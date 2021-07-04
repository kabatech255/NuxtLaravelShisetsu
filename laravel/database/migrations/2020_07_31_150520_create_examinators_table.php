<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExaminatorsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('examinators', function (Blueprint $table) {
      $table->id();
      $table->unsignedInteger('employee_id')->unique()->comment('社員ID');
      $table->unsignedInteger('team_code')->nullable();
      $table->string('name');
      $table->string('file_name')->nullable();
      $table->unsignedInteger('created_by')->nullable()->comment('作成者のemployee_id');
      $table->unsignedInteger('updated_by')->nullable()->comment('作成者のemployee_id');
      $table->timestamps();
      $table->softDeletes();
      $table
        ->foreign('employee_id')
        ->references('login_id')
        ->on('users')
        ->onDelete('cascade');
      $table
        ->foreign('team_code')
        ->references('team_code')
        ->on('examinator_teams')
        ->onDelete('cascade');
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
    Schema::table('examinators', function(Blueprint $table){
      $table->dropForeign('examinators_team_code_foreign');
      $table->dropForeign('examinators_employee_id_foreign');
      $table->dropForeign('examinators_created_by_foreign');
      $table->dropForeign('examinators_updated_by_foreign');
    });
    Schema::dropIfExists('examinators');
  }
}
