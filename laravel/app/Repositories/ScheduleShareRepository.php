<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Schedule;
use App\Models\Examinator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ScheduleRepository extends Repository
{
  public function __construct()
  {
    $this->builder = new Schedule();
  }

//  /**
//   * @return mixed
//   */
//  public function othersDailySchedules()
//  {
//    $now = Carbon::now()->format('Y-m-d');
//    return $this->getBuilder()
//      ->with(['examinator'])
//      ->whereDate('start', $now)
//      ->whereNotIn('created_by', [ Auth::user()->examinator->employee_id ])
//      ->get();
//  }
}
