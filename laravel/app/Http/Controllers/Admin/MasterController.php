<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MasterService;

class MasterController extends Controller
{
  protected $masterService;

  public function __construct(MasterService $masterService)
  {
    $this->masterService = $masterService;
  }

  /**
   * @param Request $request
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $response = $this->masterService->getTableData($request->all());
    return response($response, 200);
  }
}
