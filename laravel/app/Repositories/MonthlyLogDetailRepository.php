<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Shop;
use App\Models\MonthlyLog;
use App\Models\MonthlyLogDetail;
use Illuminate\Support\Facades\Auth;

class MonthlyLogDetailRepository extends Repository
{
  public function __construct()
  {
    $this->builder = new MonthlyLogDetail();
  }

  /**
   * @param MonthlyLog $monthlyLog
   * @param array $attributes
   * @return false|\Illuminate\Database\Eloquent\Model
   */
  public function store(MonthlyLog $monthlyLog, array $attributes)
  {
    $attributes['note'] = $attributes['note'] === 'null' ? null : $attributes['note'];
    $detail = $this->builder->fill($attributes);
    return $monthlyLog->monthlyLogDetails()->save($detail);
  }

  /**
   * @param MonthlyLogDetail $monthlyLogDetail
   * @param array $attributes
   * @return bool
   */
  public function update(MonthlyLogDetail $monthlyLogDetail, array $attributes)
  {
    $attributes['note'] = $attributes['note'] === 'null' ? null : $attributes['note'];
    return $monthlyLogDetail->fill($attributes)->save();
  }

  /**
   * @param array $relation
   * @param $storeCode
   */
  public function findByCode(array $relation, $storeCode)
  {
    return $this->getBuilder()
      ->with($relation)
      ->where('store_code', $storeCode)
      ->firstOrFail();
  }

  /**
   * @param array $relation
   * @param string $keyword
   * @return mixed
   */
  public function filterByKeyword(array $relation = [], string $keyword = '')
  {
    return $this->getBuilder()
      ->with($relation)
      ->where('store_code', 'like', "$keyword%")
      ->orWhere('name', 'like', "%$keyword%")
      ->get();
  }
}
