<?php

namespace App\Http\Requests\MonthlyLog;

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
      'examined_year' => 'required|integer|min:2020',
      'examined_month' => 'required|integer|max:12',
      'store_code' => 'required|integer',
      'exam_code' => 'nullable|integer',
    ];
  }
}
