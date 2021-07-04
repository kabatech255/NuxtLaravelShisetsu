<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// interface
use App\Models\Interfaces\CanDeleteRelationInterface;
// traits
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HandledByUser;
use App\Models\Traits\HandledByAdmin;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static Builder|\App\Models\User newModelQuery()
 * @method static Builder|\App\Models\User newQuery()
 * @method static Builder|\App\Models\User query()
 * @method static Builder|\App\Models\User whereCreatedAt($value)
 * @method static Builder|\App\Models\User whereEmail($value)
 * @method static Builder|\App\Models\User whereEmailVerifiedAt($value)
 * @method static Builder|\App\Models\User whereId($value)
 * @method static Builder|\App\Models\User whereName($value)
 * @method static Builder|\App\Models\User wherePassword($value)
 * @method static Builder|\App\Models\User whereRememberToken($value)
 * @method static Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $department_code
 * @property int $login_id
 * @property string|null $deleted_at
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereDepartmentId($value)
 * @method static Builder|User whereLoginId($value)
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @method static Builder|User whereDepartmentCode($value)
 * @property-read \App\Models\Examinator|null $authInfo
 * @property-read \App\Models\Department|null $department
 * @property-read \App\Models\Examinator|null $examinator
 */
class User extends Authenticatable implements CanDeleteRelationInterface
{
  use Notifiable;
  use HandledByUser;
  use SoftDeletes;

  protected $table = 'users';

  const EXAMINATOR_SECTION = 1;
  const TRAINER_SECTION = 2;
  const STORE_SECTION = 3;
  const SECTIONS_TABLE_ARRAY = [
    self::EXAMINATOR_SECTION => 'examinators',
    self::TRAINER_SECTION => 'trainers',
    self::STORE_SECTION => 'shops',
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'login_id',
    'email',
    'password',
  ];

  const RELATIONS_ARRAY = ['authInfo'];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
    'email_verified_at',
    'id',
    'created_at',
    'updated_at',
  ];

  /**
   * @var string[]
   */
  protected $appends = [
    'department_name',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  protected $columnStyle = [
    'department_code' => 'width: 80px; text-align: center',
    'department_name' => 'width: 100px; text-align: center',
    'login_id' => 'width: 180px; text-align: center',
    'email' => 'width: 230px; text-align: center',
    'deleted_at' => 'width: 230px; text-align: center',
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function examinator()
  {
    return $this->hasOne(Examinator::class, 'employee_id', 'login_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function department()
  {
    return $this->belongsTo(Department::class, 'department_code', 'department_code');
  }

  public function getDepartmentNameAttribute()
  {
    return $this->department->name;
  }

  /**
   * 削除
   */
  public function getDeleteRelations()
  {
    return [$this->examinator];
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


}
