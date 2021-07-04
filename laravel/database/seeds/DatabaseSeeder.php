<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    Model::unguard();
    $this->setFKCheckOff();
    $this->call(ExaminatorAreasTableSeeder::class);
    $this->call(ExaminatorTeamsTableSeeder::class);
    $this->call(UsersTableSeeder::class);
    $this->call(TodosTableSeeder::class);
    $this->call(DepartmentsTableSeeder::class);
    $this->call(BranchesTableSeeder::class);
    $this->call(ShopsTableSeeder::class);
    $this->call(RanksTableSeeder::class);
    $this->call(ExamIssuesSeeder::class);
    $this->call(EquipmentTableSeeder::class);
    $this->call(SelectableEquipmentTableSeeder::class);
    $this->call(MonthlyLogsTableSeeder::class);
    $this->call(SummariesTableSeeder::class);
    $this->setFKCheckOn();
    Model::reguard();
  }

  private function setFKCheckOff() {
    switch(DB::getDriverName()) {
      case 'mysql':
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        break;
      case 'sqlite':
        DB::statement('PRAGMA foreign_keys = OFF');
        break;
    }
  }

  private function setFKCheckOn() {
    switch(DB::getDriverName()) {
      case 'mysql':
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        break;
      case 'sqlite':
        DB::statement('PRAGMA foreign_keys = ON');
        break;
    }
  }
}
