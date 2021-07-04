<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Interfaces\CanDeleteRelationInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HandledByUser;
use App\Models\Traits\HandledByAdmin;
/**
 * App\Models\ExamIssue
 *
 * @property int $id
 * @property int $exam_code 検査ID
 * @property string $name 設問: 「最重要項目」
 * @property string|null $judgement_base 判定基準
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Exam $exam
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssue query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssue whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssue whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssue whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssue whereJudgementBase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssue whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssue whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ExamIssue whereExamCode($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ExamIssueDetail[] $examIssueDetails
 * @property-read int|null $exam_issue_details_count
 * @method static \Illuminate\Database\Query\Builder|ExamIssue onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|ExamIssue withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ExamIssue withoutTrashed()
 */
class ExamIssue extends Model implements CanDeleteRelationInterface
{
  use HandledByUser;
  use HandledByAdmin;
  use SoftDeletes;

  const RELATIONS_ARRAY = ['examIssueDetails'];

  protected $table = 'exam_issues';

  protected $fillable = [
    'exam_code',
    'name',
    'judgement_base',
    'created_by',
  ];


  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function exam()
  {
    return $this->belongsTo(Exam::class, 'exam_code', 'exam_code');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function examIssueDetails()
  {
    return $this->hasMany(ExamIssueDetail::class, 'exam_issue_id', 'id');
  }

  public function getDeleteRelations()
  {
    return [$this->examIssueDetails];
  }
}
