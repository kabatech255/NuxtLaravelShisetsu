<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExaminatorTeamsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('examinator_teams', function (Blueprint $table) {
      $table->id();
      $table->unsignedInteger('team_code')->unique()->comment('チームコード');
      $table->string('name')->comment('チーム名');
      $table->integer('examinator_area_id')->comment('エリアID');
      $table->unsignedInteger('created_by')->comment('作成者のemployee_id');
      $table->unsignedInteger('updated_by')->comment('更新者のemployee_id');
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
    Schema::table('examinator_teams', function(Blueprint $table){
      $table->dropForeign('examinator_teams_updated_by_foreign');
      $table->dropForeign('examinator_teams_created_by_foreign');
    });
    Schema::dropIfExists('examinator_teams');
  }
}
