<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('admins', function (Blueprint $table) {
      $table->id();
      $table->unsignedInteger('login_id');
      $table->string('email');
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password');
      $table->rememberToken();
      $table->timestamps();
      $table->softDeletes();
      $table->unique(['email', 'deleted_at'], 'admins_email_deleted_at_unique');
      $table->unique(['login_id', 'deleted_at'], 'admins_login_id_deleted_at_unique');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('admins');
  }
}
