<?php

namespace App\Http\Controllers;

use App\Rules\AfterCompleteRule;
use App\Rules\ExceptOwnerRule;
use Illuminate\Http\Request;
use App\Http\Requests\MonthlyLogDetail\StoreRequest;
use App\Http\Requests\MonthlyLogDetail\UpdateRequest;
use App\Models\MonthlyLog;
use App\Models\MonthlyLogDetail;
use App\Services\MonthlyLogDetailService;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MonthlyLogDetailController extends Controller
{
  protected $monthlyLogDetailService;
  protected $fileUploadService;

  public function __construct(
    MonthlyLogDetailService $monthlyLogDetailService,
    FileUploadService $fileUploadService
  )
  {
    $this->monthlyLogDetailService = $monthlyLogDetailService;
    $this->fileUploadService = $fileUploadService;
  }

  /**
   * @param MonthlyLog $monthlyLog
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   */
  public function index(MonthlyLog $monthlyLog)
  {
    return response($this->monthlyLogDetailService->getExamApi($monthlyLog->store_code), 200);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * @param MonthlyLog $monthlyLog
   * @param StoreRequest $request
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   * @throws \Throwable
   */
  public function store(MonthlyLog $monthlyLog, StoreRequest $request)
  {
    $request->validate([
      'exam_issue_detail_id' => [new AfterCompleteRule($monthlyLog->is_complete)],
    ]);
    DB::beginTransaction();
    try {
      $examApi = $this->monthlyLogDetailService->store($monthlyLog, $request->all());
      DB::commit();
    } catch(\Exception $e) {
      DB::rollBack();
      throw $e;
    }
    return response($examApi, 201);
  }

  /**
   * @param MonthlyLog $monthlyLog
   * @param MonthlyLogDetail $monthlyLogDetail
   * @param UpdateRequest $request
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Illuminate\Support\Collection
   * @throws \Throwable
   */
  public function update(MonthlyLog $monthlyLog, MonthlyLogDetail $monthlyLogDetail, UpdateRequest $request)
  {
    $request->validate([
      'exam_issue_detail_id' => new ExceptOwnerRule($monthlyLogDetail->created_by),
    ]);
    DB::beginTransaction();
    try {
      $examApi = $this->monthlyLogDetailService->update($monthlyLog, $monthlyLogDetail, $request->all());
      DB::commit();
    } catch(\Exception $e) {
      DB::rollBack();
      throw $e;
    }
    return $examApi;
  }

  /**
   * @param MonthlyLog $monthlyLog
   * @param MonthlyLogDetail $monthlyLogDetail
   * @param Request $request
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
   * @throws \Throwable
   */
  public function destroy(MonthlyLog $monthlyLog, MonthlyLogDetail $monthlyLogDetail, Request $request)
  {
    if (!$monthlyLogDetail->can_delete) {
      return response([
        'errors' => [
          'created_by' => [
            '他の人が投稿した指摘内容は削除できません'
          ]
        ]
      ], 422);
    }
    DB::beginTransaction();
    try {
      $examApi = $this->monthlyLogDetailService->delete($monthlyLog, $monthlyLogDetail);
      DB::commit();
    } catch(\Exception $e) {
      DB::rollBack();
      throw $e;
    }
    return redirect()->route('monthlyLogDetail.index', ['monthlyLog' => $monthlyLog]);
  }

  public function worry(MonthlyLog $monthlyLog, MonthlyLogDetail $monthlyLogDetail)
  {
    DB::beginTransaction();
    try {
      $this->monthlyLogDetailService->worry($monthlyLogDetail);
      DB::commit();
    } catch(\Exception $e) {
      DB::rollBack();
      throw $e;
    }
    return response($monthlyLogDetail, 200);
  }

  public function unworry(MonthlyLog $monthlyLog, MonthlyLogDetail $monthlyLogDetail)
  {
    DB::beginTransaction();
    try {
      $this->monthlyLogDetailService->unworry($monthlyLogDetail);
      DB::commit();
    } catch(\Exception $e) {
      DB::rollBack();
      throw $e;
    }
    return response($monthlyLogDetail, 204);
  }
}
