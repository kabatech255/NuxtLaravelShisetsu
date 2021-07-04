<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ExamIssueDetail
 *
 * @property int $id
 * @property int $exam_issue_id 設問ID
 * @property string $issue_content 指摘内容
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\ExamIssue $examIssue
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Equipment[] $selectableEquipment
 * @property-read int|null $selectable_equipment_count
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssueDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssueDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssueDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssueDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssueDetail whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssueDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssueDetail whereExamIssueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssueDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssueDetail whereIssueContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssueDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $updated_by 作成者のemployee_id
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssueDetail whereUpdatedBy($value)
 */
class ExamIssueDetail extends Model
{
  protected $table = 'exam_issue_details';

  protected $fillable = [
    'exam_issue_id',
    'issue_content',
    'created_by',
  ];

  const RELATIONS_ARRAY = ['examIssue'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function selectableEquipment()
  {
    return $this->belongsToMany(Equipment::class, SelectableEquipment::class, 'exam_issue_detail_id', 'equipment_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function examIssue()
  {
    return $this->belongsTo(ExamIssue::class, 'exam_issue_id', 'id');
  }
}
