<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Examinator;

class ExaminatorRepository extends Repository
{
  public function __construct()
  {
    $this->builder = new Examinator();
  }

  /**
   * @param array $relation
   * @param $employeeId
   */
   public function findByCode(array $relation = [], $employeeId)
   {
      return $this->getBuilder()
        ->with($relation)
        ->where('employee_id', $employeeId)
        ->firstOrFail();
   }

}
