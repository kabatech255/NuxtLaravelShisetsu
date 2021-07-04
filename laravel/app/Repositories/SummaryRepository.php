<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Summary;

class SummaryRepository extends Repository
{
  public function __construct()
  {
    $this->builder = new Summary();
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
   * @param Summary $summary
   * @param array $attributes
   * @return bool
   */
  public function update(Summary $summary, array $attributes)
  {
    return $summary->fill($attributes)->save();
  }

  /**
   * @param array $relation
   * @param array $specificArr
   */
  public function findBySpecificCode(array $relation, $specificArr)
  {
    return $this->getBuilder()
      ->with($relation)
      ->where('year', $specificArr['year'])
      ->where('month', $specificArr['month'])
      ->where('exam_code', $specificArr['examCode'])
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
