<?php

use Illuminate\Database\Seeder;
use App\Models\RiskRank;

class RanksTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('risk_ranks')->truncate();
    DB::table('risk_ranks')->insert([
      [
        'rank_id' => RiskRank::RANK_IDS['rankS'],
        'rank' => 'S',
        'point' => RiskRank::RANK_POINTS[RiskRank::RANK_IDS['rankS']],
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'rank_id' => RiskRank::RANK_IDS['rankA'],
        'rank' => 'A',
        'point' => RiskRank::RANK_POINTS[RiskRank::RANK_IDS['rankA']],
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'rank_id' => RiskRank::RANK_IDS['rankB'],
        'rank' => 'B',
        'point' => RiskRank::RANK_POINTS[RiskRank::RANK_IDS['rankB']],
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'rank_id' => RiskRank::RANK_IDS['rankC'],
        'rank' => 'C',
        'point' => RiskRank::RANK_POINTS[RiskRank::RANK_IDS['rankC']],
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'rank_id' => RiskRank::RANK_IDS['rankD'],
        'rank' => 'D',
        'point' => RiskRank::RANK_POINTS[RiskRank::RANK_IDS['rankD']],
        'created_at' => now(),
        'updated_at' => now(),
      ]
    ]);
  }
}
