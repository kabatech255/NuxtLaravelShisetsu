<?php
declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\User;
use Carbon\Carbon;
use App\Models\Interfaces\CanDeleteRelationInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait HandledByAdmin
{
  /**
   * @param array $attributes
   * @param User $admin
   * @return mixed
   */
  public function createByAdmin(array $attributes, User $admin)
  {
    $this->fill($attributes);
    $this->created_by = $admin->login_id;
    $this->updated_by = $admin->login_id;
    $this->save();

    return $this;
  }

  /**
   * @param array $attributes
     * @param User $admin
   */
  public function updateByAdmin(array $attributes, User $admin)
  {
    $this->fill($attributes);
    $this->updated_by = $admin->login_id;
    $this->save();
  }

  /**
   * @param User $admin
   * @throws \Exception
   */
  public function deleteByAdmin(User $admin)
  {
//    $this->deleted_by = $admin->id;
    $this->deleted_at = Carbon::now();
    $this->save();

    if ($this instanceof CanDeleteRelationInterface) {
      foreach ($this->getDeleteRelations() as $relation) {
        if ($relation instanceof Collection) {
          $relation->each(function (Model $model) use ($admin) {
            $model->deleteByAdmin($admin);
          });
        } else {
          $relation->deleteByAdmin($admin);
        }
      }
    }
  }

}
