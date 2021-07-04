<?php

namespace Tests\Unit;

use App\Models\Examinator;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DashboardApiTest extends TestCase
{
  protected $user;
  protected $shop;
  protected function setUp(): void
  {
    parent::setUp();
    $this->seed();
    $this->user = User::with(['examinator'])->get()->first();
    $this->shop = Shop::first();
  }

  /**
   *
   */
  public function should_dashboard()
  {
    $response = $this->actingAs($this->user)->getJson(route('dashboard.index'));
    $response->assertOk();
  }
}
