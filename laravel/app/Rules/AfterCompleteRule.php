<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AfterCompleteRule implements Rule
{
  protected $isComplete;
  /**
   * Create a new rule instance.
   *
   * @return void
   */
  public function __construct($isComplete)
  {
    $this->isComplete = $isComplete;
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
    return !$this->isComplete;
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    return '検査完了後に指摘の追加はできません';
  }
}
