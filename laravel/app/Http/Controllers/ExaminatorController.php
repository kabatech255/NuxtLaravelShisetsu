<?php

namespace App\Http\Controllers;

use App\Rules\CurrentPasswordRule;
use App\Rules\ShouldFixRule;
use App\Rules\TestDataRule;
use App\Rules\TestPasswordRule;
use Illuminate\Http\Request;
use App\Http\Requests\Examinator\UpdateProfileRequest;
use App\Services\ExaminatorService;
use Illuminate\Support\Facades\Auth;
use App\Models\Examinator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ExaminatorController extends Controller
{
  protected $examinatorService;

  public function __construct(ExaminatorService $examinatorService)
  {
    $this->examinatorService = $examinatorService;
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
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param UpdateProfileRequest $request
   * @param Examinator $examinator
   * @return \Illuminate\Http\Response
   */
  public function updateProfile(UpdateProfileRequest $request, Examinator $examinator)
  {
    $request->validate([
      'current_password' => [new CurrentPasswordRule()],
      'password' => [new ShouldFixRule($examinator->employee_id)]
    ]);
    DB::beginTransaction();
    try {
      $this->examinatorService->updateProfile($request->all(), $examinator);
      DB::commit();
    }catch(\Exception $e) {
      DB::rollBack();
      throw $e;
    }
    return response($this->examinatorService->findByCode(Examinator::RELATIONS_ARRAY, $examinator->employee_id), 200);
  }

  public function scheduleMemberList()
  {
    $members = $this->examinatorService->scheduleMemberList();
    return response($members, 200);
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

  public function currentAuthor()
  {
    return Auth::check() ? $this->examinatorService->findByCode(Examinator::RELATIONS_ARRAY, Auth::user()->login_id) : '';
  }
}
