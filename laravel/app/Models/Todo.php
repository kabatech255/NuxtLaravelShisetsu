<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HandledByUser;

/**
 * App\Models\Todo
 *
 * @property int $id
 * @property int $employee_id
 * @property string $body
 * @property int $is_done
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Examinator $examinator
 * @property-read string $icon_name
 * @property-read bool $validity
 * @method static \Illuminate\Database\Eloquent\Builder|Todo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Todo newQuery()
 * @method static \Illuminate\Database\Query\Builder|Todo onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Todo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereIsDone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Todo withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Todo withoutTrashed()
 * @mixin \Eloquent
 */
class Todo extends Model
{
  use SoftDeletes;
  use HandledByUser;

  protected $table = 'todos';
  protected $appends = ['validity', 'icon_name'];
  protected $fillable = ['is_done', 'body', 'employee_id'];

//  protected $hidden = ['created_at'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function examinator()
  {
    return $this->belongsTo(Examinator::class, 'employee_id', 'employee_id');
  }

  /**
   * @return bool
   */
  public function getValidityAttribute(): bool
  {
    return !$this->is_done;
  }

  /**
   * @return string
   */
  public function getIconNameAttribute(): string
  {
    return $this->is_done ? 'check_box' : 'check_box_outline_blank';
  }
}
