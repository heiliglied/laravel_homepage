<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_rank')->insert(
			[
				'rank' => 1,
				'name' => '일반회원',
				'default' => 'Y'
			]
		);
    }
}
