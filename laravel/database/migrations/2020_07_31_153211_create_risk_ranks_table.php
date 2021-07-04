<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiskRanksTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('risk_ranks', function (Blueprint $table) {
      $table->id();
      $table->integer('rank_id');
      $table->string('rank');
      $table->integer('point');
      $table->unsignedInteger('created_by')->nullable()->comment('作成者のemployee_id');
      $table->unsignedInteger('updated_by')->nullable()->comment('更新者のemployee_id');
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
    Schema::table('risk_ranks', function(Blueprint $table){
      $table->dropForeign('risk_ranks_updated_by_foreign');
      $table->dropForeign('risk_ranks_created_by_foreign');
    });
    Schema::dropIfExists('risk_ranks');
  }
}
