<?php

namespace App\Http\Requests\Schedule;

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
      'body' => 'required|string|max:50',
      'color' => 'required|string|max:30',
      'start' => 'required|string',
      'end' => 'required|string',
      'is_private' => 'required|boolean',
      'shared_members' => 'nullable|string',
      'editable_members' => 'nullable|string',
    ];
  }

  public function attributes()
  {
    return [
      'body' => '予定',
      'color' => 'カラー',
      'start' => '予定開始日',
      'end' => '予定終了日',
      'is_private' => '非公開設定',
      'shared_members' => '共有相手',
      'editable_members' => '編集権限者',
    ];
  }
}
