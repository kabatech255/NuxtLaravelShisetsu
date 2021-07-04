<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Examinator;
use App\Models\ExaminatorTeam;
use Faker\Generator as Faker;
class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(Faker $faker)
  {
    DB::table('users')->truncate();
    DB::table('examinators')->truncate();

    $testUser = factory(User::class)->create([
      'login_id' => config('app.test_id'),
      'password' => \Hash::make(config('app.test_pass')),
      'department_code' => User::EXAMINATOR_SECTION,
      'email' => 'torakichisite@gmail.com',
    ]);

    $testEmployee = factory(Examinator::class)->create([
      'team_code' => 1,
      'name' => 'テスト太郎',
      'employee_id' => $testUser->login_id,
      'created_by' => $testUser->login_id,
    ]);

    $counts = [
      1 => 1,
      2 => 2,
      3 => 2,
      4 => 4,
      5 => 2,
      6 => 1,
      7 => 2,
      8 => 2,
      9 => 1,
      10 => 2
    ];
    $teams = ExaminatorTeam::all();
    $teams->each(function($team) use ($testUser, $counts, $faker){
      $sampleUsers = factory(User::class, $counts[$team->team_code])->create([
        'department_code' => User::EXAMINATOR_SECTION,
      ]);

      $sampleUsers->each(function($sampleUser) use ($testUser, $team, $faker) {
        $teamName = str_replace('チーム', '', $team->name);
        factory(Examinator::class)->create([
          'employee_id' => $sampleUser->login_id,
          'team_code' => $team->team_code,
          'name' => $teamName . $faker->firstName,
          'created_by' => $testUser->login_id,
        ]);
      });
    });


  }
}
