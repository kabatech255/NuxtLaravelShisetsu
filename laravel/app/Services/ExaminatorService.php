<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\ExaminatorRepository;
use App\Models\Examinator;
use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Auth;
class ExaminatorService extends Service
{
  protected $examinatorRepository;
  protected $fileUploadService;

  public function __construct(
    ExaminatorRepository $examinatorRepository,
    FileUploadService $fileUploadService
  )
  {
    $this->examinatorRepository = $examinatorRepository;
    $this->fileUploadService = $fileUploadService;
  }


  public function all(array $relation = [])
  {
    return $this->examinatorRepository->all($relation);
  }

  /**
   * @param array $requestArr
   * @param Examinator $examinator
   */
  public function updateProfile(array $requestArr, Examinator $examinator)
  {
    $data = collect($requestArr)->forget('department');
    $data = $data->forget('name');
    // file_nameが'null'のときは$examinator->file_nameのパスを引数にして画像削除 & file_nameに空文字を入れて更新
    if (!$data->has('file')) {
      $examinator->updateByAdmin($data->all(), Auth::user());
    } elseif ($data->get('file') === 'null') {
      // 画像をクリアしたい
      $deletePath = $examinator->file_name;
      $data->put('file_name', null);
      $examinator->updateByAdmin($data->all(), Auth::user());
      $this->fileUploadService->delete($deletePath);
    } else {
      $deletePath = $examinator->file_name;
      $putData['putDir'] = 'examinators';
      $putData['fileData'] = $data->get('file');
      $putData['fileName'] = Examinator::getFileName() . '.' . $putData['fileData']->extension();
      $data->put('file_name',"{$putData['putDir']}/{$putData['fileName']}");
      $examinator->updateByAdmin($data->all(), Auth::user());
      $tempPath = storage_path('app/public/' . $putData['fileName']);
      $res = $deletePath === null ? '' : $this->fileUploadService->delete($deletePath);
      $this->fileUploadService->uploadThumbnail($tempPath, $putData);
    }

    if ($data->has('password')) {
      $user = User::find($examinator->user->id);
      $hashed = \Hash::make($data->get('password'));
      $user->updateByUser(['password' => $hashed]);
    }
  }

  /**
   * @param array $relation
   * @param $employeeId
   * @return mixed
   */
  public function findByCode(array $relation = [], $employeeId)
  {
    return $this->examinatorRepository->findByCode($relation, $employeeId);
  }

  /**
   * @return mixed
   */
  public function scheduleMemberList()
  {
    return $this->examinatorRepository->all([]);
  }


}
