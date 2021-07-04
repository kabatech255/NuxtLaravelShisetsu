<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Interfaces\CanDeleteRelationInterface;
use App\Models\Traits\HandledByUser;
use App\Models\Traits\HandledByAdmin;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * App\Models\Exam
 *
 * @property int $id
 * @property string $name 検査名
 * @property int|null $risk_rank_id リスクランク
 * @property int $is_spot スポットかどうか
 * @property int $interval 間隔
 * @property string|null $file_name イメージ画像
 * @property string|null $color イメージカラー
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ExamIssue[] $examIssues
 * @property-read int|null $exam_issues_count
 * @property-read \App\Models\RiskRank|null $riskRank
 * @method static \Illuminate\Database\Eloquent\Builder|Exam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exam query()
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereIsSpot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereRiskRankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $exam_code
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereExamCode($value)
 * @property string|null $description 主にスポット調査用。検査の趣旨、着眼点、指摘基準等
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|Exam onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|Exam withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Exam withoutTrashed()
 * @property string|null $icon_name アイコン
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereIconName($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MonthlyLog[] $monthlyLogs
 * @property-read int|null $monthly_logs_count
 * @property int|null $updated_by 作成者のemployee_id
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereUpdatedBy($value)
 */
class Exam extends Model implements CanDeleteRelationInterface
{
  use HandledByUser;
  use HandledByAdmin;
  use SoftDeletes;

  const EXAM_CODE_BOUSAI = 1;
  const EXAM_CODE_SHOKUHIN = 2;
  const EXAM_CODE_KODOIRYO = 3;
  const EXAM_LABEL_ARR = [
    self::EXAM_CODE_BOUSAI => '防災',
    self::EXAM_CODE_SHOKUHIN => '食品',
    self::EXAM_CODE_KODOIRYO => '高度医療',
  ];

  const DESCRIPTIONS_ARR = [
    self::EXAM_CODE_BOUSAI => '火災の予防、および火災発生時の避難対応に関するチェックをします。',
    self::EXAM_CODE_SHOKUHIN => "要冷商品の温度管理や、売り場を清潔に保っているか等をチェックします。",
    self::EXAM_CODE_KODOIRYO => "コンタクトレンズやピアッサーなどの取扱いにおける義務を遵守しているかチェックします。",
  ];

  const POINTS_ARR = [
    self::EXAM_CODE_BOUSAI => [
      '非常扉等の前に物を置いていないか',
      '避難経路の幅員が確保されているか',
      'タコ足配線などが放置されていないか',
    ],
    self::EXAM_CODE_SHOKUHIN => [
      '要冷商品の温度管理は適正か',
      '冷ケース設備の管理状況は適正か',
      '酒類販売等のPOP設置ルール',
    ],
    self::EXAM_CODE_KODOIRYO => [
      '購入者から同意書を取っているか',
      '法令に定める継続的研修の受講状況等',
      '従業者に対する教育訓練の実施状況等',
    ],
  ];

  const RELATIONS_ARRAY = ['examIssues.examIssueDetails'];

  protected $table = 'exams';
  protected $primaryKey = 'exam_code';
  protected $fillable = [
    'name',
    'risk_rank_id',
    'is_spot',
    'interval',
    'file_name',
    'icon_name',
    'color',
    'created_by'
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function examIssues()
  {
    return $this->hasMany(ExamIssue::class, 'exam_code', 'id');
  }

  public function monthlyLogs()
  {
    return $this->hasMany(MonthlyLog::class, 'exam_code', 'exam_code');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function riskRank()
  {
    return $this->belongsTo(RiskRank::class, 'risk_rank_id', 'rank_id');
  }

  public function getDeleteRelations()
  {
    return [$this->examIssues];
  }

}
