<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CurrentPasswordRule implements Rule
{
  /**
   * Create a new rule instance.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  /**
   * Determine if the validation rule passes.
   *
   * @param string $attribute
   * @param mixed $value
   * @return bool
   */
  public function passes($attribute, $value)
  {
    $pass = Auth::user()->password;
    return (\Hash::check($value, $pass));
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    return trans('validation.current_pass');
  }
}
