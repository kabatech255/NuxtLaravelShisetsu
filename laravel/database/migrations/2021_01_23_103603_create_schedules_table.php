<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('schedules', function (Blueprint $table) {
      $table->id();
      $table->string('body')->comment('予定の内容');
      $table->string('color')->comment('カレンダーに表示させる色');
      $table->unsignedInteger('created_by')->comment('予定の作成者');
      $table->dateTime('start');
      $table->dateTime('end');
      $table->timestamps();
      $table->softDeletes();
      $table->foreign('created_by')
        ->references('employee_id')
        ->on('examinators')
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
    Schema::table('schedules', function(Blueprint $table){
      $table->dropForeign('schedules_created_by_foreign');
    });
    Schema::dropIfExists('schedules');
  }
}
