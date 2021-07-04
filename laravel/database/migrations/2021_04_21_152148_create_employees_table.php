<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('employees', function (Blueprint $table) {
      $table->id();
      $table->unsignedInteger('employee_id')->comment('社員ID');
      $table->string('name');
      $table->string('file_name')->nullable();
      $table->unsignedInteger('created_by')->nullable()->comment('作成者のemployee_id');
      $table->unsignedInteger('updated_by')->nullable()->comment('作成者のemployee_id');
      $table->timestamps();
      $table->softDeletes();
      $table->unique(['employee_id', 'deleted_at'], 'employees_employee_id_deleted_at_unique');
      $table
        ->foreign('employee_id')
        ->references('login_id')
        ->on('admins')
        ->onDelete('cascade');
      $table
        ->foreign('created_by')
        ->references('login_id')
        ->on('admins')
        ->onDelete('cascade');
      $table
        ->foreign('updated_by')
        ->references('login_id')
        ->on('admins')
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
    Schema::table('employees', function(Blueprint $table){
      $table->dropForeign('employees_employee_id_foreign');
      $table->dropForeign('employees_created_by_foreign');
      $table->dropForeign('employees_updated_by_foreign');
    });

    Schema::dropIfExists('employees');
  }
}
