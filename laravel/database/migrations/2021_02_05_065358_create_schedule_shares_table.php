<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleSharesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('schedule_shares', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('schedule_id')->comment('スケジュールID');
      $table->unsignedInteger('employee_id')->comment('共有者');
      $table->unsignedInteger('edit_permission')->default(0)->comment('編集権限');
      $table->timestamps();
      $table->softDeletes();
      $table->foreign('schedule_id')
        ->references('id')
        ->on('schedules')
        ->onDelete('cascade');
      $table->foreign('employee_id')
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
    Schema::table('schedule_shares', function(Blueprint $table){
      $table->dropForeign('schedule_shares_schedule_id_foreign');
    });
    Schema::table('schedule_shares', function(Blueprint $table){
      $table->dropForeign('schedule_shares_employee_id_foreign');
    });
    Schema::dropIfExists('schedule_shares');
  }
}
