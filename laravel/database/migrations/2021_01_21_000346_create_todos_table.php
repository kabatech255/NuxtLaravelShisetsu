<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodosTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('todos', function (Blueprint $table) {
      $table->id();
      $table->unsignedInteger('employee_id');
      $table->string('body');
      $table->boolean('is_done')->default(0);
      $table->timestamps();
      $table->softDeletes();
      $table->foreign('employee_id')
        ->references('employee_id')
        ->on('examinators')
        ->onDelete('cascade');;
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('todos', function(Blueprint $table){
      $table->dropForeign('todos_employee_id_foreign');
    });
    Schema::dropIfExists('todos');
  }
}
