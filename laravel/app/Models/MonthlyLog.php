<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
// interface
use App\Models\Interfaces\CanDeleteRelationInterface;
// traits
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HandledByUser;
use App\Models\Traits\HandledByAdmin;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MonthlyLog
 *
 * @property int $id
 * @property int $store_code
 * @property int $exam_code
 * @property int $examined_by
 * @property int $examined_year
 * @property int $examined_month
 * @property string $examined_at
 * @property string|null $review
 * @property int|null $total
 * @property int $is_complete
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog whereExaminedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog whereExaminedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog whereExaminedMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog whereExaminedYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog whereIsComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog whereExamCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyLog whereStoreCode($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MonthlyLogDetail[] $logs
 * @property-read int|null $logs_count
 * @method static \Illuminate\Database\Query\Builder|MonthlyLog onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|MonthlyLog withTrashed()
 * @method static \Illuminate\Database\Query\Builder|MonthlyLog withoutTrashed()
 * @property-read \App\Models\Examinator $examinator
 * @property-read string $year_month
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MonthlyLogDetail[] $monthlyLogDetails
 * @property-read int|null $monthly_log_details_count
 * @property-read mixed $can_delete_details
 * @property-read \App\Models\Exam|null $exam
 * @property-read int $specific_code
 * @property-read int $year_month_number
 * @property-read \App\Models\Shop|null $shop
 * @property-read int $date_number
 */
class MonthlyLog extends Model implements CanDeleteRelationInterface
{
  use HandledByAdmin;
  use HandledByUser;
  use SoftDeletes;

  const RELATIONS_ARRAY = ['examinator', 'monthlyLogDetails'];

  protected $table = 'monthly_logs';

  protected $fillable = [
    'examined_year',
    'examined_month',
    'store_code',
    'exam_code',
    'examined_by',
    'examined_at',
    'review',
    'total',
    'is_complete',
  ];

  protected $dates = [
    'examined_at'
  ];

  protected $appends = [
    'year_month',
    'year_month_number',
    'date_number',
    'specific_code',
  ];
  protected $hidden = ['examined_by'];

  /**
   * MonthlyLog constructor.
   * @param array $attributes
   */
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    if (!(Arr::get($this->attributes,'examined_at') && Arr::get($this->attributes,'examined_by'))) {
      $this->setExaminedAt();
      $this->setExaminedBy();
    }
  }

  /**
   * @return string
   */
  public function getYearMonthAttribute(): string
  {
    return "{$this->examined_year}-{$this->examined_month}";
  }
  /**
   * @return int
   */
  public function getYearMonthNumberAttribute(): int
  {
    return $this->examined_year . sprintf('%02d', $this->examined_month);
  }

  /**
   * @return int
   */
  public function getDateNumberAttribute(): int
  {
    $day = Carbon::parse($this->examined_at)->day;
    return $this->examined_year . sprintf('%02d', $this->examined_month) . $day;
  }

  /**
   * @return int
   */
  public function getSpecificCodeAttribute(): int
  {
    return $this->examined_year . sprintf('%02d', $this->examined_month) . sprintf('%02d', $this->exam_code);
  }

  public function setExaminedAt(): void
  {
    $this->attributes['examined_at'] = now();
  }

  public function setExaminedBy(): void
  {
    if (Auth::check()) {
      $this->attributes['examined_by'] = Auth::user()->login_id;
    }
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function monthlyLogDetails()
  {
    return $this->hasMany(MonthlyLogDetail::class, 'monthly_log_id', 'id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function examinator()
  {
    return $this->belongsTo(Examinator::class, 'examined_by', 'employee_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function exam()
  {
    return $this->hasOne(Exam::class, 'exam_code', 'exam_code');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function shop()
  {
    return $this->hasOne(Shop::class, 'store_code', 'store_code');
  }

  /**
   * @return array
   */
  public function getDeleteRelations(): array
  {
    return [$this->monthlyLogDetails];
  }
}
