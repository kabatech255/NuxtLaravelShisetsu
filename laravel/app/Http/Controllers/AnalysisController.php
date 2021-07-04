<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnalysisService;
use App\Services\ExamService;

class AnalysisController extends Controller
{

  protected $analysisService;
  protected $examService;

  public function __construct(
    AnalysisService $analysisService,
    ExamService $examService
  )
  {
    $this->analysisService = $analysisService;
    $this->examService = $examService;
  }

  /**
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   */
  public function index(int $examCode)
  {
    $chartData = $this->analysisService->index($examCode);
    return response($chartData);
  }

  /**
   * @param int $examCode
   * @return \Illuminate\Support\Collection
   */
  public function showShopRanks(int $examCode)
  {
    return $this->analysisService->showShopRanks($examCode);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
