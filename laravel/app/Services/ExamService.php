<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\ExamRepository;
use App\Models\Exam;

class ExamService extends Service
{
  protected $examRepository;

  public function __construct(ExamRepository $examRepository)
  {
    $this->examRepository = $examRepository;
  }

  /**
   * @param array $attributes
   * @return mixed
   */
  public function store(array $attributes)
  {
    return $this->examRepository->store($attributes);
  }

  /**
   * @param array $relation
   * @return mixed
   */
  public function all(array $relation = [])
  {
    return $this->examRepository->all($relation);
  }

  /**
   * @param int $examCode
   * @return mixed
   */
  public function nameByCode(int $examCode)
  {
    return $this->examRepository->nameByCode($examCode);
  }

  /**
   * @param array $relation
   * @param int $examCode
   * @return mixed
   */
  public function findByCode(array $relation, int $examCode)
  {
    return $this->examRepository->findByCode($relation, $examCode);
  }

  public function examCodeArr()
  {
    return $this->examRepository->examCodeList()->all();
  }
}
