<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HandledByUser;
use App\Models\Traits\HandledByAdmin;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\MonthlyLogDetail
 *
 * @property int $id
 * @property int $monthly_log_id
 * @property int $exam_issue_detail_id 指摘内容ID
 * @property string|null $primary_file_name 違反画像1
 * @property string|null $secondary_file_name 違反画像予備(原則1枚)
 * @property string|null $improved_file_name 改善画像
 * @property int $is_improved 改善フラグ
 * @property string|null $note 備考欄
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Examinator|null $creator
 * @property-read \App\Models\ExamIssueDetail $examIssueDetail
 * @property-read \App\Models\ExamIssue $belongs_to_issue
 * @property-read mixed $body
 * @property-read bool $can_delete
 * @property-read bool $is_worried
 * @property-read int $issue_id
 * @property-read int $worries_count
 * @property-read \App\Models\MonthlyLog $monthlyLog
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Examinator[] $worriedMembers
 * @property-read int|null $worried_members_count
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLogDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLogDetail newQuery()
 * @method static \Illuminate\Database\Query\Builder|MonthlyLogDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLogDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLogDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLogDetail whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLogDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLogDetail whereExamIssueDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLogDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLogDetail whereImprovedFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLogDetail whereIsImproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLogDetail whereMonthlyLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLogDetail whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLogDetail wherePrimaryFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLogDetail whereSecondaryFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLogDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|MonthlyLogDetail withTrashed()
 * @method static \Illuminate\Database\Query\Builder|MonthlyLogDetail withoutTrashed()
 * @mixin \Eloquent
 * @property-read mixed $improved_file_path
 * @property-read mixed $primary_file_path
 * @property-read mixed $secondary_file_path
 */
class MonthlyLogDetail extends Model
{
  use HandledByAdmin;
  use HandledByUser;
  use SoftDeletes;

  protected $table = 'monthly_log_details';

  const RELATIONS_ARRAY = ['creator'];

  protected $hidden = [
    'deleted_at',
    'updated_at',
  ];
  protected $appends = [
    'body',
    'belongs_to_issue',
    'issue_id',
    'can_delete',
    'worries_count',
    'is_worried',
    'primary_file_path',
    'secondary_file_path',
    'improved_file_path',
  ];
  protected $fillable = [
    'monthly_log_id',
    'exam_issue_detail_id',
    'is_improved',
    'primary_file_name',
    'secondary_file_name',
    'improved_file_name',
    'note',
    'created_by'
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    if (!Arr::get($attributes, 'created_by')) {
      $this->setCreatedBy();
    }
  }

  /**
   * @param string $attrName
   */
  public function setFileName(string $attrName): void
  {
    $this->attributes[$attrName] = $this->getFileName();
  }

  protected function setCreatedBy(): void
  {
    if (Auth::check()) {
      $this->attributes['created_by'] = Auth::user()->examinator->employee_id;
    } else {
      $this->attributes['created_by'] = null;
    }
  }

  /**
   * @return string
   */
  public function getFileName(): string
  {
    return Str::random(16);
  }
  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function examIssueDetail()
  {
    return $this->belongsTo(ExamIssueDetail::class, 'exam_issue_detail_id', 'id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function monthlyLog()
  {
    return $this->belongsTo(MonthlyLog::class, 'monthly_log_id', 'id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function creator()
  {
    return $this->belongsTo(Examinator::class, 'created_by', 'employee_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function worriedMembers()
  {
    return $this->belongsToMany(Examinator::class, Worry::class, 'monthly_log_detail_id', 'employee_id')->withTimestamps()->withPivot('created_at');
  }

  /**
   * @return mixed
   */
  public function getBodyAttribute()
  {
    return $this->examIssueDetail->issue_content;
  }

  /**
   * @return ExamIssue
   */
  public function getBelongsToIssueAttribute()
  {
    return $this->examIssueDetail->examIssue;
  }

  public function getPrimaryFilePathAttribute()
  {
    return $this->primary_file_name === '' ? '' : Storage::cloud()->url($this->primary_file_name);
  }

  public function getSecondaryFilePathAttribute()
  {
    return $this->secondary_file_name === null ? '' : Storage::cloud()->url($this->secondary_file_name);
  }

  public function getImprovedFilePathAttribute()
  {
    return $this->improved_file_name === null ? '' : Storage::cloud()->url($this->improved_file_name);
  }

  /**
   * @return int
   */
  public function getIssueIdAttribute()
  {
    return $this->examIssueDetail->examIssue->id;
  }

  /**
   * @return int
   */
  public function getWorriesCountAttribute()
  {
    return $this->worriedMembers->count();
  }

  /**
   * @return bool
   */
  public function getIsWorriedAttribute()
  {
    return Auth::check() ? $this->worriedMembers->contains(function($member){
      return $member->employee_id === Auth::user()->login_id;
    }) : false;
  }

  /**
   * @return bool
   */
  public function getCanDeleteAttribute()
  {
    return Auth::check() ? $this->created_by === Auth::user()->login_id && !$this->monthlyLog->is_complete : false;
  }


}
