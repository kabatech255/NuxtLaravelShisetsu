<?php

use Illuminate\Database\Seeder;
use App\Models\Summary;
use App\Models\Examinator;
use App\Models\Exam;
use App\Models\MonthlyLog;
use App\Models\MonthlyLogDetail;
use Illuminate\Support\Carbon;

class SummariesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    DB::table('summaries')->truncate();
    Exam::all()->each(function($exam){
      collect(range(0, 12))->each(function ($i) use ($exam) {
        $description = $i < 12 ? $this->getDescription($exam, $i) : '';
        factory(Summary::class)->create([
          'year' => Carbon::parse("-{$i}month")->format('Y'),
          'month' => Carbon::parse("-{$i}month")->format('m'),
          'exam_code' => $exam->exam_code,
          'description' => $description,
          'written_by' => config('app.test_id'),
        ]);
      });
    });
  }

  private function getDescription($exam, $i)
  {
    $source = $this->getSummarySource($exam, $i);
    $state = [
      'countRate' => $this->getStateStr($source['countRate']),
      'improvedRate' => $this->getStateStr($source['improvedRate']),
    ];
    $verb = abs($source['improvedRate']) > 0 ? 'しました' : 'でした';
    return <<<EOM
    指摘総数は先月に比べ{$state['countRate']}、改善率は先月に比べ{$state['improvedRate']}{$verb}。\n
    上昇率が最も高かった指摘内容は{$source['maxIssue']['name']}（{$source['maxIssue']['prevCount']}→{$source['maxIssue']['count']}）で、約{$source['maxIssue']['diff']}％上昇しました。
    EOM;
  }

  private function getSummarySource($exam, $i)
  {
    $currentLogs = $this->logsPerIssueDetail($exam, $i);
    $prevLogs = $this->logsPerIssueDetail($exam, $i + 1);
    $currentCount = $currentLogs->sum('count');
    $prevCount = $prevLogs->sum('count');
    $countRate = round($currentCount * 100 / $prevCount, 1) - 100;
    $improvedRate = $this->getImprovedRate($currentLogs, $prevLogs);
    $maxIssue = $this->getMaxIssue($currentLogs, $prevLogs);
    return [
      'countRate' => $countRate,
      'improvedRate' => $improvedRate,
      'maxIssue' => $maxIssue,
    ];
  }

  /**
   * @param Exam $exam
   * @param int $i
   * @return \Illuminate\Support\Collection
   */
  private function logsPerIssueDetail(Exam $exam, int $i)
  {
    $monthlyLogIds = MonthlyLog::where('exam_code', $exam->exam_code)
      ->where('examined_year', Carbon::parse("-{$i}month")->format('Y'))
      ->where('examined_month', Carbon::parse("-{$i}month")->format('m'))
      ->get()
      ->pluck('id');

    return MonthlyLogDetail::whereIn('monthly_log_id', $monthlyLogIds)
      ->get()
      ->groupBy('exam_issue_detail_id')
      ->map(function($monthlyLogDetails) use ($exam, $i){
        return collect([
          'id' => $monthlyLogDetails->first()->exam_issue_detail_id,
          'name' => $monthlyLogDetails->first()->body,
          'count' => $monthlyLogDetails->count(),
          'improved_count' => $monthlyLogDetails->sum('is_improved'),
        ]);
      });
  }

  /**
   * @param \Illuminate\Support\Collection $currentLogs
   * @param \Illuminate\Support\Collection $prevLogs
   * @return float
   */
  private function getImprovedRate(\Illuminate\Support\Collection $currentLogs, \Illuminate\Support\Collection $prevLogs)
  {
    $currentImprovedRate = round($currentLogs->sum('improved_count') * 100 / $currentLogs->sum('count'), 1);
    $prevImprovedRate = round($prevLogs->sum('improved_count') * 100 / $prevLogs->sum('count'), 1);
    return $currentImprovedRate - $prevImprovedRate;
  }

  /**
   * @param $currentLogs
   * @param $prevLogs
   * @return mixed
   */
  private function getMaxIssue(\Illuminate\Support\Collection $currentLogs, \Illuminate\Support\Collection $prevLogs)
  {
    $added = $currentLogs->map(function($monthlyLogDetail) use ($prevLogs){
      $prev = $prevLogs->where('id', $monthlyLogDetail->get('id'))->first();
      $div = collect($prev)->get('count') == 0 ? 1 : collect($prev)->get('count');
      $diff = round(collect($monthlyLogDetail)->get('count') / $div * 100, 1) - 100;
      $monthlyLogDetail->put('diff', $diff);
      $monthlyLogDetail->put('prevCount', $div);
      return $monthlyLogDetail;
    });
    return $added->where('diff', $added->max('diff'))->first();
  }

  /**
   * @param $rate
   * @return string
   */
  private function getStateStr($rate)
  {
    if ($rate > 0) {
      return "約{$rate}%上昇";
    } else {
      $abs = abs($rate);
      return $rate !== 0 ? "約{$abs}％減少" : '変化なし';
    }
  }
}
