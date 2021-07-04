<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HandledByUser;
use App\Models\Traits\HandledByAdmin;
use Illuminate\Support\Carbon;

/**
 * App\Models\Shop
 *
 * @property int $id
 * @property int|null $branch_code 支社番号
 * @property int $store_code 店舗番号
 * @property string $name
 * @property string|null $file_name
 * @property int|null $created_by 作成者のemployee_id
 * @property int|null $updated_by 作成者のemployee_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int|null $team_code 担当チーム
 * @property-read \App\Models\ExaminatorTeam|null $belongsToTeam
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Examinator|null $createdUser
 * @property-read mixed $has_current_record
 * @property-read string $zerofill_code
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MonthlyLog[] $monthlyLogs
 * @property-read int|null $monthly_logs_count
 * @method static \Illuminate\Database\Eloquent\Builder|Shop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop newQuery()
 * @method static \Illuminate\Database\Query\Builder|Shop onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereBranchCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereStoreCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereTeamCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|Shop withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Shop withoutTrashed()
 * @mixin \Eloquent
 */
class Shop extends Model
{
  use HandledByUser;
  use HandledByAdmin;
  use SoftDeletes;

  const RELATIONS_ARRAY = [
    'branch',
    'createdUser',
    'monthlyLogs'
  ];

  protected $table = 'shops';

  protected $fillable = [
    'store_code',
    'branch_code',
    'team_code',
    'name',
    'file_name',
    'created_by',
  ];

  protected $hidden = [
    'id',
    'branch_code',
    'created_at',
    'updated_at',
    'deleted_at',
    'team_code',
  ];

  protected $appends = [
    'zerofill_code',
    'branch_name',
  ];

  protected $columnStyle = [
    'zerofill_code' => 'width: 140px; text-align: center',
    'store_code' => 'width: 100px; text-align: center',
    'file_name' => 'width: 230px; text-align: center',
    'name' => 'width: 230px; text-align: center',
    'created_by' => 'width: 180px; text-align: center',
    'updated_by' => 'width: 180px; text-align: center',
  ];

  protected $masterHeader = [
    'zerofill_code',
    'branch_name',
    'store_code',
    'name',
    'file_name',
    'created_by',
    'updated_by',
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function branch()
  {
    return $this->belongsTo(Branch::class, 'branch_code', 'branch_code');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function createdUser()
  {
    return $this->belongsTo(Examinator::class, 'created_by', 'employee_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function monthlyLogs()
  {
    return $this->hasMany(MonthlyLog::class, 'store_code', 'store_code');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function belongsToTeam()
  {
    return $this->belongsTo(ExaminatorTeam::class, 'team_code', 'team_code');
  }

  /**
   * @return mixed
   */
  public function getHasCurrentRecordAttribute()
  {
    $now = Carbon::now()->format('Y-m');
    return $this->monthlyLogs->contains(function ($record) use ($now) {
      $yearMonth = Carbon::parse($record->examined_at)->format('Y-m');
      return $now === $yearMonth;
    });
  }

  /**
   * @return string
   */
  public function  getZerofillCodeAttribute()
  {
    return sprintf('%05d', $this->store_code);
  }

  /**
   * @return string
   */
  public function getBranchNameAttribute()
  {
    return $this->branch->name;
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

  /**
   * @return string[]
   */
  public function getMasterHeader()
  {
    return $this->masterHeader;
  }

}
