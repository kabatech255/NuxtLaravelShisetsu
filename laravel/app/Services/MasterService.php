<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\Repository;
use Illuminate\Support\Str;


class MasterService extends Service
{
  protected $eloquentRepository;

  public function __construct(Repository $eloquentRepository)
  {
    $this->eloquentRepository = $eloquentRepository;
  }

  /**
   * @param array $request
   * @return mixed
   */
  public function getTableData(array $request)
  {
    $repoName = $request['modelName'];
    $repository = $this->repositoryByName($repoName);
    // Modelでhiddenに設定しているものは除外
    $fields = $this->getTableHeader($repository, $repoName);
    $items = $repository->customPaginate($request);
    return [
      'fields' => $fields,
      'items' => $items,
    ];
  }

  protected function getTableHeader($repository, $repoName)
  {
    $columns = array_diff($repository->allColumnNames(), $repository->justBuilder()->getHidden());
    $columns = array_merge($repository->justBuilder()->getAppends(), $columns);
//    $columns = $repository->getMasterHeader();
    $columnStyleArr = $repository->justBuilder()->getColumnStyle();
    return collect($columns)->map(function ($col) use ($repoName, $columnStyleArr) {
      $modelName = Str::lower($repoName);
      $attrName = "validation.attributes.{$modelName}.{$col}";
      $labelName = trans("validation.attributes.{$modelName}.{$col}");
      $label = $labelName === $attrName ? $col : $labelName;
      return [
        'key' => $col,
        'label' => $label,
        'style' => $columnStyleArr[$col] ?? ''
      ];
    });
  }

  protected function repositoryByName(string $repoName)
  {
    $repositoryPath = 'App\Repositories\\' . $repoName . 'Repository';
    return new $repositoryPath;
  }
}
