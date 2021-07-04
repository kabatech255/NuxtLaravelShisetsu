<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelectableEquipmentTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('selectable_equipment', function (Blueprint $table) {
      $table->id();
      $table->integer('equipment_id')->comment('risk_objectsのID');
      $table->integer('exam_issue_detail_id')->comment('指摘内容ID');
      $table->unsignedInteger('created_by')->nullable()->comment('作成者のemployee_id');
      $table->unsignedInteger('updated_by')->nullable()->comment('作成者のemployee_id');
      $table->timestamps();
      $table->softDeletes();
      $table
        ->foreign('created_by')
        ->references('login_id')
        ->on('users')
        ->onDelete('cascade');
      $table
        ->foreign('updated_by')
        ->references('login_id')
        ->on('users')
        ->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('selectable_equipment', function(Blueprint $table){
      $table->dropForeign('selectable_equipment_updated_by_foreign');
      $table->dropForeign('selectable_equipment_created_by_foreign');
    });
    Schema::dropIfExists('selectable_equipment');
  }
}
