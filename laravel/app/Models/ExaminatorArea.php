<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ExaminatorArea
 *
 * @property int $id
 * @property string $name エリア名:西日本：東日本
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ExaminatorTeam[] $teams
 * @property-read int|null $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorArea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorArea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorArea query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorArea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorArea whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorArea whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorArea whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorArea whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $updated_by 作成者のemployee_id
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorArea whereUpdatedBy($value)
 */
class ExaminatorArea extends Model
{
  protected $table = 'examinator_areas';
  protected $fillable = ['name', 'created_by'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function teams()
  {
    return $this->hasMany(ExaminatorTeam::class, 'examinator_area_id', 'id');
  }
}
