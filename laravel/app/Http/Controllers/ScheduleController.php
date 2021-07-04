<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Schedule\StoreRequest;
use Illuminate\Support\Facades\DB;
use App\Services\ScheduleService;
use App\Models\Examinator;
use App\Models\Schedule;

class ScheduleController extends Controller
{

  protected $scheduleService;

  public function __construct(ScheduleService $scheduleService)
  {
    $this->scheduleService = $scheduleService;
  }

  /**
   * @param Examinator $examinator
   * @param StoreRequest $request
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   * @throws \Throwable
   */
  public function store(Examinator $examinator, StoreRequest $request)
  {
    DB::beginTransaction();
    try {
      $response = $this->scheduleService->store($examinator, $request->all());
      DB::commit();
    } catch(\Exception $e) {
      DB::rollback();
      throw $e;
    }
    return response($response, 201);
  }

  /**
   * @param Examinator $examinator
   * @param Schedule $schedule
   * @param StoreRequest $request
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   * @throws \Throwable
   */
  public function update(Examinator $examinator, Schedule $schedule, StoreRequest $request)
  {
    DB::beginTransaction();
    try {
      $response = $this->scheduleService->update($request->all(), $schedule);
      DB::commit();
    } catch(\Exception $e) {
      DB::rollback();
      throw $e;
    }
    return response($response, 200);
  }


  /**
   * @param Examinator $examinator
   * @param Schedule $schedule
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   * @throws \Throwable
   */
  public function destroy(Examinator $examinator, Schedule $schedule)
  {
    // 編集権限機能を実装したらバリデーションをかける
    DB::beginTransaction();
    try {
      $response = $this->scheduleService->delete($schedule);
      DB::commit();
    } catch(\Exception $e) {
      DB::rollBack();
      throw $e;
    }
    return response([], 204);
  }
}
