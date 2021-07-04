<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Equipment
 *
 * @property int $id
 * @property string $name 「消火器」「非常扉」「温度管理表」など
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $updated_by 作成者のemployee_id
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereUpdatedBy($value)
 */
class Equipment extends Model
{
  protected $table = 'equipment';
  protected $fillable = ['name', 'created_by'];
}
