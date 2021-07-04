<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLastDoneMonthsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('last_done_months', function (Blueprint $table) {
      $table->id();
      $table->integer('year');
      $table->integer('month');
      $table->unsignedInteger('exam_code')->unique()->comment('検査ID');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('last_done_months');
  }
}
