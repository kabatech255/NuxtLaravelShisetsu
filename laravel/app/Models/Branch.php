<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Branch
 *
 * @property int $id
 * @property int $branch_code
 * @property string $name 支社名
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Shop[] $shops
 * @property-read int|null $shops_count
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch query()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereBranchCode($value)
 * @property int|null $updated_by 作成者のemployee_id
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereUpdatedBy($value)
 */
class Branch extends Model
{
  protected $table = 'branches';
  protected $fillable = [ 'branch_code', 'name', 'created_by' ];

  const BRANCH_CODE_OKINAWA = 1;
  const BRANCH_CODE_KYUSHU = 2;
  const BRANCH_CODE_CHUSHIKOKU = 3;
  const BRANCH_CODE_KANSAI = 4;
  const BRANCH_CODE_TOKAI = 5;
  const BRANCH_CODE_HOKURIKU = 6;
  const BRANCH_CODE_KITAKANTO = 7;
  const BRANCH_CODE_MINAMIKANTO = 8;
  const BRANCH_CODE_TOHOKU = 9;
  const BRANCH_CODE_HOKKAIDO = 10;

  const SHOP_COUNT_ARRAY = [
    self::BRANCH_CODE_OKINAWA => 3,
    self::BRANCH_CODE_KYUSHU => 4,
    self::BRANCH_CODE_CHUSHIKOKU => 4,
    self::BRANCH_CODE_KANSAI => 5,
    self::BRANCH_CODE_TOKAI => 3,
    self::BRANCH_CODE_HOKURIKU => 3,
    self::BRANCH_CODE_KITAKANTO => 3,
    self::BRANCH_CODE_MINAMIKANTO => 4,
    self::BRANCH_CODE_TOHOKU => 3,
    self::BRANCH_CODE_HOKKAIDO => 3,
  ];

  protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'id'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function shops()
  {
    return $this->hasMany(Shop::class, 'branch_code', 'branch_code');
  }
}
