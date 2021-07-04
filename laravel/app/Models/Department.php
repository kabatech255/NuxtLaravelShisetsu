<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * App\Models\Department
 *
 * @property int $id
 * @property int $department_code 1:検査員 2:店舗トレーナー 3:店舗
 * @property string $name
 * @property string|null $file_name
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $authors
 * @property-read int|null $authors_count
 * @method static \Illuminate\Database\Eloquent\Builder|Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Department query()
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereDepartmentCode($value)
 * @property int|null $updated_by 作成者のemployee_id
 * @method static \Illuminate\Database\Query\Builder|Department onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|Department withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Department withoutTrashed()
 */
class Department extends Model
{
  use SoftDeletes;

  protected $table = 'departments';
  protected $primaryKey = 'department_code';
  protected $fillable = [ 'department_code', 'name', 'file_name' ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function authors()
  {
    return $this->hasMany(User::class, 'department_code', 'department_code');
  }
}
