<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyLogDetailsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('monthly_log_details', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('monthly_log_id');
      $table->unsignedBigInteger('exam_issue_detail_id')->comment('指摘内容ID');
      $table->string('primary_file_name')->nullable()->comment('違反画像1');
      $table->string('secondary_file_name')->nullable()->comment('違反画像予備(原則1枚)');
      $table->string('improved_file_name')->nullable()->comment('改善画像');
      $table->boolean('is_improved')->default(0)->comment('改善フラグ');
      $table->text('note')->nullable()->comment('備考欄');
      $table->unsignedInteger('created_by')->nullable()->comment('違反箇所を発見した人');
      $table->timestamps();
      $table->softDeletes();
      $table
        ->foreign('monthly_log_id')
        ->references('id')
        ->on('monthly_logs')
        ->onDelete('cascade');
      $table
        ->foreign('exam_issue_detail_id')
        ->references('id')
        ->on('exam_issue_details')
        ->onDelete('cascade');
      $table
        ->foreign('created_by')
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
    Schema::table('monthly_log_details', function(Blueprint $table){
      $table->dropForeign('monthly_log_details_created_by_foreign');
      $table->dropForeign('monthly_log_details_exam_issue_detail_id_foreign');
      $table->dropForeign('monthly_log_details_monthly_log_id_foreign');
    });
    Schema::dropIfExists('monthly_log_details');
  }
}
