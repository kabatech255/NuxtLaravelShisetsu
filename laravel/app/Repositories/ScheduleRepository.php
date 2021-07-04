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

  /**
   * @param Examinator $examinator
   * @param array $attribute
   * @return false|\Illuminate\Database\Eloquent\Model
   */
  public function store(Examinator $examinator, array $attribute)
  {
    $filled = $this->builder->fill($attribute);
    return $examinator->schedules()->save($filled);
  }

  /**
   * @return mixed
   */
  public function othersDailySchedules()
  {
    $now = Carbon::now()->format('Y-m-d');
    return $this->getBuilder()
      ->with(['sharedMembers'])
      ->whereDate('start', $now)
      ->get();
  }
}
