<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
			$table->string('user_id', 64)->unique();
			$table->string('password', 192);
			$table->unsignedTinyInteger('rank');
			$table->string('name', 80)->nullable();
			$table->string('email', 80)->nullable();
			$table->string('contact', 24)->nullable();
			$table->string('email_verified_at', 192)->nullable();
			$table->rememberToken();
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin');
    }
}
