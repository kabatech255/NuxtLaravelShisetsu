<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use App\Models\Examinator;
use App\Models\MonthlyLogDetail;
use App\Models\MonthlyLog;

class ExamApiTest extends TestCase
{
  use RefreshDatabase;

  protected $monthlyLog;
  protected $targetShop;
  protected $user;

  protected function setUp(): void
  {
    parent::setUp();
    $this->seed();
    $this->targetShop = Shop::with(Shop::RELATIONS_ARRAY)->first();
    $this->monthlyLog = $this->targetShop->monthlyLogs->first();
    $this->user = factory(User::class)->create();
    factory(Examinator::class)->create([
      'employee_id' => $this->user->login_id
    ]);
  }

  /**
   * @test
   * @group examApi
   */
  public function should_指摘内容の追加()
  {
    Storage::fake('s3');
    $expects = [
      'primary_file' => UploadedFile::fake()->image('primary_file.png'),
      'exam_issue_detail_id' => 1,
      'note' => 'this is test note.',
      'created_by' => $this->user->login_id
    ];
    // 指摘内容の投稿
    $response = $this->actingAs($this->user)->postJson(route('monthlyLogDetail.store', ['monthlyLog' => $this->monthlyLog]), $expects);

    /**
     * 201を返すか
     * データベースに保存されているか
     * 画像が保存されているか
     */
    $response->assertCreated();

    $monthlyLogDetail = MonthlyLogDetail::get()->last();
    $this->assertDatabaseHas('monthly_log_details', [
      'note' => $expects['note']
    ]);
    Storage::cloud()->assertExists($monthlyLogDetail->primary_file_name);
  }

  /**
   * @test
   * @group examApi
   */
  public function should_指摘画像データがfile型でないとバリデーションエラーを返却()
  {
    Storage::fake('s3');
    $invalid = [
      'primary_file' => 'not file type',
      'exam_issue_detail_id' => 1,
      'note' => 'this is test note.',
      'created_by' => $this->user->login_id
    ];
    $response = $this->actingAs($this->user)->postJson(route('monthlyLogDetail.store', ['monthlyLog' => $this->monthlyLog]), $invalid);
    $response
      ->assertStatus(422)
      ->assertJsonValidationErrors(['primary_file']);
  }

  /**
   * @test
   * @group examApi
   */
  public function should_投稿者以外が指摘内容を削除しようとするとエラーを返却する()
  {
    //指摘内容の追加
    $monthlyLogDetail = new MonthlyLogDetail();
    $monthlyLogDetail->fill([
      'exam_issue_detail_id' => $this->monthlyLog->exam->examIssues->first()->examIssueDetails->first()->id,
      'note' => 'this is created.',
      'created_by' => $this->user->login_id
    ]);
    $createdLogDetail = $this->monthlyLog->monthlyLogDetails()->save($monthlyLogDetail);

    // 他の検査者による削除
    $otherExaminator = User::get()->first();
    $response = $this->actingAs($otherExaminator)->deleteJson(route('monthlyLogDetail.destroy', ['monthlyLog' => $this->monthlyLog, 'monthlyLogDetail' => $createdLogDetail]));

    // エラーが返ってくるか確認
    $response->assertStatus(422)->assertJsonValidationErrors(['created_by']);

    // DBから削除されていないかチェック
    $this->assertDatabaseHas('monthly_log_details', [
      'id' => $createdLogDetail->id,
      'deleted_at' => null
    ]);

  }

  /**
   * @test
   * @group examCompApi
   */
  public function should_検査完了後に指摘の追加をするとエラーを返却する()
  {
    // 検査完了の処理
    $completeRes = $this->actingAs($this->user)->putJson(route('monthlyLog.complete', ['monthlyLog' => $this->monthlyLog]));
    $completeRes->assertOk();
    // 検査完了カラムが1か確認
    $completedLog = MonthlyLog::find($this->monthlyLog->id);
    $this->assertEquals($completedLog->is_complete, 1);

    // 検査完了したレコードに指摘内容の追加
    Storage::fake('s3');
    $denied = [
      'primary_file' => UploadedFile::fake()->image('denied.png'),
      'exam_issue_detail_id' => 1,
      'note' => 'this will be denied.',
    ];
    $response = $this->actingAs($this->user)->postJson(route('monthlyLogDetail.store', ['monthlyLog' => $this->monthlyLog]), $denied);

    // エラーが返ってくるかチェック
    $response
      ->assertStatus(422)
      ->assertJsonValidationErrors(['exam_issue_detail_id']);
    // DB保存されていないことをチェック
    $this->assertDatabaseMissing('monthly_log_details', [
      'note' => $denied['note']
    ]);
    // 画像が保存されていないことをチェック
    Storage::cloud()->assertMissing('denied.png');

  }

  /**
   * @test
   * @group examApi
   */
  public function should_ゲストは指摘の追加ができない()
  {
    Storage::fake('s3');
    $missing = [
      'primary_file' => UploadedFile::fake()->image('unauthorized_file.png'),
      'exam_issue_detail_id' => 1,
      'note' => 'this is unauthorized note.',
      'created_by' => $this->user->login_id
    ];
    // 認証を経ずに指摘内容の追加
    $response = $this->postJson(route('monthlyLogDetail.store', ['monthlyLog' => $this->monthlyLog]), $missing);
    $response
      ->assertUnauthorized();
    // 画像が保存されていないことの確認
    Storage::cloud()->assertMissing('unauthorized_file.png');
    $this->assertDatabaseMissing('monthly_log_details', [
      'note' => $missing['note']
    ]);
  }
}
