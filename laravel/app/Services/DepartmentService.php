<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\DepartmentRepository;

class DepartmentService extends Service
{
  protected $departmentRepository;

  public function __construct(
    DepartmentRepository $departmentRepository
  )
  {
    $this->departmentRepository = $departmentRepository;
  }

  /**
   * @param array $requestArr
   * @return mixed
   */
  public function all(array $requestArr = [])
  {
    return $this->departmentRepository->all($requestArr);
  }
}
