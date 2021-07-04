<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ExamRepository;
use App\Repositories\MonthlyLogDetailRepository;
use App\Services\MonthlyLogDetailService;
use App\Models\MonthlyLogDetail;
use App\Models\MonthlyLog;
use App\Models\Exam;

class TopController extends Controller
{
  protected $examRepository;
  protected $monthlyLogDetailRepository;
  protected $monthlyLogDetailService;

  public function __construct(
    ExamRepository $examRepository,
    MonthlyLogDetailRepository $monthlyLogDetailRepository,
    MonthlyLogDetailService $monthlyLogDetailService
  )
  {
    $this->examRepository = $examRepository;
    $this->monthlyLogDetailRepository = $monthlyLogDetailRepository;
    $this->monthlyLogDetailService = $monthlyLogDetailService;
  }

  /**
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   */
  public function index()
  {
    $examList = $this->examRepository->all([])->map(function($exam){
      return [
        'icon_name' => $exam->icon_name,
        'src' => $exam->file_name,
        'color' => $exam->color,
        'name' => $exam->name,
        'desc' => Exam::DESCRIPTIONS_ARR[$exam->exam_code],
        'points' => Exam::POINTS_ARR[$exam->exam_code],
      ];
    });
    $recentLogs = MonthlyLog::orderBy('examined_at', 'desc')->get()->splice(0, 5);
    $recentDetails = $recentLogs->flatMap(function($monthlyLog){
      return $monthlyLog->monthlyLogDetails;
    })->splice(0, 10);
    $articles = $recentDetails->map(function($monthlyLogDetail){
      return $this->monthlyLogDetailService->transformToArticle($monthlyLogDetail);
    });
    $res = [
      'mainVisualPanels' => $examList,
      'articles' => $articles,
    ];

    return response($res, 200);
  }
}
