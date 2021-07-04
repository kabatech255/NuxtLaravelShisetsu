<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shops', function (Blueprint $table) {
      $table->id();
      $table->unsignedInteger('branch_code')->nullable()->comment('支社番号');
      $table->unsignedInteger('store_code')->unique()->comment('店舗番号');
      $table->string('name');
      $table->string('file_name')->nullable();
      $table->unsignedInteger('created_by')->nullable()->comment('作成者のemployee_id');
      $table->unsignedInteger('updated_by')->nullable()->comment('作成者のemployee_id');
      $table->timestamps();
      $table->softDeletes();
      $table
        ->foreign('branch_code')
        ->references('branch_code')
        ->on('branches')
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
    Schema::table('shops', function(Blueprint $table){
      $table->dropForeign('shops_updated_by_foreign');
      $table->dropForeign('shops_created_by_foreign');
      $table->dropForeign('shops_branch_code_foreign');
    });
    Schema::dropIfExists('shops');
  }
}
