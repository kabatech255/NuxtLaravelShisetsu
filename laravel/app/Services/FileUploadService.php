<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileUploadService extends Service
{

  /**
   * @param string $tempPath
   * @param array $putData
   * @return bool
   */
  public function uploadThumbnail(string $tempPath, array $putData)
  {
    $this->putResizedTempFile(500, $putData['fileData'], $tempPath);
    Storage::cloud()->putFileAs($putData['putDir'], $tempPath, $putData['fileName'], 'public');
    return $this->deleteTempFile('public', $putData['fileName']);
  }

  /**
   * @param array $putData
   * @return false|string
   */
  public function upload(array $putData)
  {
    return Storage::cloud()->putFileAs($putData['putDir'], $putData['fileData'], $putData['fileName'], 'public');
  }

  /**
   * @param string $putPath
   * @return bool
   */
  public function delete(string $putPath)
  {
    return Storage::cloud()->delete($putPath);
  }

  /**
   * @param $diskName
   * @param $tempPath
   * @return bool
   */
  public function deleteTempFile($diskName, $tempPath)
  {
    return Storage::disk($diskName)->delete($tempPath);
  }

  /**
   * @param int $width
   * @param $fileData
   * @param $tempPath
   * @return \Intervention\Image\Image
   */
  public function putResizedTempFile(int $width, $fileData, $tempPath)
  {
    return \Image::make($fileData)->resize($width, null, function ($constraint) {
      $constraint->aspectRatio();
    })->save($tempPath);
  }

  /**
   * @param $file
   * @return bool
   */
  public function uploadAsset($file)
  {
    $putData['putDir'] = 'assets/img';
    $putData['fileData'] = $file;
    $putData['fileName'] = $file->getClientOriginalName();
    return $this->upload($putData);
  }

  /**
   * @param array $files
   * @return \Illuminate\Support\Collection
   */
  public function uploadMultiAsset(array $files)
  {
    return collect($files)->map(function($file) {
      if (gettype($file) === 'object') {
        $this->uploadAsset($file);
      }
    });
  }
}
