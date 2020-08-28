<?php

namespace App\Services;

use App\Models\AdminRank;

class AdminRankService
{
	static private $instance = null;
	
	static function getInstance() 
	{
		if(self::$instance == null) {
			self::$instance = new AdminRankService();
		}
		
		return self::$instance;
	}
	
	function getTotalRecordCount()
	{
		return AdminRank::count();
	}
	
	function createRank(array $data)
	{
		return AdminRank::insert($data);
	}
	
	function getList(int $page, int $limit)
	{
		return AdminRank::where('rank', '>', 0)->skip(($page - 1 * $limit))->take($limit)->orderBy('rank', 'asc')->get();
	}
	
	function deleteRank(int $rank)
	{
		return AdminRank::where('rank', $rank)->delete();
	}
	
	function updateRank(int $rank, String $name)
	{
		return AdminRank::where('rank', $rank)->update(['name' => $name]);
	}
}