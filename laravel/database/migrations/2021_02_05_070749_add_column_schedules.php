<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSchedules extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('schedules', function(Blueprint $table){
      $table->boolean('is_private')->nullable()->default(0)->after('created_by');
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
      $table->dropColumn('is_private');
    });
  }
}
