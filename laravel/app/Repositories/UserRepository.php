<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Models\Examinator;

class UserRepository extends Repository
{
  public function __construct()
  {
    $this->builder = new User();
  }
}
