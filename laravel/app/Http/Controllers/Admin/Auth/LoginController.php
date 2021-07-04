<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\EmployeeService;
use App\Models\Employee;

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

  protected $employeeService;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(EmployeeService $employeeService)
  {
    $this->middleware('guest:admin')->except('logout');
    $this->employeeService = $employeeService;
  }

  /**
   * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
   */
  protected function guard()
  {
    return Auth::guard('admin');
  }

  public function username()
  {
    return 'login_id';
  }

  protected function loggedOut(Request $request)
  {
   $this->guard()->logout();
    $request->session()->regenerate();
    return response()->json();
  }

  /**
   * @param Request $request
   * @param $admin
   * @return mixed
   */
  protected function authenticated(Request $request, $admin)
  {
    return $this->employeeService->findByCode(Employee::RELATIONS_ARRAY, $admin->login_id);
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
