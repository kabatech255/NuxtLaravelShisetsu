<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HandledByAdmin;
use Illuminate\Support\Str;

/**
 * App\Models\Employee
 *
 * @property int $id
 * @property int $employee_id 社員ID
 * @property string $name
 * @property string|null $file_name
 * @property int|null $created_by 作成者のemployee_id
 * @property int|null $updated_by 作成者のemployee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Admin|null $admin
 * @property-read mixed $department_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Schedule[] $schedules
 * @property-read int|null $schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Schedule[] $sharedSchedules
 * @property-read int|null $shared_schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Todo[] $todos
 * @property-read int|null $todos_count
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newQuery()
 * @method static \Illuminate\Database\Query\Builder|Employee onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|Employee withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Employee withoutTrashed()
 * @mixin \Eloquent
 */
class Employee extends Model
{
  use SoftDeletes;
  use HandledByAdmin;
  const RELATIONS_ARRAY = ['admin'];

  protected $table = 'employees';
  protected $primaryKey = 'employee_id';
  public $incrementing = false;

  protected $fillable = [
    'employee_id',
    'name',
    'file_name',
    'created_by'
  ];

  protected $hidden = [
    'created_by',
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function admin()
  {
    return $this->hasOne(Admin::class, 'login_id', 'employee_id');
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
}
