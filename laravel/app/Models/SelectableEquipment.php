<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SelectableEquipment
 *
 * @property int $id
 * @property int $equipment_id risk_objectsのID
 * @property int $exam_issue_detail_id 指摘内容ID
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|SelectableEquipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SelectableEquipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SelectableEquipment query()
 * @method static \Illuminate\Database\Eloquent\Builder|SelectableEquipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SelectableEquipment whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SelectableEquipment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SelectableEquipment whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SelectableEquipment whereExamIssueDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SelectableEquipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SelectableEquipment whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $updated_by 作成者のemployee_id
 * @method static \Illuminate\Database\Eloquent\Builder|SelectableEquipment whereUpdatedBy($value)
 */
class SelectableEquipment extends Model
{
  protected $table = 'selectable_equipment';
}
