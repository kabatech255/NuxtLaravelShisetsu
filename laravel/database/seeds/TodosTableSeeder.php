<?php

use Illuminate\Database\Seeder;
use App\Models\Todo;

class TodosTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('todos')->truncate();
    DB::table('schedules')->truncate();
    factory(Todo::class, 3)->create();
  }
}
