<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSummariesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('summaries', function (Blueprint $table) {
      $table->id();
      $table->integer('year');
      $table->integer('month');
      $table->integer('exam_code');
      $table->text('description');
      $table->unsignedInteger('written_by');
      $table->timestamps();
      $table->softDeletes();
      $table
        ->foreign('written_by')
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
    Schema::table('summaries', function(Blueprint $table){
      $table->dropForeign('summaries_written_by_foreign');
    });
    Schema::dropIfExists('summaries');
  }
}
