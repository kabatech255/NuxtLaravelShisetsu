<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\ShopRepository;
use App\Models\Shop;

class ShopService extends Service
{
  protected $shopRepository;

  public function __construct(ShopRepository $shopRepository)
  {
    $this->shopRepository = $shopRepository;
  }

  public function all(array $relation = [])
  {
    return $this->shopRepository->all($relation);
  }

  /**
   * @param array $relation
   * @param int $storeCode
   * @return mixed
   */
  public function findByCode(array $relation, int $storeCode)
  {
    return $this->shopRepository->findByCode($relation, $storeCode);
  }

  /**
   * @param array $relation
   * @param string $keyword
   * @return mixed
   */
  public function filterByKeyword(array $relation, string $keyword)
  {
    return $this->shopRepository->filterByKeyword($relation, $keyword);
  }
}
