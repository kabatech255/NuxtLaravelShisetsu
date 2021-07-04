<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Todo;
use App\Models\Examinator;

class TodoRepository extends Repository
{
  public function __construct()
  {
    $this->builder = new Todo();
  }

  /**
   * @param Examinator $examinator
   * @param array $attributes
   * @return false|\Illuminate\Database\Eloquent\Model
   */
  public function store(Examinator $examinator, array $attributes)
  {
    $todo = $this->builder->fill($attributes);
    return $examinator->todos()->save($todo);
  }

  /**
   * @param array $relation
   * @param $employeeId
   * @return mixed
   */
  public function findByOwner(array $relation, $employeeId)
  {
    return $this->getBuilder()
      ->with($relation)
      ->findOrFail($employeeId);
  }
}
