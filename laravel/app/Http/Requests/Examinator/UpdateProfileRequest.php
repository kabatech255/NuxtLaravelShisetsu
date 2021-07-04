<?php

namespace App\Http\Requests\Examinator;

use App\Rules\CurrentPasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
  const MULTIPLE_NUM = 3;
  const MAX_FILE_SIZE = 1024 * self::MULTIPLE_NUM; // 3M

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $currentPassRule = new CurrentPasswordRule();
    return [
      'file' => 'nullable|max:' . self::MAX_FILE_SIZE,
      'password' => 'nullable|string|min:8|confirmed|different:current-password',
      'current_password' => 'required_with:password|string|min:8'
    ];
  }

  public function attributes()
  {
    return [
      'file' => '顔写真',
      'password' => '新しいパスワード',
      'current_password' => '現在のパスワード',
    ];
  }

  public function messages()
  {
    return [
      'file.max' => ':attributeが' . self::MULTIPLE_NUM . 'MBを超えています。',
    ];
  }
}
