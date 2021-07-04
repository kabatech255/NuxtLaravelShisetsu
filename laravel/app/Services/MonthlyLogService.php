<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\MonthlyLogRepository;
use App\Services\ExamService;
use App\Services\ShopService;
use App\Models\Exam;
use App\Models\MonthlyLog;
use App\Models\Shop;

class MonthlyLogService extends Service
{
  protected $monthlyLogRepository;
  protected $examService;
  protected $shopService;

  public function __construct(
    ExamService $examService,
    ShopService $shopService,
    MonthlyLogRepository $monthlyLogRepository
  )
  {
    $this->monthlyLogRepository = $monthlyLogRepository;
    $this->examService = $examService;
    $this->shopService = $shopService;
  }

  /**
   * @param array $requestArr
   * @return \Illuminate\Support\Collection
   */
  public function store(array $requestArr)
  {
    $shop = $this->shopService->findByCode(['monthlyLogs'], (int)$requestArr['store_code']);
    if (!$shop->has_current_record) {
      $examList = collect($this->examService->all());
      $newLog = $examList->map(function ($exam) use ($requestArr) {
        $requestArr['exam_code'] = $exam->exam_code;
        $monthlyLogRepository = new MonthlyLogRepository();
        return $monthlyLogRepository->store($requestArr);
      });
    }
    return $this->examApi($shop);
  }

  /**
   * @param MonthlyLog $monthlyLog
   * @return \Illuminate\Support\Collection
   */
  public function complete(MonthlyLog $monthlyLog)
  {
    $this->monthlyLogRepository->update($monthlyLog, [
      'is_complete' => 1,
      'examined_at' => now()
    ]);
    $shop = $this->shopService->findByCode([], (int)$monthlyLog->store_code);
    return $this->examApi($shop);
  }

  /**
   * @param Shop $shop
   * @return \Illuminate\Support\Collection
   */
  public function examApi(Shop $shop)
  {
    $api = collect(['shop', 'records']);
    return $api->combine([$shop, $this->monthlyLogOfShop((int)$shop->store_code)]);
  }

  /**
   * @param int $storeCode
   * @return mixed
   */
  public function monthlyLogOfShop(int $storeCode)
  {
    $examApi = collect(['exam', 'monthlyLogs']);
    $shop = $this->shopService->findByCode(['monthlyLogs.examinator', 'monthlyLogs.monthlyLogDetails.creator', 'monthlyLogs.monthlyLogDetails.examIssueDetail.examIssue.examIssueDetails'], $storeCode);
    return $shop->monthlyLogs->sortByDesc('examined_at')->values()->groupBy('exam_code')->map(function ($record) use ($examApi) {
      $exam = $this->examService->findByCode(Exam::RELATIONS_ARRAY, (int)$record[0]->exam_code);
      return $examApi->combine([$exam, $record]);
    });
  }

  /**
   * @param array $relation
   * @param int $id
   * @return mixed
   */
  public function findById(array $relation, int $id)
  {
    return $this->monthlyLogRepository->findById($relation, $id);
  }

}
