<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamIssuesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('exam_issues', function (Blueprint $table) {
      $table->id();
      $table->unsignedInteger('exam_code')->nullable()->comment('検査ID');
      $table->text('name')->comment('設問: 「最重要項目」など');
      $table->text('judgement_base')->nullable()->comment('判定基準');
      $table->unsignedInteger('created_by')->nullable()->comment('作成者のemployee_id');
      $table->timestamps();
      $table->softDeletes();
      $table
        ->foreign('exam_code')
        ->references('exam_code')
        ->on('exams')
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
    Schema::table('exam_issues', function(Blueprint $table){
      $table->dropForeign('exam_issues_exam_code_foreign');
      $table->dropForeign('exam_issues_created_by_foreign');
    });
    Schema::dropIfExists('exam_issues');
  }
}
