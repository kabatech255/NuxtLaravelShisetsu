<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyLogsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('monthly_logs', function (Blueprint $table) {
      $table->id();
      $table->integer('examined_year');
      $table->integer('examined_month');
      $table->unsignedInteger('store_code')->comment('店舗番号');
      $table->unsignedInteger('exam_code')->comment('検査ID');
      $table->unsignedInteger('examined_by')->comment('検査者の社員ID');
      $table->dateTime('examined_at');
      $table->text('review')->nullable();
      $table->integer('total')->nullable();
      $table->boolean('is_complete')->default(0);
      $table->timestamps();
      $table->softDeletes();
      $table
        ->foreign('store_code')
        ->references('store_code')
        ->on('shops')
        ->onDelete('cascade');
      $table
        ->foreign('exam_code')
        ->references('exam_code')
        ->on('exams')
        ->onDelete('cascade');
      $table
        ->foreign('examined_by')
        ->references('employee_id')
        ->on('examinators')
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
    Schema::table('monthly_logs', function(Blueprint $table){
      $table->dropForeign('monthly_logs_store_code_foreign');
      $table->dropForeign('monthly_logs_exam_code_foreign');
      $table->dropForeign('monthly_logs_examined_by_foreign');
    });
    Schema::dropIfExists('monthly_logs');
  }
}
