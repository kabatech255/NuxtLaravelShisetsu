<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\ScheduleRepository;
use App\Models\Examinator;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class ScheduleService extends Service
{
  protected $scheduleRepository;

  public function __construct(ScheduleRepository $scheduleRepository)
  {
    $this->scheduleRepository = $scheduleRepository;
  }

  /**
   * @param Examinator $examinator
   * @param array $attributes
   * @return array
   */
  public function store(Examinator $examinator, array $attributes)
  {
    $sharedMembers = $attributes['shared_members'] === null ? null : explode(',', $attributes['shared_members']);
    $editableMembers = $attributes['editable_members'] === null ? null : explode(',', $attributes['editable_members']);

    $newSchedule = $this->scheduleRepository->store($examinator, $attributes);

    $schedule = Schedule::find($newSchedule->id);
    $this->fetchSharing($sharedMembers, $editableMembers, $schedule);
    $schedule->load('sharedMembers');

    return $this->transformForVCalendar($schedule);
  }

  /**
   * @param array $attributes
   * @param Schedule $schedule
   * @return array
   */
  public function update(array $attributes, Schedule $schedule)
  {
    $sharedMembers = $attributes['shared_members'] === null ? null : explode(',', $attributes['shared_members']);
    $editableMembers = $attributes['editable_members'] === null ? null : explode(',', $attributes['editable_members']);

    $schedule->fill($attributes)->save();

    $this->fetchSharing($sharedMembers, $editableMembers, $schedule);
    $schedule->load('sharedMembers');

    return $this->transformForVCalendar($schedule);
  }

  /**
   * @param $sharedMembers
   * @param $editableMembers
   * @param $schedule
   */
  private function fetchSharing($sharedMembers, $editableMembers, $schedule)
  {
    if ($sharedMembers !== null && count($sharedMembers) > 0) {
      $args = $this->arrayWithEditable($sharedMembers, $editableMembers);
      // 各共有者の編集権の有無含めて登録
      $schedule->sharedMembers()->sync($args);
      $this->shareSelf($schedule);
    } else {
      // 編集権はデフォルトで0なので1にする必要あり
      $this->shareSelf($schedule);
    }
  }

  /**
   * @param $newSchedule
   */
  private function shareSelf($newSchedule)
  {
    $employeeId = Auth::user()->examinator->employee_id;
    $newSchedule->sharedMembers()->detach($employeeId);
    $newSchedule->sharedMembers()->attach($employeeId, [
      'edit_permission' => 1
    ]);
  }

  /**
   * @param Schedule $schedule
   * @return bool|null
   * @throws \Exception
   */
  public function delete(Schedule $schedule)
  {
    $schedule->sharedMembers()->sync([]);
    return $schedule->delete();
  }

  /**
   * @param Schedule $schedule
   * @return array
   */
  public function transformForVCalendar(Schedule $schedule)
  {
    return [
      'id' => $schedule->id,
      'examinator' => $schedule->examinator,
      'color' => $schedule->color,
      'body' => $schedule->body,
      'start' => $this->replaceHyphenToSlash($schedule->start),
      'end' => $this->replaceHyphenToSlash($schedule->end),
      'is_private' => $schedule->is_private,
      'can_edit' => $schedule->can_edit,
      'shared_members' => $this->getSharedMembers($schedule->sharedMembers),
      'editable_members' => $this->getEditables($schedule->sharedMembers)
    ];
  }

  private function getSharedMembers($members)
  {
    return $members->map(function($member){
      return [
        'employee_id' => $member->employee_id,
        'name' => $member->name,
        'team_code' => $member->team_code,
        'edit_permission' => $member->pivot->edit_permission,
      ];
    });
  }

  /**
   * @param $members
   * @return mixed
   */
  private function getEditables($members)
  {
    $editableMembers = $members->filter(function($member){
      return $member->pivot->edit_permission;
    })->values();
    return $editableMembers->map(function($member){
      return [
        'employee_id' => $member->employee_id,
        'name' => $member->name,
        'team_code' => $member->team_code,
        'edit_permission' => $member->pivot->edit_permission,
      ];
    });
  }

  /**
   * @param string $date
   * @return string
   */
  private function replaceHyphenToSlash(string $date): string
  {
    return str_replace('-', '/', $date);
  }

  /**
   * @param $sharedMembers
   * @param $editable
   * @return array
   */
  private function arrayWithEditable($sharedMembers, $editable)
  {
    $result = $editable === null ? [] : $editable;
    return collect($sharedMembers)->mapWithKeys(function($employeeId) use ($result) {
      return [
        $employeeId => [
          'edit_permission' => collect($result)->some($employeeId)
        ]
      ];
    })->toArray();
  }
}
