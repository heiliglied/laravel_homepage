<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdeaBoard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idea_board', function (Blueprint $table) {
            $table->id();
			$table->string('writer', 12);
            $table->unsignedBigInteger('user_id')->index();
            $table->string('subject', '255')->index();
            $table->longText('contents');
			$table->string('content_type', '16')->nullable();
			$table->integer('view')->default(0);
			$table->string('files', '1')->nullable();
			$table->char('censorship', 1)->default('N');
			$table->datetime('deleted_at')->nullable();
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
        Schema::dropIfExists('idea_board');
    }
}
