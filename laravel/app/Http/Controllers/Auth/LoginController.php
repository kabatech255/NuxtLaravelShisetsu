<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ExaminatorService;
use App\Models\Examinator;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = RouteServiceProvider::HOME;

  protected $examinatorService;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(ExaminatorService $examinatorService)
  {
    $this->middleware('guest')->except('logout');
    $this->examinatorService = $examinatorService;
  }

  public function username()
  {
    return 'login_id';
  }

  protected function loggedOut(Request $request)
  {
    Auth::logout();
    $request->session()->flush();
    return response()->json();
  }

  /**
   * @param Request $request
   * @param $user
   * @return mixed
   */
  protected function authenticated(Request $request, $user)
  {
    return $this->examinatorService->findByCode(Examinator::RELATIONS_ARRAY, $user->login_id);
  }

  /**
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
   * @throws \Illuminate\Validation\ValidationException
   */
  public function testLogin(Request $request)
  {
    return $this->login($request);
  }
}
