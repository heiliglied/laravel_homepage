<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiddler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiddler', function (Blueprint $table) {
            $table->id();
			$table->string('user_id', 192)->index();
			$table->string('random_key', 12)->unique();
			$table->string('html_type', 20)->nullable();
			$table->string('html', 4000)->nullable();
			$table->string('css_type', 20)->nullable();
			$table->string('css', 2000)->nullable();
			$table->string('js_type', 20)->nullable();
			$table->string('script', 2000)->nullable();
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
        Schema::dropIfExists('fiddler');
    }
}
