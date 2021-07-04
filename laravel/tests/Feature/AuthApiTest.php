<?php
declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Examinator;

class AuthApiTest extends TestCase
{
  use RefreshDatabase;

  protected $user;

  protected function setUp(): void
  {
    parent::setUp();
    $this->seed();
    $this->user = factory(User::class)->create();
    factory(Examinator::class)->create([
      'employee_id' => $this->user->login_id
    ]);
  }

  /**
   * @test
   * @group authApi
   */
  public function should_ログイン成功()
  {
    $expect = [
      'login_id' => $this->user->login_id,
      'password' => 'password',
    ];
    $response = $this->json('POST', route('login', $expect));
    $response
      ->assertOk()
      ->assertJson([
        'employee_id' => $expect['login_id']
      ]);
  }

  /**
   * @test
   * @group authApi
   */
  public function should_現在のユーザーを返す()
  {
    $response = $this->actingAs($this->user)->json('GET', route('currentAuthor'));
    $response
      ->assertOk()
      ->assertJson([
        'employee_id' => $this->user->login_id
      ]);
  }

  /**
   * @test
   * @group authApi
   */
  public function should_passwordが違うとログインできない()
  {
    $differentData = [
      'login_id' => $this->user->login_id,
      'password' => 'differentpassword'
    ];

    $response = $this->json('POST', route('login', $differentData));

    $response
      ->assertStatus(422)
      ->assertJsonValidationErrors('login_id');
  }
}
