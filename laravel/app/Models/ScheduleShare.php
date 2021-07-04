<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ScheduleShare
 *
 * @property int $id
 * @property int $schedule_id スケジュールID
 * @property int $employee_id 共有者
 * @property int $edit_permission 編集権限
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleShare newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleShare newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleShare query()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleShare whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleShare whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleShare whereEditPermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleShare whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleShare whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleShare whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleShare whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ScheduleShare extends Model
{
  protected $table = 'schedule_shares';
  protected $fillable = ['employee_id', 'schedule_id', 'edit_permission'];
}
