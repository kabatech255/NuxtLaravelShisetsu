<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Schedule
 *
 * @property int $id
 * @property string $body 予定の内容
 * @property string $color カレンダーに表示させる色
 * @property int $created_by 予定の作成者
 * @property string $start
 * @property string $end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Examinator $examinator
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule newQuery()
 * @method static \Illuminate\Database\Query\Builder|Schedule onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Schedule withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Schedule withoutTrashed()
 * @mixin \Eloquent
 * @property int|null $is_private
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Examinator[] $sharedMembers
 * @property-read int|null $shared_members_count
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereIsPrivate($value)
 * @property-read mixed $can_edit
 */
class Schedule extends Model
{
  use SoftDeletes;

  protected $table = 'schedules';
  protected $fillable = ['body', 'color', 'start', 'end', 'created_by', 'is_private'];
  protected $appends = ['can_edit'];
  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function examinator()
  {
    return $this->belongsTo(Examinator::class, 'created_by', 'employee_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function sharedMembers()
  {
    return $this->belongsToMany(Examinator::class, ScheduleShare::class, 'schedule_id', 'employee_id')->withTimestamps()->withPivot('edit_permission');
  }

  public function getCanEditAttribute()
  {
    if (Auth::guest()) {
      return 0;
    }
    return $this->sharedMembers->filter(function($member){
      return $member->employee_id === Auth::user()->examinator->employee_id;
    })->first()->pivot->edit_permission;
  }


}
