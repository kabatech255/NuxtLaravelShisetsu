<?php

use Illuminate\Database\Seeder;

class ExaminatorAreasTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $area = ['西日本', '東日本'];
    DB::table('examinator_areas')->truncate();
    foreach($area as $areaName) {
      DB::table('examinator_areas')->insert([
        'name' => $areaName,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }
  }
}
