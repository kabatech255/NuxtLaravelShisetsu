<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamIssueDetailsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('exam_issue_details', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('exam_issue_id')->comment('設問ID');
      $table->text('issue_content')->comment('指摘内容');
      $table->unsignedInteger('created_by')->nullable()->comment('作成者のemployee_id');
      $table->unsignedInteger('updated_by')->nullable()->comment('作成者のemployee_id');
      $table->timestamps();
      $table->softDeletes();
      $table
        ->foreign('exam_issue_id')
        ->references('id')
        ->on('exam_issues')
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
    Schema::table('exam_issue_details', function(Blueprint $table){
      $table->dropForeign('exam_issue_details_updated_by_foreign');
      $table->dropForeign('exam_issue_details_created_by_foreign');
      $table->dropForeign('exam_issue_details_exam_issue_id_foreign');
    });
    Schema::dropIfExists('exam_issue_details');
  }
}
