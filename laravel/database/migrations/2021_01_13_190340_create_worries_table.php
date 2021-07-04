<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('worries', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('monthly_log_detail_id');
      $table->unsignedInteger('employee_id');
      $table->timestamps();
      $table->softDeletes();
      $table
        ->foreign('monthly_log_detail_id')
        ->references('id')
        ->on('monthly_log_details')
        ->onDelete('cascade');
      $table
        ->foreign('employee_id')
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
    Schema::table('worries', function(Blueprint $table){
      $table->dropForeign('worries_monthly_log_detail_id_foreign');
      $table->dropForeign('worries_employee_id_foreign');
    });
    Schema::dropIfExists('worries');
  }
}
