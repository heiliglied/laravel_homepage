<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdeaReply extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idea_reply', function (Blueprint $table) {
            $table->id();
			$table->string('writer', 12);
			$table->integer('user_id');
			$table->string('table_name', 32);
            $table->string('board_id', '120');
            $table->longText('reply');
            $table->integer('parent_id');
			$table->integer('depth');
			$table->char('censorship', 1)->default('N');
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
        Schema::dropIfExists('idea_reply');
    }
}
