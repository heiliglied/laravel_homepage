<?php

namespace App\Services;

use App\Models\User;

class UserService
{
	static private $instance = null;
	
	static function getInstance() 
	{
		if(self::$instance == null) {
			self::$instance = new UserService();
		}
		
		return self::$instance;
	}
	
	function dupilcate(String $user_id) : String
	{
		if(User::where('user_id', $user_id)->count() > 0) {
			return "duplicate";
		} else {
			return "not_null";
		}
	}
	
	function createUser(array $data)
	{
		return User::create($data);
	}
	
	function userTotalCount()
	{
		return User::where('rank', '>', 0)->count();
	}
	
	function getUserFilteredCount(array $parameters)
	{
		$result = User::where('rank', '>', 0);
		
		$result = $result->where(function($query) use($parameters) {
			$query->where('user_id', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('name', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('email', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('contact', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('social_path', 'like', '%' . $parameters['search']['value'] . '%');
		});
		
		$result = $result->count();
		return $result;
	}
	
	function getUserList(array $parameters)
	{
		$result = User::where('users.rank', '>', 0);
		$result = $result->leftjoin('user_rank', 'user_rank.rank', '=', 'users.rank');
		$result = $result->select('id', 'user_rank.name as rank', 'user_id', 'users.name as name', 'email', 'contact', 'social_path', 'except', 'excepted_at');
		
		$result = $result->where(function($query) use($parameters) {
			$query->where('user_id', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('users.name', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('email', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('contact', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('social_path', 'like', '%' . $parameters['search']['value'] . '%');
		});
		
		$result = $result->skip($parameters['skip'])->take($parameters['take'])->orderBy($parameters['order']['column'], $parameters['order']['sort'])->get();
		return $result;
	}
	
	function getOneRow(String $column, $key)
	{
		return User::where($column, $key)->first();
	}
	
	function updateUser(String $column, $key, array $data)
	{
		return User::where($column, $key)->update($data);
	}
	
	function userDelete(int $id)
	{
		return User::where('id', $id)->delete();
	}
	
	function userExcept(int $id)
	{
		return User::where('id', $id)->update(
					[
						'except' => 'Y',
						'excepted_at' => \Carbon\Carbon::now()
					]
				);
	}
}