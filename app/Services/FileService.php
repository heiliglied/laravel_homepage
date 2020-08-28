<?php 

namespace App\Services;

use App\Models\Files;

class FileService
{
	static private $instance = null;
	
	static function getInstance() 
	{
		if(self::$instance == null) {
			self::$instance = new FileService();
		}
		
		return self::$instance;
	}
	
	public function insert(array $datas)
	{
		return Files::insert($datas);
	}
	
	public function getList(array $condition)
	{
		return Files::where(function($query) use($condition) {
			foreach($condition as $key => $value) {
				$query->where($key, $value);
			}
		})->get();
	}
	
	public function getOneRow($value)
	{
		return Files::where('id', $value)->first();
	}
	
	public function deleteRow(String $column, $value)
	{
		return Files::where($column, $value)->delete();
	}
	
	public function getFileCount($table_name, $board_num)
	{
		return Files::where('table_name', $table_name)->where('board_num', $board_num)->count();
	}
	
	public function deleteList(array $condition)
	{
		return Files::where(function($query) use($condition) {
			foreach($condition as $key => $value) {
				$query->where($key, $value);
			}
		})->delete();
	}
}