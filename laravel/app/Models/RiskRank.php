<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RiskRank
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Exam[] $exams
 * @property-read int|null $exams_count
 * @method static \Illuminate\Database\Eloquent\Builder|RiskRank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RiskRank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RiskRank query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $rank_id
 * @property string $rank
 * @property int $point
 * @property int|null $created_by 作成者のemployee_id
 * @property int|null $updated_by 更新者のemployee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|RiskRank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiskRank whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiskRank whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiskRank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiskRank wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiskRank whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiskRank whereRankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiskRank whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiskRank whereUpdatedBy($value)
 */
class RiskRank extends Model
{
  protected $table = 'risk_ranks';

  protected $fillable = [ 'rank_id', 'rank', 'point', 'created_by' ];

  const RANK_IDS = [
    'rankS' => 1,
    'rankA' => 2,
    'rankB' => 3,
    'rankC' => 4,
    'rankD' => 5,
  ];

  const RANK_POINTS = [
    self::RANK_IDS['rankS'] => 10,
    self::RANK_IDS['rankA'] => 5,
    self::RANK_IDS['rankB'] => 3,
    self::RANK_IDS['rankC'] => 1,
    self::RANK_IDS['rankD'] => 0,
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function exams()
  {
    return $this->hasMany(Exam::class, 'risk_rank_id', 'rank_id');
  }

}
