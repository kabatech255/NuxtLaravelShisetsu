<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\MonthlyLogRepository;
use App\Repositories\SummaryRepository;
use App\Repositories\ScheduleRepository;
use App\Services\ScheduleService;
use App\Services\MonthlyLogDetailService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardService extends Service
{
  protected $monthlyLogRepository;
  protected $summaryRepository;
  protected $scheduleRepository;
  protected $scheduleService;
  protected $examService;
  protected $shopService;
  protected $monthlyLogDetailService;

  const DOUGHNUT_MAXIMUM_LENGTH = 9;

  public function __construct(
    MonthlyLogRepository $monthlyLogRepository,
    SummaryRepository $summaryRepository,
    ScheduleRepository $scheduleRepository,
    ScheduleService $scheduleService,
    ExamService $examService,
    ShopService $shopService,
    MonthlyLogDetailService $monthlyLogDetailService
  )
  {
    $this->monthlyLogRepository = $monthlyLogRepository;
    $this->summaryRepository = $summaryRepository;
    $this->scheduleRepository = $scheduleRepository;
    $this->scheduleService = $scheduleService;
    $this->examService = $examService;
    $this->shopService = $shopService;
    $this->monthlyLogDetailService = $monthlyLogDetailService;
  }

  public function index()
  {
    $scores = $this->getScores();
    $bookmarks = $this->getMyBookmarks();
    $todos = Auth::user()->examinator->todos->sortBy('id')->sortBy('is_done')->values();
    $schedules = $this->getMySchedules();
    $othersDailySchedule = $this->othersDailySchedule();
    return [
      'scores' => $scores,
      'bookmarks' => $bookmarks,
      'todos' => $todos,
      'schedules' => $schedules,
      'othersDailySchedule' => $othersDailySchedule
    ];
  }

  /**
   * @return \App\Models\MonthlyLog[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
   */
  private function getScores()
  {
    return Auth::user()->examinator->monthlyLogs->filter(function($monthlyLog) {
      return Carbon::parse($monthlyLog->examined_at) > $this->getCurrentDate();
    })->sortByDesc('date_number')->splice(0, 15)->map(function($monthlyLog){
      $date = Carbon::parse($monthlyLog->examined_at);
      $examined_at = $date->year < $this->getCurrentYear() ? $date->format('Y年n月j日') : $date->format('n月j日');
      return [
        'examined_at' => $examined_at,
        'store_code' => $monthlyLog->store_code,
        'store_name' => $monthlyLog->shop->name,
        'exam_name' => $monthlyLog->exam->name,
        'total' => $monthlyLog->monthlyLogDetails->count(),
      ];
    });
  }

  /**
   * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
   */
  private function getMyBookmarks()
  {
    return $this->myMonthlyLogDetails()->map(function($monthlyLogDetail){
      return $this->monthlyLogDetailService->transformToArticle($monthlyLogDetail, true);
    })->sortByDesc('worried_at')->values();
  }

  /**
   * @return \App\Models\Schedule[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
   */
  private function getMySchedules()
  {
    return Auth::user()->examinator->sharedSchedules->map(function($schedule){
      return $this->scheduleService->transformForVCalendar($schedule);
    });
  }

  /**
   * @return mixed
   */
  private function othersDailySchedule()
  {

    $dailySchedules = $this->scheduleRepository->othersDailySchedules();
    return $dailySchedules->filter(function($schedule){
      $schedule->sharedMembers->each(function($member, $index) use($schedule){
        if ($member->employee_id === Auth::user()->examinator->employee_id) {
          $schedule->sharedMembers->splice($index, 1);
        }
      });
      return $schedule->sharedMembers->count() > 0;
    })->map(function($schedule){
      $body = $schedule->is_private ? '予定あり' : $schedule->body;
      return [
        'body' => $body,
        'is_private' => $schedule->is_private,
        'shared_members' => $schedule->sharedMembers
      ];
    })->values();
  }

  /**
   * @return \App\Models\MonthlyLogDetail[]|\Illuminate\Database\Eloquent\Collection
   */
  private function myMonthlyLogDetails()
  {
    return Auth::user()->examinator->worries->slice(0, 10);
  }

  /**
   * @return string
   */
  private function getCurrentYearMonth(): string
  {
    return Carbon::now()->format('Y-n');
  }

  /**
   * @return int
   */
  private function getCurrentYear(): int
  {
    return Carbon::now()->year;
  }

  /**
   * @return Carbon
   */
  private function getCurrentDate(): Carbon
  {
    return Carbon::parse('-1month')->firstOfMonth();
  }

  private function getExaminedStr($dateTime)
  {
    $year = Carbon::parse($dateTime)->format('Y');
    $month = Carbon::parse($dateTime)->month;
    $day = Carbon::parse($dateTime)->day;
    return Carbon::parse($dateTime)->month;
  }
}
