<?php

namespace App\Http\Requests\Todo;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
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
    return [
      'body' => 'required|string|max:30'
    ];
  }

  public function attributes()
  {
    return [
      'body' => 'タスク',
    ];
  }

  public function messages()
  {
    return [
      'body|max' => 'タスクは最大30文字までです'
    ];
  }
}
