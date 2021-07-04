<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use App\Models\Examinator;
use App\Models\MonthlyLogDetail;

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
  public function should_指摘内容の更新()
  {
    // 指摘内容の追加
    $monthlyLogDetail = new MonthlyLogDetail();
    $monthlyLogDetail->fill([
      'exam_issue_detail_id' => $this->monthlyLog->exam->examIssues->first()->examIssueDetails->first()->id,
      'note' => 'this is created.',
      'created_by' => $this->user->login_id
    ]);
    $createdLogDetail = $this->monthlyLog->monthlyLogDetails()->save($monthlyLogDetail);
    $expects = [
      'note' => 'this is updated'
    ];
    // 指摘内容の更新
    $response = $this->actingAs($this->user)->putJson(route('monthlyLogDetail.update', ['monthlyLog' => $this->monthlyLog, 'monthlyLogDetail' => $createdLogDetail]), $expects);
    $response
      ->assertOk();
    $updated = MonthlyLogDetail::find($createdLogDetail->id);
    $this->assertNotEquals($updated->note, $createdLogDetail->note);
  }

  /**
   * @test
   * @group examApi
   */
  public function should_指摘内容の削除()
  {
    $monthlyLogDetail = new MonthlyLogDetail();
    $monthlyLogDetail->fill([
      'exam_issue_detail_id' => $this->monthlyLog->exam->examIssues->first()->examIssueDetails->first()->id,
      'note' => 'this is created.',
      'created_by' => $this->user->login_id
    ]);
    $createdLogDetail = $this->monthlyLog->monthlyLogDetails()->save($monthlyLogDetail);
    $response = $this->actingAs($this->user)->deleteJson(route('monthlyLogDetail.destroy', ['monthlyLog' => $this->monthlyLog, 'monthlyLogDetail' => $createdLogDetail]));
    $response->assertRedirect(route('monthlyLogDetail.index', ['monthlyLog' => $this->monthlyLog]));
    $this->assertSoftDeleted('monthly_log_details', [
      'id' => $createdLogDetail->id
    ]);
  }
}
