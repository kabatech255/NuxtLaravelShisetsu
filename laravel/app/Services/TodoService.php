<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Examinator;
use App\Repositories\TodoRepository;
use App\Models\Todo;

class TodoService extends Service
{
  protected $todoRepository;

  public function __construct(TodoRepository $todoRepository)
  {
    $this->todoRepository = $todoRepository;
  }

  /**
   * @param Examinator $examinator
   * @param array $requestArr
   * @return false|\Illuminate\Database\Eloquent\Model
   */
  public function store(Examinator $examinator, array $requestArr)
  {
    return $this->todoRepository->store($examinator, $requestArr);
  }

  /**
   * @param Todo $todo
   * @return bool
   */
  public function done(Todo $todo)
  {
    $todo->is_done = 1;
    return $todo->save();
  }

  /**
   * @param Todo $todo
   * @return bool
   */
  public function didnt(Todo $todo)
  {
    $todo->is_done = 0;
    return $todo->save();
  }

  /**
   * @param Todo $todo
   * @return bool|null
   * @throws \Exception
   */
  public function delete(Todo $todo)
  {
    return $todo->delete();
  }
}
