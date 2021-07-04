<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('exams', function (Blueprint $table) {
      $table->id();
      $table->unsignedInteger('exam_code')->unique()->comment('検査ID');
      $table->string('name')->comment('検査名');
      $table->integer('risk_rank_id')->nullable()->comment('リスクランク');
      $table->boolean('is_spot')->comment('スポットかどうか');
      $table->integer('interval')->default(1)->comment('間隔');
      $table->string('file_name')->nullable()->comment('イメージ画像');
      $table->string('icon_name')->nullable()->comment('アイコン');
      $table->string('color')->nullable()->comment('イメージカラー');
      $table->text('description')->nullable()->comment('主にスポット調査用。検査の趣旨、着眼点、指摘基準等');
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
    Schema::table('exams', function(Blueprint $table){
      $table->dropForeign('exams_created_by_foreign');
      $table->dropForeign('exams_updated_by_foreign');
    });
    Schema::dropIfExists('exams');
  }
}
