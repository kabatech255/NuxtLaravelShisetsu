<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Branch;
class BranchesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $expects = [
      [
        'branch_code' => Branch::BRANCH_CODE_OKINAWA,
        'name' => '沖縄支社',
      ],
      [
        'branch_code' => Branch::BRANCH_CODE_KYUSHU,
        'name' => '九州支社',
      ],
      [
        'branch_code' => Branch::BRANCH_CODE_CHUSHIKOKU,
        'name' => '中四国支社',
      ],
      [
        'branch_code' => Branch::BRANCH_CODE_KANSAI,
        'name' => '関西支社',
      ],
      [
        'branch_code' => Branch::BRANCH_CODE_TOKAI,
        'name' => '東海支社',
      ],
      [
        'branch_code' => Branch::BRANCH_CODE_HOKURIKU,
        'name' => '北陸支社',
      ],
      [
        'branch_code' => Branch::BRANCH_CODE_KITAKANTO,
        'name' => '北関東支社',
      ],
      [
        'branch_code' => Branch::BRANCH_CODE_MINAMIKANTO,
        'name' => '南関東支社',
      ],
      [
        'branch_code' => Branch::BRANCH_CODE_TOHOKU,
        'name' => '東北支社',
      ],
      [
        'branch_code' => Branch::BRANCH_CODE_HOKKAIDO,
        'name' => '北海道支社',
      ],
    ];

    $admin = User::first();

    DB::table('branches')->truncate();
    foreach($expects as $branch) {
      DB::table('branches')->insert([
        'branch_code' => $branch['branch_code'],
        'name' => $branch['name'],
        'created_by' => $admin->login_id,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }

  }
}
