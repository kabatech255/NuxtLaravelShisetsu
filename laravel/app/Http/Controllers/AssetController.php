<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileUploadService;

class AssetController extends Controller
{
  protected $fileUploadService;

  public function __construct(FileUploadService $fileUploadService)
  {
    $this->fileUploadService = $fileUploadService;
  }


  public function upload(Request $request)
  {
    return response($this->fileUploadService->uploadMultiAsset($request->all()));
  }

}
