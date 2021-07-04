<?php
declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\User;
use App\Models\Interfaces\CanDeleteRelationInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait HandledByUser
{
  /**
   * @param array $attributes
   * @return $this
   */
  public function createByUser(array $attributes)
  {
    $this->fill($attributes);
    $this->save();
    return $this;
  }

  /**
   * @param array $attributes
   * @return $this
   */
  public function updateByUser(array $attributes)
  {
    $this->fill($attributes);
    $this->save();
    return $this;
  }

  /**
   * @throws \Exception
   */
  public function deleteByUser()
  {
//    $this->deleted_by = null;
    $this->deleted_at = Carbon::now();
    $this->save();

    if ($this instanceof CanDeleteRelationInterface) {
      foreach ($this->getDeleteRelations() as $relation) {
        if ($relation instanceof Collection) {
          $relation->each(function (Model $model) {
            if ($model instanceof CanDeleteRelationInterface) {
              $model->deleteByUser();
            }
          });
        } elseif ($relation instanceof CanDeleteRelationInterface) {
          $relation->deleteByUser();
        }
      }
    }
  }

}
