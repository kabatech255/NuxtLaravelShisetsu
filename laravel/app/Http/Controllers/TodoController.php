<?php

namespace App\Http\Controllers;

use App\Services\TodoService;
use Illuminate\Http\Request;
use App\Models\Examinator;
use App\Models\Todo;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Todo\StoreRequest;

class TodoController extends Controller
{
  protected $todoService;

  public function __construct(TodoService $todoService)
  {
    $this->todoService = $todoService;
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
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
   * @param Examinator $examinator
   * @param StoreRequest $request
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   * @throws \Throwable
   */
  public function store(Examinator $examinator, StoreRequest $request)
  {
    if ($examinator->todos->count() > 29) {
      return response([
        'errors' => [
          'id' => [
            '登録できるタスクは30個までです'
          ]
        ]
      ], 422);
    }
    DB::beginTransaction();
    try {
      $response = $this->todoService->store($examinator, $request->all());
      DB::commit();
    } catch(\Exception $e) {
      DB::rollBack();
      throw $e;
    }

    return response($response, 201);
  }

  /**
   * @param Examinator $examinator
   * @param Todo $todo
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   * @throws \Throwable
   */
  public function done(Examinator $examinator, Todo $todo)
  {
    DB::beginTransaction();
    try {
      $response = $this->todoService->done($todo);
      DB::commit();
    } catch(\Exception $e) {
      DB::rollback();
      throw $e;
    }
    return response($response, 200);
  }

  /**
   * @param Examinator $examinator
   * @param Todo $todo
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   * @throws \Throwable
   */
  public function didnt(Examinator $examinator, Todo $todo)
  {
    DB::beginTransaction();
    try {
      $response = $this->todoService->didnt($todo);
      DB::commit();
    } catch(\Exception $e) {
      DB::rollback();
      throw $e;
    }
    return response($response, 200);
  }

  /**
   * @param Examinator $examinator
   * @param Todo $todo
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   * @throws \Throwable
   */
  public function destroy(Examinator $examinator, Todo $todo)
  {
    if ($examinator->employee_id !== $todo->employee_id) {
      return response([
        'errors' => [
          'created_by' => [
            '他人のタスクは削除できません'
          ]
        ]
      ], 422);
    }
    DB::beginTransaction();
    try {
      $response = $this->todoService->delete($todo);
      DB::commit();
    } catch(\Exception $e) {
      DB::rollBack();
      throw $e;
    }
    return response([], 204);
  }
}
