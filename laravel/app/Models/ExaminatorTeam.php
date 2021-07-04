<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ExaminatorTeam
 *
 * @property int $id
 * @property int $team_code チームコード
 * @property string $name チーム名
 * @property int $examinator_area_id エリアID
 * @property int $created_by 作成者のemployee_id
 * @property int $updated_by 更新者のemployee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\ExaminatorArea $area
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Examinator[] $examinators
 * @property-read int|null $examinators_count
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorTeam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorTeam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorTeam query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorTeam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorTeam whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorTeam whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorTeam whereExaminatorAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorTeam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorTeam whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorTeam whereTeamCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorTeam whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminatorTeam whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class ExaminatorTeam extends Model
{
  protected $table = 'examinator_teams';
  protected $primaryKey = 'team_code';

  protected $fillable = [
    'team_code',
    'name',
    'examinator_area_id',
    'created_by',
  ];

  const EXAMINATOR_PER_TEAM = 3;
  const AVERAGE_PER_EXAMINATOR = 15;
  const STORE_PER_BRANCH = self::EXAMINATOR_PER_TEAM * self::AVERAGE_PER_EXAMINATOR;

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function area()
  {
    return $this->belongsTo(ExaminatorArea::class, 'area_id', 'id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function examinators()
  {
    return $this->hasMany(Examinator::class, 'team_code', 'team_code');
  }
}
