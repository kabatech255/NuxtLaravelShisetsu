<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Register Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles the registration of new users as well as their
  | validation and creation. By default this controller uses a trait to
  | provide this functionality without requiring any additional code.
  |
  */

  use RegistersUsers;

  /**
   * Where to redirect users after registration.
   *
   * @var string
   */
  protected $redirectTo = RouteServiceProvider::HOME;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest:admin');
  }

  protected function guard()
  {
    return Auth::guard('admin');
  }

  /**
   * Get a validator for an incoming registration request.
   *
   * @param array $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data)
  {
    return Validator::make($data, [
      'login_id' => ['required', 'integer', Rule::unique('admins', 'email')->whereNull('deleted_at')],
      'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins', 'email')->whereNull('deleted_at')],
      'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);
  }

  /**
   * Create a new user instance after a valid registration.
   *
   * @param array $data
   * @return \App\Models\Admin
   */
  protected function create(array $data)
  {
    return Admin::create([
      'login_id' => $data['login_id'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
    ]);
  }

  /**
   * @param Request $request
   * @param Admin $admin
   * @return Admin
   */
  protected function registered(Request $request, $admin)
  {
    return $admin;
  }
}
