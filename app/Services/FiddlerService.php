<?php

namespace App\Services;

use App\Models\Fiddler;
use DB;

class FiddlerService
{
	static private $instance = null;
	
	static function getInstance() 
	{
		if(self::$instance == null) {
			self::$instance = new FiddlerService();
		}
		
		return self::$instance;
	}
	
	public function getCount(String $column, $value)
	{
		return Fiddler::where($column, $value)->count();
	}
	
	public function getOneRow(String $column, $value) 
	{
		return Fiddler::where($column, $value)->first();
	}
	
	public function create(array $data)
	{
		return Fiddler::create($data);
	}
	
	public function updateOrCreate(String $unique, $value, array $data)
	{
		return Fiddler::updateOrCreate(
			[$unique => $value],
			$data
		);
	}
	
	public function getMyList(String $column, $value) 
	{
		return Fiddler::where('user_id', $value)->orderBy('created_at', 'desc')->get();
	}
	
	public function getTotalCount()
	{
		return Fiddler::where('id', '>', 0)->count();
	}
	
	public function getFilteredCount($parameters)
	{
		$result = Fiddler::where('fiddler.id', '>', 0);
		$result = $result->leftjoin('users', 'users.id', '=', 'fiddler.user_id');
		$result = $result->where(function($query) use($parameters) {
			$query->where('users.name', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('fiddler.random_key', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('fiddler.user_id', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('users.user_id', 'like', '%' . $parameters['search']['value'] . '%');
		});
		
		$result = $result->count();
		return $result;
	}
	
	public function getList($parameters)
	{
		$result = Fiddler::where('fiddler.id', '>', 0);
		$result = $result->leftjoin('users', 'users.id', '=', 'fiddler.user_id');
		$result = $result->select(DB::raw(
										'
											fiddler.id, 
											if(users.user_id = "" or users.user_id is null, fiddler.user_id, users.user_id) as user_id, 
											users.name, 
											fiddler.random_key, 
											fiddler.updated_at
										'
										)
								);
		
		$result = $result->where(function($query) use($parameters) {
			$query->where('users.name', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('fiddler.random_key', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('fiddler.user_id', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('users.user_id', 'like', '%' . $parameters['search']['value'] . '%');
		});
		
		$result = $result->skip($parameters['skip'])->take($parameters['take'])->orderBy($parameters['order']['column'], $parameters['order']['sort'])->get();
		return $result;
	}
	
	function delete(int $id)
	{
		return Fiddler::where('id', $id)->delete();
	}
}