<?php

namespace App\Http\Requests\MonthlyLogDetail;

class UpdateRequest extends StoreRequest
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
      'exam_issue_detail_id' => 'nullable|integer',
      'is_improved' => 'nullable|boolean',
      'note' => 'nullable|string|max:80',
      'primary_file' => 'nullable|max:'.self::MAX_FILE_SIZE,
      'secondary_file' => 'nullable|max:'.self::MAX_FILE_SIZE,
      'improved_file' => 'nullable|max:'.self::MAX_FILE_SIZE,
    ];
  }

  public function attributes()
  {
    return parent::attributes();
  }

  public function messages()
  {
    return parent::messages();
  }
}
