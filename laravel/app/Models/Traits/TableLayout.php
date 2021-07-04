<?php
declare(strict_types=1);

namespace App\Models\Traits;

trait TableLayout
{
  /**
   * @return mixed
   */
  public function getColumnStyle($prop)
  {
    return $this->columnStyle;
  }

  /**
   * @return string[]
   */
  public function getAppends(): array
  {
    return $this->appends;
  }
}
