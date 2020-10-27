<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connect_log', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 192)->comment('비로그인 세션코드 저장용');
			$table->string('site_uri', 255)->comment('사이트 주소');
			$table->ipAddress('ip')->comment('방문자 IP');
			$table->char('year', 4)->comment('파티셔닝용 년 데이터');
			$table->char('month', 2)->comment('파티셔닝용 월 데이터');
			$table->datetime('created_at')->comment('등록일');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('connect_log');
    }
}
