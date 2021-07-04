<?php

namespace App\Http\Middleware;

use Closure;

class HandleTestUser
{
  /**
   * Handle an incoming request.
   *
   * @param \Illuminate\Http\Request $request
   * @param \Closure $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $request->merge([
      'login_id' => config('app.test_id')
    ]);
    $request->merge([
      'password' => config('app.test_pass')
    ]);
    return $next($request);
  }
}
