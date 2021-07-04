<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LastDoneMonth
 *
 * @property int $id
 * @property int $exam_code
 * @property int $year
 * @property int $month
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LastDoneMonth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LastDoneMonth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LastDoneMonth query()
 * @method static \Illuminate\Database\Eloquent\Builder|LastDoneMonth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LastDoneMonth whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LastDoneMonth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LastDoneMonth whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LastDoneMonth whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LastDoneMonth whereYear($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|LastDoneMonth whereExamCode($value)
 */
class LastDoneMonth extends Model
{
    //
}
