<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * App\Models\Summary
 *
 * @property int $id
 * @property int $year
 * @property int $month
 * @property int $exam_code
 * @property string $description
 * @property int $written_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int $specific_code
 * @property-read string $year_month
 * @property-read int $year_month_number
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MonthlyLog[] $monthlyLogs
 * @property-read int|null $monthly_logs_count
 * @method static \Illuminate\Database\Eloquent\Builder|Summary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Summary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Summary query()
 * @method static \Illuminate\Database\Eloquent\Builder|Summary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Summary whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Summary whereExamCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Summary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Summary whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Summary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Summary whereWrittenBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Summary whereYear($value)
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Examinator|null $writtenBy
 * @method static \Illuminate\Database\Query\Builder|Summary onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Summary whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Summary withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Summary withoutTrashed()
 */
class Summary extends Model
{
  use SoftDeletes;
  protected $table = 'summaries';
  protected $fillable = ['year', 'month', 'exam_code', 'description', 'written_by'];
  protected $appends = ['specific_code'];

  const RELATIONS_ARRAY = ['writtenBy'];
  /**
   * @return string
   */
  public function getYearMonthAttribute(): string
  {
    return "{$this->year}-{$this->month}";
  }

  /*
   * @return int
   */
  public function getYearMonthNumberAttribute(): int
  {
    return $this->year . sprintf('%02d', $this->month);
  }

  /**
   * @return int
   */
  public function getSpecificCodeAttribute(): int
  {
    return $this->year . sprintf('%02d', $this->month) . sprintf('%02d', $this->exam_code);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function writtenBy()
  {
    return $this->hasOne(Examinator::class, 'employee_id', 'written_by');
  }
}
