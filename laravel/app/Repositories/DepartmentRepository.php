<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Shop;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class DepartmentRepository extends Repository
{
  public function __construct()
  {
    $this->builder = new Department();
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
   * @param Department $department
   * @param array $attributes
   * @return bool
   */
  public function update(Department $department, array $attributes)
  {
    return $department->fill($attributes)->save();
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
