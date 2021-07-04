<?php

namespace App\Http\Controllers;

use _HumbugBox1d62d963ee7e\Nette\Neon\Exception;
use Illuminate\Http\Request;
use App\Http\Requests\MonthlyLog\StoreRequest;
use function React\Promise\all;
use Illuminate\Support\Facades\Auth;
use App\Services\MonthlyLogService;
use App\Services\ShopService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\Exam;
use App\Models\Shop;
use App\Models\MonthlyLog;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class MonthlyLogController extends Controller
{
  protected $monthlyLogService;
  protected $shopService;

  public function __construct(
    MonthlyLogService $monthlyLogService,
    ShopService $shopService
  )
  {
    $this->monthlyLogService = $monthlyLogService;
    $this->shopService = $shopService;
  }

  /**
   * @param int $storeCode
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   */
  public function index(int $storeCode)
  {
    try {
      $shop = $this->shopService->findByCode([], $storeCode);
      $examApi = $this->monthlyLogService->examApi($shop);
    } catch (ModelNotFoundException $e) {
      return response($e, 404);
    } catch (\Exception $e) {
      return response($e, $e->getStatusCode());
    }
    return response($examApi, 200);
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
   * @param StoreRequest $request
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   * @throws \Throwable
   */
  public function store(StoreRequest $request)
  {
    $recordArr = $request->all();
    DB::beginTransaction();
    try {
      $monthlyLog = $this->monthlyLogService->store($recordArr);
      DB::commit();
    } catch(\Exception $e) {
      DB::rollBack();
      throw $e;
    }
    return response($monthlyLog, 201);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  public function complete(MonthlyLog $monthlyLog)
  {
    DB::beginTransaction();
    try {
      $examApi = $this->monthlyLogService->complete($monthlyLog);
      DB::commit();
    }catch(\Exception $e) {
      DB::rollBack();
      throw $e;
    }
    return response($examApi, 200);

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
