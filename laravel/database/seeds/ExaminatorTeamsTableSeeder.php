<?php

use Illuminate\Database\Seeder;
use App\Models\Examinator;

class ExaminatorTeamsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    DB::table('examinator_teams')->truncate();
    $teams = [
      [
        'team_code' => 1,
        'name' => '沖縄チーム',
        'examinator_area_id' => 1,
      ],
      [
        'team_code' => 2,
        'name' => '九州チーム',
        'examinator_area_id' => 1,
      ],
      [
        'team_code' => 3,
        'name' => '中四国チーム',
        'examinator_area_id' => 1,
      ],
      [
        'team_code' => 4,
        'name' => '近畿チーム',
        'examinator_area_id' => 1,
      ],
      [
        'team_code' => 5,
        'name' => '東海チーム',
        'examinator_area_id' => 1,
      ],
      [
        'team_code' => 6,
        'name' => '北陸チーム',
        'examinator_area_id' => 2,
      ],
      [
        'team_code' => 7,
        'name' => '南関東チーム',
        'examinator_area_id' => 2,
      ],
      [
        'team_code' => 8,
        'name' => '北関東チーム',
        'examinator_area_id' => 2,
      ],
      [
        'team_code' => 9,
        'name' => '東北チーム',
        'examinator_area_id' => 2,
      ],
      [
        'team_code' => 10,
        'name' => '北海道チーム',
        'examinator_area_id' => 2,
      ],
    ];

    foreach($teams as $team){
      DB::table('examinator_teams')->insert([
        'team_code' => $team['team_code'],
        'name' => $team['name'],
        'examinator_area_id' => $team['examinator_area_id'],
        'created_by' => config('app.test_id'),
        'updated_by' => config('app.test_id'),
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }

  }
}
