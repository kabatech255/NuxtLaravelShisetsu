<?php
declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Shop;
use Illuminate\Support\Carbon;
use App\Models\Exam;
use App\Models\User;
use App\Models\Examinator;
use App\Services\ShopService;

class ShopApiTest extends TestCase
{
  use RefreshDatabase;

  protected $shops;
  protected $shop;
  protected $user;
  protected $shopService;

  protected function setUp(): void
  {
    parent::setUp();
    $this->seed();
    $this->shops = Shop::all();
    $this->shop = $this->shops->first();
    $this->user = factory(User::class)->create();
    factory(Examinator::class)->create([
      'employee_id' => $this->user->login_id
    ]);
  }
  /**
   * @test
   * @group shopApi
   */
  public function should_キーワード検索できる()
  {
    $keyword = 'nothing';
    $response = $this->getJson(route('shop.filterByKeyword', $keyword));
    $response->assertStatus(200);
  }

  /**
   * @test
   * @group shopApi
   */
  public function should_検査スタートボタンでJsonデータを返す()
  {
    $args = [
      'examined_year' => (int)Carbon::now()->format('Y'),
      'examined_month' => (int)Carbon::now()->format('m'),
      'store_code' => $this->shop->store_code,
      'examined_by' => $this->user->login_id
    ];
    $expects = collect([
      'monthly_logs' => [
        '2019-10' => [
          'count' => 32
        ],
        '2019-11' => [
          'count' => 32
        ],
        '2019-12' => [
          'count' => 32
        ],
      ],
    ]);
    $response = $this->actingAs($this->user)->postJson(route('monthlyLog.store', $args));
    $response->assertCreated();
  }
}
