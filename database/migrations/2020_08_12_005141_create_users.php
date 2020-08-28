<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
			$table->string('user_id', 64)->index();
			$table->string('password', 192);
			$table->unsignedTinyInteger('rank');
			$table->string('name', 80)->nullable();
			$table->string('email', 80)->nullable();
			$table->string('contact', 24)->nullable();
			$table->string('email_verified_at', 192)->nullable();
			$table->rememberToken();
			$table->string('social_path', 32)->nullable();
			$table->string('social_key', 192)->nullable();
			$table->enum('except', ['Y', 'N', 'R'])->nullable()->default('N');
			$table->datetime('excepted_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
