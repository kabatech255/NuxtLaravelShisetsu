<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Shop;

class ShopRepository extends Repository
{
  public function __construct()
  {
    $this->builder = new Shop();
  }

  /**
   * @param array $relation
   * @param $storeCode
   * @return mixed
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
