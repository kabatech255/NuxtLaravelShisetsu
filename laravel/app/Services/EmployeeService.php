<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\EmployeeRepository;
use App\Models\Employee;
use App\Models\Admin;

use App\Services\FileUploadService;
use Illuminate\Support\Facades\Auth;
class EmployeeService extends Service
{
  protected $employeeRepository;
  protected $fileUploadService;

  public function __construct(
    EmployeeRepository $employeeRepository,
    FileUploadService $fileUploadService
  )
  {
    $this->employeeRepository = $employeeRepository;
    $this->fileUploadService = $fileUploadService;
  }


  public function all(array $relation = [])
  {
    return $this->employeeRepository->all($relation);
  }

  /**
   * @param array $requestArr
   * @param Employee $employee
   */
  public function updateProfile(array $requestArr, Employee $employee)
  {
    $data = collect($requestArr)->forget('department');
    $data = $data->forget('name');
    // file_nameが'null'のときは$employee->file_nameのパスを引数にして画像削除 & file_nameに空文字を入れて更新
    if (!$data->has('file')) {
      $employee->updateByAdmin($data->all(), Auth::guard('admin')->user());
    } elseif ($data->get('file') === 'null') {
      // 画像をクリアしたい
      $deletePath = $employee->file_name;
      $data->put('file_name', null);
      $employee->updateByAdmin($data->all(), Auth::user());
      $this->fileUploadService->delete($deletePath);
    } else {
      $deletePath = $employee->file_name;
      $putData['putDir'] = 'employees';
      $putData['fileData'] = $data->get('file');
      $putData['fileName'] = Employee::getFileName() . '.' . $putData['fileData']->extension();
      $data->put('file_name',"{$putData['putDir']}/{$putData['fileName']}");
      $employee->updateByAdmin($data->all(), Auth::guard('admin')->user());
      $tempPath = storage_path('app/public/' . $putData['fileName']);
      $res = $deletePath === null ? '' : $this->fileUploadService->delete($deletePath);
      $this->fileUploadService->uploadThumbnail($tempPath, $putData);
    }

    if ($data->has('password')) {
      $employee = Admin::find($employee->admin->id);
      $hashed = \Hash::make($data->get('password'));
      $employee->updateByUser(['password' => $hashed]);
    }
  }

  /**
   * @param array $relation
   * @param $employeeId
   * @return mixed
   */
  public function findByCode(array $relation = [], $employeeId)
  {
    return $this->employeeRepository->findByCode($relation, $employeeId);
  }

  /**
   * @return mixed
   */
  public function scheduleMemberList()
  {
    return $this->employeeRepository->all([]);
  }


}
