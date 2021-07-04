<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Shop;
use App\Models\MonthlyLog;
use Illuminate\Support\Facades\Auth;

class MonthlyLogRepository extends Repository
{
  public function __construct()
  {
    $this->builder = new MonthlyLog();
  }

  /**
   * @param array $attributes
   * @return mixed
   */
  public function store(array $attributes)
  {
    return $this->createByUser($attributes);
  }

  /**
   * @param MonthlyLog $monthlyLog
   * @param array $attributes
   * @return bool
   */
  public function update(MonthlyLog $monthlyLog, array $attributes)
  {
    return $monthlyLog->fill($attributes)->save();
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
