<?php

use Illuminate\Database\Seeder;
use App\Models\Equipment;
use App\Models\ExamIssueDetail;

class SelectableEquipmentTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $examIssueDetails = ExamIssueDetail::whereIn('issue_content', ['物品存置', '未設置'])->get();
    $selectableEquipment = Equipment::whereIn('name', ['消火器', '散水栓', '消火栓'])->pluck('id')->toArray();

    DB::table('selectable_equipment')->truncate();

    $examIssueDetails->each(function($examIssueDetail) use ($selectableEquipment){
      $examIssueDetail->selectableEquipment()->sync($selectableEquipment);
    });
  }
}
