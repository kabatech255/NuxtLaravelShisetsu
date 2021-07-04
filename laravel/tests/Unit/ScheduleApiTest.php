<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Examinator;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ScheduleApiTest extends TestCase
{
  protected $user;

  protected function setUp(): void
  {
    parent::setUp();
    $this->seed();
    $this->user = User::with(['examinator'])->get()->first();
  }

  /**
   *
   */
  public function should_自分だけのスケジュールを登録()
  {
    $expects = [
      'body' => 'test schedule',
      'color' => 'red',
      'start' => Carbon::now()->format('Y/m/d H:i:s'),
      'end' => Carbon::now()->format('Y/m/d H:i:s'),
      'is_private' => false,
      'shared_members' => [],
      'editable_members' => [],
    ];

    $response = $this->actingAs($this->user)->postJson(route('schedule.store', ['examinator' => $this->user->examinator]), $expects);
    $response->assertCreated()->assertJson([
      'shared_members' => [ $this->user->examinator->employee_id ]
    ]);
  }

  /**
   *
   */
  public function should_他人と共有したスケジュールの登録()
  {
    $source = Examinator::take(5)->pluck('employee_id');
    $sharedMembers = $source->splice(1, 4)->toArray();
    $editableMembers = collect($sharedMembers)->splice(0, 1)->toArray();
    $expects = [
      'body' => 'test schedule',
      'color' => 'red',
      'start' => Carbon::now()->format('Y/m/d H:i:s'),
      'end' => Carbon::now()->format('Y/m/d H:i:s'),
      'is_private' => true,
      'shared_members' => $sharedMembers,
      'editable_members' => $editableMembers,
    ];

    $response = $this->actingAs($this->user)->postJson(route('schedule.store', ['examinator' => $this->user->examinator]), $expects);

    $response->assertCreated()->assertJson([
      'shared_members' => array_merge([$this->user->examinator->employee_id], $expects['shared_members']),
      'editable_members' => array_merge([$this->user->examinator->employee_id], $expects['editable_members']),
    ]);
  }


}
