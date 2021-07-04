<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Worry
 *
 * @property int $id
 * @property int $monthly_log_detail_id
 * @property int $employee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Worry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Worry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Worry query()
 * @method static \Illuminate\Database\Eloquent\Builder|Worry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worry whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worry whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worry whereMonthlyLogDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worry whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Worry extends Model
{
  protected $table = 'worries';
  protected $fillable = ['employee_id', 'monthly_log_detail_id'];
}
