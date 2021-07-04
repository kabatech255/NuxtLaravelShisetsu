<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\MonthlyLogRepository;
use App\Repositories\SummaryRepository;
use App\Services\ExamService;
use App\Services\ShopService;
use App\Models\Exam;
use App\Models\MonthlyLog;
use App\Models\Shop;
use App\Models\Summary;
use Illuminate\Support\Carbon;
class AnalysisService extends Service
{
  protected $monthlyLogRepository;
  protected $summaryRepository;
  protected $examService;
  protected $shopService;

  const DOUGHNUT_MAXIMUM_LENGTH = 9;

  public function __construct(
    ExamService $examService,
    ShopService $shopService,
    MonthlyLogRepository $monthlyLogRepository,
    SummaryRepository $summaryRepository
  )
  {
    $this->monthlyLogRepository = $monthlyLogRepository;
    $this->summaryRepository = $summaryRepository;
    $this->examService = $examService;
    $this->shopService = $shopService;
  }

  public function index(int $examCode)
  {
    // ドロップダウン用の検査リスト
    $vals = $this->examService->all()->map(function($exam) {
      return [
        'name' => $exam->name,
        'color' => $exam->color,
      ];
    });
    $examList = collect($this->examService->examCodeArr())->combine($vals->all());

    $exam = $this->examService->findByCode(['monthlyLogs.shop'], $examCode);
    $perYearMonth = $this->makeLogsPerMonth($exam);
    // チャートデータ
    $chartData = $this->getChartData($perYearMonth, $exam);

    // $summaryDescription = 責任者からの一言
    $yearMonth = (string)collect($perYearMonth->keys())->first();
    $year = substr($yearMonth, 0, 4);
    $month = substr($yearMonth, 4, 2);
    $summaryDescription = $this->summaryRepository->findBySpecificCode(Summary::RELATIONS_ARRAY, [
      'year' => $year,
      'month' => $month,
      'examCode' => $exam->exam_code,
    ]);

    return collect(['examList', 'chartData', 'summary', 'examCode'])->combine([$examList, $chartData->all(), $summaryDescription, $exam->exam_code]);
  }

  /**
   * @param int $examCode
   * @return \Illuminate\Support\Collection
   */
  public function showShopRanks(int $examCode)
  {
    $exam = $this->examService->findByCode(['monthlyLogs.shop'], $examCode);
    $perYearMonth = $this->makeLogsPerMonth($exam);
    return $this->getShopRanks($perYearMonth, $exam);
  }

  /**
   * @param Exam $exam
   * @return MonthlyLog[]|\Illuminate\Database\Eloquent\Collection
   */
  private function makeLogsPerMonth(Exam $exam)
  {
    return $exam->monthlyLogs->sortBy('id')->values()->groupBy('year_month_number');
  }

  /**
   * @param $perYearMonth
   * @return mixed
   */
  private function makeThreeMonth($perYearMonth)
  {
    return $perYearMonth->sortKeys()->slice(10, 3);
  }

  /**
   * chartDataの内訳
   * 直近12ヶ月の月別指摘総数棒グラフ
   * 直近3ヶ月の指摘項目別棒グラフ
   * 直近3ヶ月の指摘内容内訳ドーナツグラフ
   * 総改善率のドーナツグラフ
   * 直近3ヶ月の支社別平均指摘総数棒グラフ
   * 直近12ヶ月の支社別改善率ドーナツグラフ
   * * [廃止]ワースト5店舗テーブル
   * * [廃止]ベスト5店舗テーブル
   * 今月のブックマーク数ランキング
   * 店舗別指摘総数一覧
   * @param $perYearMonth
   * @return \Illuminate\Support\Collection
   */
  private function getChartData($perYearMonth, $exam)
  {
    $threeMonth = $this->makeThreeMonth($perYearMonth);

    $summary = $this->getSummaryData($perYearMonth);
    $perIssue = $this->getIssueData($threeMonth, $exam);
    $perIssueDetail = $this->getIssueDetailData($threeMonth, $exam);
    $rateOfImprove = $this->getImprovedRate($perYearMonth);
    $perBranch = $this->getPerBranch($threeMonth);
    $improvedRatePerBranch = $this->getImprovedRatePerBranch($perYearMonth);
//    $worst = $this->getWorst($perYearMonth);
//    $best = $this->getBest($perYearMonth);
    $worries = $this->monthlyWorriedRank($perYearMonth);
//    $shopRanks = $this->getShopRanks($perYearMonth, $exam);
    return collect([
      'examCode',
      'summary',
      'perIssue',
      'perIssueDetail',
      'rateOfImprove',
      'perBranch',
      'improvedRatePerBranch',
//      'worst',
//      'best',
      'worries',
//      'shopRanks',
    ])->combine([
      $exam->exam_code,
      $summary,
      $perIssue,
      $perIssueDetail,
      $rateOfImprove,
      $perBranch,
      $improvedRatePerBranch,
//      $worst,
//      $best,
      $worries,
//      $shopRanks,
    ]);
  }

  /**
   * @param $perYearMonth
   * @return \Illuminate\Support\Collection
   */
  private function getSummaryData($perYearMonth)
  {
    $datasets = $this->getSummaryDatasets($perYearMonth);
    $labels = $perYearMonth->map(function($monthlyLogs){
      return $monthlyLogs->first()->year_month;
    });
    $labels->pop();
    return collect(['datasets', 'labels'])->combine([$datasets, $labels->sortKeys()->values()]);
  }

  /**
   * @param $perYearMonth
   * @return \Illuminate\Support\Collection
   */
  private function getSummaryDatasets($perYearMonth)
  {
    $totalArrPerMonth = $perYearMonth->map(function($monthlyLogs){
      return $this->monthlyTotal($monthlyLogs);
    });
    $compare = $this->comparePrevMonth($totalArrPerMonth);
    $improvedArrPerMonth = $perYearMonth->map(function($monthlyLogs){
      return $this->monthlyImprovedCount($monthlyLogs);
    });
    $totalArrPerMonth->pop();
    $compare->pop();
    $improvedArrPerMonth->pop();
    return collect(['total', 'compare', 'improvedCount'])->combine([$totalArrPerMonth->sortKeys()->values(), $compare, $improvedArrPerMonth->sortKeys()->values()]);
  }

  /**
   * @param $threeMonth
   * @param $exam
   * @return \Illuminate\Support\Collection
   */
  private function getIssueData($threeMonth, $exam)
  {
    $issueDatasets = $this->getIssueDatasets($threeMonth, $exam);
    $issueLabels = $exam->examIssues->pluck('name');
    return collect(['datasets', 'labels'])->combine([$issueDatasets, $issueLabels]);
  }

  /**
   * @param $threeMonth
   * @param $exam
   * @return mixed
   */
  private function getIssueDetailData($threeMonth, $exam)
  {
    $issueDetailList = $exam->examIssues->map(function($examIssue){
      return $examIssue->examIssueDetails;
    })->collapse();

    $issueDetailGroups = $threeMonth->map(function($monthlyLogs, $key) use($exam){
      return $monthlyLogs->map(function($monthlyLog){
        return $monthlyLog->monthlyLogDetails;
      })->collapse()->groupBy('exam_issue_detail_id')->values();
    });

    return $issueDetailGroups->map(function($monthlyLogDetailsGroup, $label) use ($issueDetailList){
      $countsPerIssueDetail = $this->countsPerIssueDetail($monthlyLogDetailsGroup, $issueDetailList);
      $separated = $this->separateCounts($countsPerIssueDetail);

      return [
        'label' => $label,
        'data' => $separated->pluck('total'),
        'labels' => $separated->pluck('issue_content')
      ];

    })->values();
  }

  /**
   * @param $monthlyLogDetailsGroup
   * @param $issueDetailList
   * @return mixed
   */
  private function countsPerIssueDetail($monthlyLogDetailsGroup, $issueDetailList)
  {
    return $monthlyLogDetailsGroup->map(function($monthlyLogDetails) use ($issueDetailList){
      $examIssueDetailId = $monthlyLogDetails->first()->exam_issue_detail_id;
      $examIssueDetail = $issueDetailList->where('id', $examIssueDetailId)->first();
      $body = "{$examIssueDetail->issue_content}（{$examIssueDetail->examIssue->name}）";
      return [
        'total' => $monthlyLogDetails->count(),
        'issue_content' => $body
      ];
    })->sortByDesc('total')->values();
  }

  /**
   * @param $countsPerIssueDetail
   * @param $issueDetailList
   * @return mixed
   */
  private function separateCounts($countsPerIssueDetail)
  {
    $denominator = $countsPerIssueDetail->sum('total');
    $breakPoint = $countsPerIssueDetail->search(function($item, $key) use($countsPerIssueDetail, $denominator) {
      $compare = $countsPerIssueDetail->slice(0, $key)->sum('total');
      return $compare > $denominator * 0.8;
    });
    if ($breakPoint > self::DOUGHNUT_MAXIMUM_LENGTH) {
      $max = self::DOUGHNUT_MAXIMUM_LENGTH;
    } else {
      $max = $breakPoint;
    }
    list($larger, $others) = $countsPerIssueDetail->partition(function($counts, $key)use ($max){
      return $key < $max;
    });
    $larger->push([
      'issue_content' => 'その他',
      'total' => $others->sum('total')
    ]);
    return $larger;
  }

  /**
   * @param $threeMonth
   * @param $exam
   * @return mixed
   */
  private function getIssueDatasets($threeMonth, $exam)
  {
    return $threeMonth->map(function($monthlyLogs) use($exam){
      $monthlyLogDetails = $monthlyLogs->map(function($monthlyLog) {
        return $monthlyLog->monthlyLogDetails;
      })->flatten();

      $countsPerIssue = $exam->examIssues->map(function($issue) use ($monthlyLogDetails){
        return $monthlyLogDetails->filter(function($detail) use ($issue){
          return $detail->belongs_to_issue->id === $issue->id;
        })->count();
      });

      return [
        'label' => $monthlyLogs->first()->year_month,
        'data' => $countsPerIssue
      ];
    })->values();
  }

  /**
   * @param $perYearMonth
   * @return array
   */
  private function getImprovedRate($perYearMonth)
  {
    $totalArrPerMonth = $perYearMonth->map(function($monthlyLogs){
      return $this->monthlyTotal($monthlyLogs);
    });
    $improvedArrPerMonth = $perYearMonth->map(function($monthlyLogs){
      return $this->monthlyImprovedCount($monthlyLogs);
    });
    $totalArrPerMonth->pop();
    $improvedArrPerMonth->pop();
    $total = $totalArrPerMonth->sum();
    $improved = $improvedArrPerMonth->sum();
    $improvedRate = $total === 0 ? 0 : round($improved / $total * 100, 1);

    return [
      'data' => [$total, $improved],
      'improved_rate' => $improvedRate,
    ];
  }

  /**
   * @param $threeMonth
   * @return \Illuminate\Support\Collection
   */
  private function getPerBranch($threeMonth)
  {
    $branchGroups = $threeMonth->map(function($monthlyLogs, $key){
      $logsPerBranch = $monthlyLogs->map(function($monthlyLog){
        return [
          'count' => $monthlyLog->monthlyLogDetails->count(),
          'branch_code' => $monthlyLog->shop->branch->branch_code,
          'branch_name' => $monthlyLog->shop->branch->name,
        ];
      })->groupBy('branch_code')->values();
      return $logsPerBranch->map(function($logs){
        return [
          'branch_name' => collect($logs->first())->get('branch_name'),
          'avg' => round(collect($logs)->avg('count'), 1),
        ];
      });
    });

    $datasets = $branchGroups->map(function($branchGroup, $label){
      return [
        'data' => $branchGroup->pluck('avg'),
        'label' => substr((string)$label, 0, 4) . '-' . sprintf('%01d', substr((string)$label, 4, 2)) . '（1店舗あたり）',
      ];
    })->values();

    $labels = collect($branchGroups)->first()->pluck('branch_name');
    return collect(['datasets', 'labels'])->combine([$datasets, $labels]);
  }

  /**
   * @param $perYearMonth
   * @return mixed
   */
  private function getImprovedRatePerBranch($perYearMonth)
  {
    $perYearMonth->pop();
    $monthlyLogs = $perYearMonth->map(function($monthlyLogs){
      return $monthlyLogs->map(function($monthlyLog){
        return [
          'count' => $monthlyLog->monthlyLogDetails->count(),
          'improved_count' => $monthlyLog->monthlyLogDetails->filter(function($monthlyLogDetail){
            return $monthlyLogDetail->is_improved;
          })->count(),
          'branch_code' => $monthlyLog->shop->branch->branch_code,
          'branch_name' => $monthlyLog->shop->branch->name,
        ];
      });
    })->collapse();

    return $monthlyLogs->groupBy('branch_code')->values()->map(function($logs){
      $total = collect($logs)->sum('count');
      $improved = collect($logs)->sum('improved_count');
      $improvedRate = $total === 0 ? 0 : round($improved / $total * 100, 1);
      return [
        'total' => $total,
        'improved_total' => $improved,
        'improved_rate' => $improvedRate,
        'branch_name' => collect($logs->first())->get('branch_name'),
      ];
    });
  }

  /**
   * @param $perYearMonth
   * @return mixed
   */
  private function getWorst($perYearMonth)
  {
    return $perYearMonth->first()->sortByDesc(function($monthlyLog) {
      return $monthlyLog->monthlyLogDetails->count();
    })->splice(0, 5)->map(function($monthlyLog){
      return [
        'zerofill_code' => $monthlyLog->shop->zerofill_code,
        'name' => $monthlyLog->shop->name,
        'total' => $monthlyLog->monthlyLogDetails->count(),
      ];
    })->values();
  }

  /**
   * @param $perYearMonth
   * @return mixed
   */
  private function getBest($perYearMonth)
  {
    return $perYearMonth->first()->sortBy(function($monthlyLog) {
      return $monthlyLog->store_code;
    })->sortBy(function($monthlyLog) {
      return $monthlyLog->monthlyLogDetails->count();
    })->splice(0, 5)->map(function($monthlyLog){
      return [
        'zerofill_code' => $monthlyLog->shop->zerofill_code,
        'name' => $monthlyLog->shop->name,
        'total' => $monthlyLog->monthlyLogDetails->count(),
      ];
    })->values();
  }

  /**
   * @param $monthlyLogs
   * @return mixed
   */
  private function monthlyTotal($monthlyLogs)
  {
    return $monthlyLogs->map(function($monthlyLog){
      return $monthlyLog->monthlyLogDetails->count();
    })->sum();
  }

  /**
   * @param $monthlyLogs
   * @return mixed
   */
  private function monthlyImprovedCount($monthlyLogs)
  {
    return $monthlyLogs->map(function($monthlyLog){
      return $monthlyLog->monthlyLogDetails->filter(function($detail){
        return $detail->is_improved;
      })->count();
    })->sum();
  }

  /**
   * @param $totalArrPerMonth
   * @return mixed
   */
  private function comparePrevMonth($totalArrPerMonth)
  {
    return $totalArrPerMonth->sortKeys()->values()->map(function($val, $key) use ($totalArrPerMonth){
      return $totalArrPerMonth->sortKeys()->slice($key, 2);
    })->map(function($pair){
      return $pair->first() === 0 ? 0 : round($pair->last() / $pair->first() * 100, 1);
    });
  }

  /**
   * @param $perYearMonth
   * @return array
   */
  private function monthlyWorriedRank($perYearMonth)
  {
    $worriedList = $perYearMonth->first()->map(function($monthlyLog){
      $worriedDetails = $monthlyLog->monthlyLogDetails->filter(function($monthlyLogDetail){
        return $monthlyLogDetail->worries_count > 0;
      })->map(function($monthlyLogDetail){
        return [
          'id' => $monthlyLogDetail->id,
          'worries_count' => $monthlyLogDetail->worries_count,
          'body' => $monthlyLogDetail->body,
          'primary_file_path' => $monthlyLogDetail->primary_file_path,
          'creator' => $monthlyLogDetail->creator,
          'shop' => $monthlyLogDetail->monthlyLog->shop,
        ];
      });
      return $worriedDetails->count() === 0 ? [] : $worriedDetails;
    })->flatten(1);
    return $worriedList->isEmpty() ? [] : $worriedList->sortByDesc('worries_count')->splice(0, 5);
  }

  /**
   * @param $perYearMonth
   * @param $exam
   * @return \Illuminate\Support\Collection
   */
  private function getShopRanks($perYearMonth, $exam)
  {
    $fields = $perYearMonth->keys()->map(function($key) use ($exam){
      $keyName = substr((string)$key, 0, 4) . '-' . sprintf('%01d', substr((string)$key, 4, 2));
      $labelName = substr((string)$key, 2, 2) . '年' . sprintf('%01d', substr((string)$key, 4, 2)) . '月';
      return [
        'key' => $keyName,
        'label' => $labelName,
        'style' => "text-align: center; width: 90px; color: {$exam->color}",
        'sort_status' => 'asc',
      ];
    });
    $fields->prepend([
      'key' => 'avg',
      'label' => '月平均',
      'style' => "text-align: center; width: 90px; color: {$exam->color}",
      'sort_status' => 'asc',
    ]);
    $fields->prepend([
      'key' => 'name',
      'label' => '店舗名',
      'link' => [
        'name' => 'mypage-exam',
        'query' => 'store_code',
        'params' => [
          'before' => [
            'name' => '集計'
          ]
        ]
      ],
      'ellipsis' => true,
      'style' => "text-align: center; width: 155px; color: {$exam->color}",
      'sort_status' => 'asc',
    ]);
    $fields->prepend([
      'key' => 'zerofill_code',
      'label' => '店舗番号',
      'style' => "text-align: center; width: 90px; color: {$exam->color}",
      'sort_status' => 'asc',
    ]);
    $items = $exam->monthlyLogs->sortByDesc('id')->values()->groupBy('store_code')->values()->map(function($monthlyLogs){
      $shopData = $monthlyLogs->map(function($monthlyLog){
        $ym = (string)$monthlyLog->year_month;
        return [
          'zerofill_code' => $monthlyLog->shop->zerofill_code,
          'store_code' => $monthlyLog->shop->store_code,
          'name' => $monthlyLog->shop->name,
          $ym => $monthlyLog->monthlyLogDetails->count(),
        ];
      })->collapse();
      $sum = $shopData->map(function($item, $key){
        if (preg_match('/^[0-9]{4}-[0-9]{1,2}$/', $key)) {
          return $item;
        }
        return 0;
      })->sum();
      $shopData->put('avg', round($sum / 13, 1));
      return $shopData;
    })->sortBy('store_code')->values();
    return collect(['fields', 'items'])->combine([$fields, $items]);
  }

}
