<?php

namespace App\Http\Requests\MonthlyLogDetail;

use App\Rules\AfterCompleteRule;
use Illuminate\Foundation\Http\FormRequest;
class StoreRequest extends FormRequest
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
    return [
      'exam_issue_detail_id' => 'required|integer',
      'is_improved' => 'nullable|boolean',
      'note' => 'nullable|string|max:80',
      'primary_file' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:'.self::MAX_FILE_SIZE,
      'secondary_file' => 'nullable|max:'.self::MAX_FILE_SIZE,
      'improved_file' => 'nullable|max:'.self::MAX_FILE_SIZE,
    ];
  }

  public function attributes()
  {
    return [
      'exam_issue_detail_id' => '指摘項目',
      'is_improved' => '改善フラグ',
      'note' => '備考欄',
      'primary_file' => '指摘画像1',
      'secondary_file' => '指摘画像2',
      'improved_file' => '改善画像',
    ];
  }

  public function messages()
  {
    return [
      'primary_file.max' => ':attributeが' . self::MULTIPLE_NUM . 'MBを超えています。',
      'secondary_file.max' => ':attributeが' . self::MULTIPLE_NUM . 'MBを超えています。',
      'improved_file.max' => ':attributeが' . self::MULTIPLE_NUM . 'MBを超えています。',
    ];
  }
}
