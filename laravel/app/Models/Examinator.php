<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HandledByUser;
use App\Models\Traits\HandledByAdmin;
use Illuminate\Support\Str;

/**
 * App\Models\Examinator
 *
 * @property int $id
 * @property int $employee_id 社員ID
 * @property int|null $team_code
 * @property string $name
 * @property string|null $file_name
 * @property int|null $created_by 作成者のemployee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\ExaminatorTeam|null $team
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MonthlyLogDetail[] $worries
 * @property-read int|null $worries_count
 * @method static \Illuminate\Database\Eloquent\Builder|Examinator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Examinator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Examinator query()
 * @method static \Illuminate\Database\Eloquent\Builder|Examinator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Examinator whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Examinator whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Examinator whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Examinator whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Examinator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Examinator whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Examinator whereTeamCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Examinator whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $updated_by 作成者のemployee_id
 * @property-read mixed $department_name
 * @method static \Illuminate\Database\Query\Builder|Examinator onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Examinator whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|Examinator withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Examinator withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MonthlyLog[] $monthlyLogs
 * @property-read int|null $monthly_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Todo[] $todos
 * @property-read int|null $todos_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Schedule[] $schedules
 * @property-read int|null $schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Schedule[] $sharedSchedules
 * @property-read int|null $shared_schedules_count
 */
class Examinator extends Model
{
  use SoftDeletes;
  use HandledByUser;
  use HandledByAdmin;
  const RELATIONS_ARRAY = ['user'];

  protected $table = 'examinators';
  protected $primaryKey = 'employee_id';

  protected $fillable = [
    'employee_id',
    'name',
    'file_name',
    'team_code',
    'created_by'
  ];

  protected $appends = ['department_name'];

  protected $hidden = [
    'created_by',
    'created_at',
    'updated_at',
  ];

  protected $columnStyle = [
    'department_name' => 'width: 100px; text-align: center',
    'employee_id' => 'width: 180px; text-align: center',
    'user.email' => 'width: 230px; text-align: center',
    'name' => 'width: 230px; text-align: center',
    'file_name' => 'width: 230px; text-align: center',
  ];

  protected $masterHeader = [
    'department_name',
    'employee_id',
    'name',
    'file_name',
    'user.email',
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function user()
  {
    return $this->hasOne(User::class, 'login_id', 'employee_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function team()
  {
    return $this->belongsTo(ExaminatorTeam::class, 'team_code', 'id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function worries()
  {
    return $this->belongsToMany(MonthlyLogDetail::class, Worry::class, 'employee_id', 'monthly_log_detail_id')->withPivot('created_at');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function monthlyLogs()
  {
    return $this->hasMany(MonthlyLog::class, 'examined_by', 'employee_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function todos()
  {
    return $this->hasMany(Todo::class, 'employee_id', 'employee_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function schedules()
  {
    return $this->hasmany(Schedule::class, 'created_by', 'employee_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function sharedSchedules()
  {
    return $this->belongsToMany(Schedule::class, ScheduleShare::class, 'employee_id', 'schedule_id')->withTimestamps()->withPivot('edit_permission');
  }

  /**
   * @return mixed
   */
  public function getDepartmentNameAttribute()
  {
    return $this->user->department->name;
  }

  public static function getFileName(): string
  {
    return Str::random(16);
  }

  /**
   * @return mixed
   */
  public function getColumnStyle()
  {
    return $this->columnStyle;
  }

  /**
   * @return string[]
   */
  public function getAppends(): array
  {
    return $this->appends;
  }

  /**
   * @return string[]
   */
  public function getMasterHeader()
  {
    return $this->masterHeader;
  }
}
