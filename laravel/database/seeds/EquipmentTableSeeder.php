<?php

use Illuminate\Database\Seeder;
use App\Models\Equipment;
use App\Models\User;

class EquipmentTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $admin = User::first();
    $expects = [
      [
        'name' => '消火器',
        'created_by' => $admin->login_id
      ],
      [
        'name' => '散水栓',
        'created_by' => $admin->login_id
      ],
      [
        'name' => '消火栓',
        'created_by' => $admin->login_id
      ],
      [
        'name' => '避難経路図',
        'created_by' => $admin->login_id
      ],
      [
        'name' => '温度計',
        'created_by' => $admin->login_id
      ],
      [
        'name' => '温度管理表',
        'created_by' => $admin->login_id
      ],
      [
        'name' => '防カビ剤POP',
        'created_by' => $admin->login_id
      ],
      [
        'name' => 'アレルギー表示',
        'created_by' => $admin->login_id
      ],
    ];

    DB::table('equipment')->truncate();
    foreach ($expects as $expect) {
      Equipment::create($expect);
    }
  }
}
