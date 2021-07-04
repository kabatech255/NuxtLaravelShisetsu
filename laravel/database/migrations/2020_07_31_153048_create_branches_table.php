<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('branches', function (Blueprint $table) {
      $table->id();
      $table->unsignedInteger('branch_code')->unique()->comment('支社番号');
      $table->string('name')->comment('支社名');
      $table->unsignedInteger('created_by')->nullable()->comment('作成者のemployee_id');
      $table->unsignedInteger('updated_by')->nullable()->comment('作成者のemployee_id');
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
    Schema::table('branches', function(Blueprint $table){
      $table->dropForeign('branches_updated_by_foreign');
      $table->dropForeign('branches_created_by_foreign');
    });
    Schema::dropIfExists('branches');
  }
}
