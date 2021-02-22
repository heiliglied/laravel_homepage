<?php

namespace App\Services;

use App\Models\User\UserRank;

class UserRankService
{
	static private $instance = null;
	
	static function getInstance() 
	{
		if(self::$instance == null) {
			self::$instance = new UserRankService();
		}
		
		return self::$instance;
	}
	
	function getTotalRecordCount()
	{
		return UserRank::count();
	}
	
	function createRank(array $data)
	{
		return UserRank::insert($data);
	}
	
	function getCount()
	{
		return UserRank::where('rank', '>', 0)->count();
	}
	
	function get()
	{
		return UserRank::where('rank', '>', 0)->orderBy('rank', 'asc')->get();
	}
	
	function getOneRow(String $column, $value)
	{
		return UserRank::where($column, $value)->first();
	}
	
	function getList(int $page, int $limit)
	{
		return UserRank::where('rank', '>', 0)->skip(($page - 1 * $limit))->take($limit)->orderBy('rank', 'asc')->get();
	}
	
	function deleteRank(int $rank)
	{
		return UserRank::where('rank', $rank)->delete();
	}
	
	function updateRank(int $rank, String $name)
	{
		return UserRank::where('rank', $rank)->update(['name' => $name]);
	}
	
	function changeDefault(int $rank)
	{
		return UserRank::where('rank', $rank)->update(['default' => 'Y']);
	}
	
	function advencedUpdate(String $column, String $suggest, $value, array $datas)
	{
		return UserRank::where($column, $suggest, $value)->update($datas);
	}
}