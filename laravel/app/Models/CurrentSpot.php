<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CurrentSpot
 *
 * @property int $id
 * @property int $exam_code
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentSpot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentSpot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentSpot query()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentSpot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentSpot whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentSpot whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentSpot whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentSpot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentSpot whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentSpot whereExamCode($value)
 * @property int|null $updated_by 作成者のemployee_id
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentSpot whereUpdatedBy($value)
 */
class CurrentSpot extends Model
{
  //
}
