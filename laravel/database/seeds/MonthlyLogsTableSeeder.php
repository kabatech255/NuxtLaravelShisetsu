<?php

use Illuminate\Database\Seeder;
use App\Services\ShopService;
use App\Services\ExamService;
use App\Services\MonthlyLogService;
use App\Services\MonthlyLogDetailService;
use Illuminate\Support\Carbon;
use App\Models\Examinator;
use App\Models\Summary;
use App\Models\MonthlyLogDetail;
use App\Repositories\MonthlyLogDetailRepository;
use App\Models\MonthlyLog;

class MonthlyLogsTableSeeder extends Seeder
{
  protected $shopService;
  protected $examService;
  protected $monthlyLogService;
  protected $monthlyLogDetailService;

  public function __construct(
    ShopService $shopService,
    ExamService $examService,
    MonthlyLogService $monthlyLogService,
    MonthlyLogDetailService $monthlyLogDetailService
  )
  {
    $this->shopService = $shopService;
    $this->examService = $examService;
    $this->monthlyLogService = $monthlyLogService;
    $this->monthlyLogDetailService = $monthlyLogDetailService;
  }

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('monthly_log_details')->truncate();
    DB::table('monthly_logs')->truncate();
    $shopList = collect($this->shopService->all())->groupBy('team_code')->map(function($shopGroup, $key){
      return $this->withRelatedMember($shopGroup);
    })->values()->collapse();
    $examList = collect($this->examService->all());
    $shopList->each(function ($shop) use ($examList) {
      collect(range(0, 12))->each(function ($i) use ($examList, $shop) {
        $args = [
          'examined_year' => Carbon::parse("-{$i}month")->format('Y'),
          'examined_month' => Carbon::parse("-{$i}month")->format('m'),
          'examined_at' => Carbon::parse("-{$i}month")->firstOfMonth()->addDays(random_int(0, 25))->format('Y-m-d H:i:s'),
          'created_at' => Carbon::parse("-{$i}month")->firstOfMonth()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::parse("-{$i}month")->firstOfMonth()->format('Y-m-d H:i:s'),
        ];
        $examList->each(function ($exam) use ($shop, $args) {
          DB::table('monthly_logs')->insert([
            'examined_year' => $args['examined_year'],
            'examined_month' => $args['examined_month'],
            'store_code' => $shop['store_code'],
            'exam_code' => $exam->exam_code,
            'examined_by' => $shop['examinedBy'],
            'examined_at' => $args['examined_at'],
            'created_at' => $args['created_at'],
            'updated_at' => $args['updated_at'],
            'is_complete' => Carbon::parse($args['examined_at'])->format('Y-m') !== Carbon::now()->format('Y-m')
          ]);
        });
      });
      // 指摘内容ごとに重み付け[0: 優良, 1: 普通, 2: 劣悪]
      $weightList = $this->weightPerDetail($examList);

      $shop->monthlyLogs->each(function($monthlyLog) use($weightList) {
        $detailSeeds = $this->getDetailSeeds();
        $examIssueDetails = $monthlyLog->exam->examIssues->flatMap(function($examIssue){
          return $examIssue->examIssueDetails;
        });

        $examIssueDetails->each(function($examIssueDetail) use($detailSeeds, $monthlyLog, $weightList){
          $count = $this->countByDetailWeight($examIssueDetail, $detailSeeds, $monthlyLog, $weightList);
          $improvedList = [0, 0, 0, 0, 1, 1, 1, 1, 1, 1];
          factory(MonthlyLogDetail::class, $count)->create([
            'monthly_log_id' => $monthlyLog['id'],
            'primary_file_name' => "exams/sample_{$examIssueDetail->id}.jpg",
            'is_improved' => $improvedList[random_int(0, count($improvedList) - 1)],
            'exam_issue_detail_id' => $examIssueDetail->id,
            'created_by' => $monthlyLog->examined_by,
            'note' => null
          ]);
        });
      });
    });
  }

  private function withRelatedMember($shopGroup)
  {
    $memberList = $shopGroup->first()->belongsToTeam->examinators->toArray();
    $separated = $shopGroup->split(count($memberList));
    return $separated->flatMap(function($group, $key) use ($memberList){
      $examinedBy = $memberList[$key]['employee_id'];
      return $this->addExaminedBy($group, $examinedBy);
    });
  }

  private function addExaminedBy($shops, $examinedBy)
  {
    return $shops->map(function($shop) use ($examinedBy){
      $shop->examinedBy = $examinedBy;
      return $shop;
    });
  }

  private function weightPerDetail($examList)
  {
    return $examList->flatMap(function($exam){
      $randomWeight = [0, 0, 0, 1, 1, 1, 1, 1, 1, 2, 2, 2];
      $randomWeightDetail = [
        0 => [0, 0, 0, 0, 0, 0, 0, 0, 1, 1],
        1 => [0, 0, 0, 0, 0, 1, 1, 1, 1, 2],
        2 => [0, 1, 1, 1, 1, 1, 1, 2, 2, 2],
      ];
      $potentialitiesOfExam = $randomWeight[random_int(0, count($randomWeight) - 1)];
      return $exam->examIssues->flatMap(function($examIssue) use ($potentialitiesOfExam, $randomWeightDetail){
        return $examIssue->examIssueDetails->map(function($detail) use($potentialitiesOfExam, $randomWeightDetail){
          $statusList = $randomWeightDetail[$potentialitiesOfExam];
          return $statusList[random_int(0, count($statusList) - 1)];
        });
      });
    });
  }

  private function countByDetailWeight($examIssueDetail, $detailSeeds, $monthlyLog, $weightList)
  {
    $key = $examIssueDetail->id - 1;
    $frequency = $detailSeeds[$key]['frequency']; // 'middle' || 'wide' || 'narrow'
    $rangeList = ExamIssuesSeeder::FREQUENCIES_ARRAY[$monthlyLog->exam->exam_code][$frequency];
    if ($weightList[$examIssueDetail->id - 1] === 0) {
      $start = 1;
    } elseif ($monthlyLog->examined_month === 12) {
      // 年末は納品が多くなる関係でリスクが増えることにする
      $start = 6;
    } elseif ($weightList[$examIssueDetail->id - 1] === 2) {
      $start = 6;
    } else {
      $start = 3;
    }
    if ($weightList[$examIssueDetail->id - 1] === 0) {
      $end = 5;
    } elseif($weightList[$examIssueDetail->id - 1] === 1) {
      $end = count($rangeList) - 1;
    } else {
      $end = count($rangeList);
    }
    $range = $rangeList[random_int($start, $end)];
    return random_int($range[0], $range[1]);
  }

  private function getDetailSeeds()
  {
    return collect(ExamIssuesSeeder::EXAM_ARRAY)->flatMap(function($examSeed){
      return collect($examSeed['issues'])->flatMap(function($issueSeed){
        return $issueSeed['details'];
      });
    });
  }
}
