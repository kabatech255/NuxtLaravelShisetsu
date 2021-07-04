<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnShops extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shops', function(Blueprint $table){
      $table->unsignedInteger('team_code')->nullable()->after('branch_code')->comment('担当チーム');
      $table->foreign('team_code')
        ->references('team_code')
        ->on('examinator_teams')
        ->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shops', function(Blueprint $table){
      $table->dropForeign('shops_team_code_foreign');
      $table->dropColumn('team_code');
    });
  }
}
