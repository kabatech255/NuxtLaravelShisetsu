<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ShouldFixRule implements Rule
{
  protected $testUserId;
  /**
   * ShouldFixRule constructor.
   * @param int $byTestUser
   */
  public function __construct(int $byTestUser)
  {
    $this->testUserId = $byTestUser;
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
    return $this->testUserId !== (int)config('app.test_id');
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    return trans('validation.should_fix');
  }
}
