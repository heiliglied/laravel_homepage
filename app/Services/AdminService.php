<?php

namespace App\Services;

use App\Admin;

class AdminService
{
	static private $instance = null;
	
	static function getInstance() 
	{
		if(self::$instance == null) {
			self::$instance = new AdminService();
		}
		
		return self::$instance;
	}
	
	function adminTotalCount() 
	{
		return Admin::where('rank', '>', 0)->count();
	}
	
	function createAuth(array $data) 
	{
		return Admin::create($data);
	}
	
	function dupilcate(String $user_id) : String
	{
		if(Admin::where('user_id', $user_id)->count() > 0) {
			return "duplicate";
		} else {
			return "not_null";
		}
	}
	
	function getAdminFilteredCount(array $parameters)
	{
		$result = Admin::where('rank', '>', 0);
		
		$result = $result->where(function($query) use($parameters) {
			$query->where('user_id', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('name', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('email', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('contact', 'like', '%' . $parameters['search']['value'] . '%');
		});
		
		$result = $result->count();
		return $result;
	}
	
	function getAdminList(array $parameters)
	{
		$result = Admin::where('admin.rank', '>', 0);
		$result = $result->leftjoin('admin_rank', 'admin_rank.rank', '=', 'admin.rank');
		$result = $result->select('id', 'admin_rank.name as rank', 'user_id', 'admin.name as name', 'email', 'contact');
		
		$result = $result->where(function($query) use($parameters) {
			$query->where('user_id', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('admin.name', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('email', 'like', '%' . $parameters['search']['value'] . '%')
					->orWhere('contact', 'like', '%' . $parameters['search']['value'] . '%');
		});
		
		$result = $result->skip($parameters['skip'])->take($parameters['take'])->orderBy($parameters['order']['column'], $parameters['order']['sort'])->get();
		return $result;
	}
	
	function adminDelete(int $id)
	{
		return Admin::where('id', $id)->delete();
	}
	
	function getOneRow(String $column, $key)
	{
		return Admin::where($column, $key)->first();
	}
	
	function updateAdmin(String $column, $key, array $data)
	{
		return Admin::where($column, $key)->update($data);
	}
}