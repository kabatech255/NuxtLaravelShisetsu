<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\MonthlyLogDetailRepository;
use App\Services\MonthlyLogService;
use App\Services\ShopService;
use App\Services\FileUploadService;
use App\Models\MonthlyLog;
use App\Models\MonthlyLogDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class MonthlyLogDetailService extends Service
{
  protected $monthlyLogService;
  protected $monthlyLogDetailRepository;
  protected $shopService;
  protected $fileUploadService;

  public function __construct(
    MonthlyLogService $monthlyLogService,
    MonthlyLogDetailRepository $monthlyLogDetailRepository,
    ShopService $shopService,
    FileUploadService $fileUploadService
  )
  {
    $this->monthlyLogService = $monthlyLogService;
    $this->monthlyLogDetailRepository = $monthlyLogDetailRepository;
    $this->shopService = $shopService;
    $this->fileUploadService = $fileUploadService;
  }

  /**
   * @param MonthlyLog $monthlyLog
   * @param array $requestArr
   * @return array|\Illuminate\Support\Collection
   */
  public function store(MonthlyLog $monthlyLog, array $requestArr)
  {
    // 画像系のカラムデータ追加
    $files = $this->getFileColumns($requestArr);
    $data = array_merge($requestArr, $this->fileColumnsArray($files, $monthlyLog, $requestArr));
    // 指摘内容の新規登録
    $monthlyLogDetailRepository = new MonthlyLogDetailRepository();
    $newDetail = $monthlyLogDetailRepository->store($monthlyLog, $data);
    // 画像のアップロード
    $files->map(function($file, $key) use($data, $monthlyLog){
      return $this->uploadExamFiles($file, $key, $data, $monthlyLog);
    });
    // APIの返却
    return $this->getExamApi($monthlyLog->store_code);
  }

  public function update(MonthlyLog $monthlyLog, MonthlyLogDetail $monthlyLogDetail, array $requestArr)
  {
    $beforeData = collect($monthlyLogDetail)->filter(function($val, $key){
      return preg_match('/^.+_file_name$/', $key);
    })->all();
    // 画像系のカラムデータ追加
    $files = $this->getFileColumns($requestArr);
    $data = array_merge($requestArr, $this->fileColumnsArray($files, $monthlyLog, $requestArr));
    // レコード更新
    $this->monthlyLogDetailRepository->update($monthlyLogDetail, $data);
    // 画像アップロード&削除
    $files->map(function($file, $key) use($data, $monthlyLog, $beforeData){
      $columnName = $key . '_name';
      if (gettype($file) === 'object') {
        // 画像アップロード
        $uploaded = $this->uploadExamFiles($file, $key, $data, $monthlyLog);
        if (gettype($beforeData[$columnName]) === 'string' && $beforeData[$columnName] !== $data[$columnName]) {
          // 旧画像の削除
          return $this->deleteExamFiles($beforeData[$columnName]);
        }
        return $uploaded;
      } elseif (gettype($beforeData[$columnName]) === 'string' && $beforeData[$columnName] !== $data[$columnName]) {
        return $this->deleteExamFiles($beforeData[$columnName]);
      } else {
        return [$beforeData[$columnName], $data[$columnName]];
      }
    });
    // APIの返却
    return $this->getExamApi($monthlyLog->store_code);
  }

  /**
   * @param MonthlyLog $monthlyLog
   * @param MonthlyLogDetail $monthlyLogDetail
   * @return \Illuminate\Support\Collection
   * @throws \Exception
   */
  public function delete(MonthlyLog $monthlyLog, MonthlyLogDetail $monthlyLogDetail)
  {
    $files = collect($monthlyLogDetail)->filter(function($val, $key){
      return preg_match('/^.+_file_name$/', $key);
    })->all();
    // テーブル削除
    $monthlyLogDetail->delete();
    // s3上の画像データ削除
    collect($files)->each(function($file){
      if(gettype($file) === 'string' && !preg_match('/^.+\/sample_/u', $file)){
        $this->deleteExamFiles($file);
      }
    });
    // APIの取得
    return $this->getExamApi($monthlyLog->store_code);
  }

  /**
   * @param MonthlyLogDetail $monthlyLogDetail
   */
  public function worry(MonthlyLogDetail $monthlyLogDetail)
  {
    $monthlyLogDetail->worriedMembers()->detach(Auth::user()->login_id);
    $monthlyLogDetail->worriedMembers()->attach(Auth::user()->login_id);
  }

  public function unworry(MonthlyLogDetail $monthlyLogDetail)
  {
    $monthlyLogDetail->worriedMembers()->detach(Auth::user()->login_id);
  }

  /**
   * @param array $requestArr
   * @return \Illuminate\Support\Collection
   */
  public function getFileColumns(array $requestArr)
  {
    return collect($requestArr)->filter(function($val, $key){
      return preg_match('/^.+_file$/', $key);
    });
  }

  /**
   * @param \Illuminate\Support\Collection $files
   * @param MonthlyLog $monthlyLog
   * @param array $requestArr
   * @return array
   */
  public function fileColumnsArray(\Illuminate\Support\Collection $files, MonthlyLog $monthlyLog, array $requestArr)
  {
    return $files->map(function($file, $key) use($monthlyLog, $requestArr){
      $columnName = $key . '_name';
      if(gettype($file) === 'object') {
        $lastName = Str::random(8);
        $ext = $file->extension();
        $putDir = "logs/{$monthlyLog->examined_year}/{$monthlyLog->examined_month}/{$monthlyLog->exam_code}/$monthlyLog->store_code";
        $fileName = "{$monthlyLog->id}-{$requestArr['exam_issue_detail_id']}-{$key}-{$lastName}.{$ext}";
        return [$columnName => "{$putDir}/{$fileName}"];
      } elseif($file === 'null') {
        return [$columnName => null];
      } else {
        return [$columnName => $file];
      }
    })->collapse()->all();
  }

  /**
   * @param $file
   * @param string $key
   * @param $examIssueDetailId
   * @param MonthlyLog $monthlyLog
   * @return false|string|null
   */
  public function uploadExamFiles($file, string $key, $data, MonthlyLog $monthlyLog)
  {
    if(gettype($file) === 'object') {
      $columnName = $key . '_name';
      $separatedArr = explode('/', $data[$columnName]);
      $putData['fileName'] = $separatedArr[count($separatedArr) - 1];
      $putData['putDir'] = "logs/{$monthlyLog->examined_year}/{$monthlyLog->examined_month}/{$monthlyLog->exam_code}/$monthlyLog->store_code";
      $putData['fileData'] = $file;
      $tempPath = storage_path('app/public/' . $putData['fileName']);
      return $this->fileUploadService->uploadThumbnail($tempPath, $putData);
    } else {
      return null;
    }
  }

  /**
   * @param string $fileName
   * @return bool
   */
  public function deleteExamFiles(string $fileName)
  {
    return $this->fileUploadService->delete($fileName);
  }

  /**
   * @param $storeCode
   * @return \Illuminate\Support\Collection
   */
  public function getExamApi($storeCode)
  {
    $shop = $this->shopService->findByCode(['monthlyLogs'], (int)$storeCode);
    return $this->monthlyLogService->examApi($shop);
  }

  /**
   * @param MonthlyLogDetail $monthlyLogDetail
   * @param bool $withWorriedAt
   * @return array
   */
  public function transformToArticle(MonthlyLogDetail $monthlyLogDetail, bool $withWorriedAt = false)
  {
    $color = $monthlyLogDetail->belongs_to_issue->exam->color;
    $examName = $monthlyLogDetail->belongs_to_issue->exam->name;
    $shopName = $monthlyLogDetail->monthlyLog->shop->name;
    $examinedAt = Carbon::parse($monthlyLogDetail->monthlyLog->examined_at)->format('n/j');
    $desc = "{$examinedAt}\n{$shopName}";
    $worriedAt = $withWorriedAt ?  Carbon::parse($monthlyLogDetail->pivot->created_at)->timestamp : null;
    return [
      // ブックマーク解除/再登録用
      'id' => $monthlyLogDetail->id,
      'monthly_log_id' => $monthlyLogDetail->monthlyLog->id,
      // 内容
      'title' => $monthlyLogDetail->body,
      'src' => $monthlyLogDetail->primary_file_name,
      'desc' => $desc,
      'store_code' => $monthlyLogDetail->monthlyLog->store_code,
      'tag' => $examName,
      'color' => $color,
      'examined_at' => $examinedAt,
      // ブックマークカードのスタイル用(falseにしたらdisabledのスタイルをあてる)
      'is_worried' => $monthlyLogDetail->is_worried,
      // 並び替え用
      'worried_at' => $worriedAt,
    ];
  }
}
