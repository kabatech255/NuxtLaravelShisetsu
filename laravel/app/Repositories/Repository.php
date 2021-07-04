<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Examinator;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class Repository
{
  protected $builder;

  public function getBuilder()
  {
    return $this->builder->query();
  }

  public function justBuilder()
  {
    return $this->builder;
  }

  public function createByUser(array $attributes)
  {
    return $this->builder->createByUser($attributes);
  }

  public function updateByUser(array $attributes)
  {
    return $this->builder->updateByUser($attributes);
  }

  /**
   * @param array|string[] $relation
   * @return mixed
   */
  public function all(array $relation = [])
  {
    return $this->getBuilder()->with($relation)->get();
  }

  /**
   * @param array $relation
   * @param int $id
   * @return mixed
   */
  public function findById(array $relation, int $id)
  {
    return $this->getBuilder()
      ->with($relation)
      ->where('id', $id)
      ->firstOrFail();
  }

  /**
   * @return array
   */
  public function allColumnNames()
  {
    return Schema::getColumnListing($this->builder->getTable());
  }

  /**
   * @param array $sortData
   * @param int $perPage
   * @return mixed
   */
  public function sortedByPaginate(array $sortData, int $perPage)
  {
    $items = $this->all();
    if ($sortData['orderBy'] === 'desc') {
      $forPage = $items->sortByDesc($sortData['sortBy'])->forPage($sortData['page'], $perPage)->values()->all();
    } else {
      $forPage = $items->sortBy($sortData['sortBy'])->forPage($sortData['page'], $perPage)->values()->all();
    }
    return $forPage;
  }

  public function customPaginate(array $attributes = [])
  {

    $q = $this->getBuilder()->with($this->builder::RELATIONS_ARRAY);
    if (!empty($attributes['sortBy'] ?? '') && $attributes['sortBy'] !== 'null'){
      $sortArr = explode('.', $attributes['sortBy']);
      $sortKey = count($sortArr) > 1 ? $sortArr[1] : $sortArr[0];
      $q->orderBy($sortKey, $attributes['orderBy']);
    }

    return $q->paginate(10);
  }


}
