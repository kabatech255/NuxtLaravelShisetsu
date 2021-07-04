<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ExceptOwnerRule implements Rule
{
  protected $createdBy;
  /**
   * Create a new rule instance.
   *
   * @return void
   */
  public function __construct(int $createdBy)
  {
    $this->createdBy = $createdBy;
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
    return $this->createdBy === Auth::user()->examinator->employee_id;
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    return trans('validation.except_owner');
  }
}
