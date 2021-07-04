<?php

use Illuminate\Database\Seeder;
use App\Models\Examinator;
use App\Models\Department;

class DepartmentsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $admin = Examinator::first();
    $departments = [
      [
        'department_code' => 1,
        'name' => '検査員',
        'created_by' => $admin->employee_id,
      ],
      [
        'department_code' => 2,
        'name' => '店舗トレーナー',
        'created_by' => $admin->employee_id,
      ],
      [
        'department_code' => 3,
        'name' => '店舗',
        'created_by' => $admin->employee_id,
      ],
    ];

    DB::table('departments')->truncate();
    collect($departments)->each(function($department){
      Department::create($department);
    });

  }
}
