<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Exam;
use Illuminate\Support\Facades\Auth;

class ExamRepository extends Repository
{
  public function __construct()
  {
    $this->builder = new Exam();
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

  /**
   * @param int $examCode
   * @return mixed
   */
  public function nameByCode(int $examCode)
  {
    return $this->getBuilder()
      ->where('exam_code', $examCode)
      ->firstOrFail()
      ->name;
  }

  /**
   * @param array $relation
   * @param int $examCode
   * @return mixed
   */
  public function findByCode(array $relation, int $examCode)
  {
    return $this->getBuilder()
      ->with($relation)
      ->where('exam_code', $examCode)
      ->firstOrFail();
  }

  /**
   * @return mixed
   */
  public function examCodeList()
  {
    return $this->getBuilder()
      ->get()
      ->pluck('exam_code');
  }
}
