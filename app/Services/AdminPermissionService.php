<?php
namespace App\Services;

use App\Models\Admin\AdminPermission;

class AdminPermissionService
{
	static private $instance = null;
	
	static function getInstance() 
	{
		if(self::$instance == null) {
			self::$instance = new AdminPermissionService();
		}
		
		return self::$instance;
	}
	
	function permissionCreate(array $datas)
	{
		return AdminPermission::create($datas);
	}
	
	function permissionTotalCount() 
	{
		return AdminPermission::count();
	}
	
	function getPermissionFilteredCount(array $parameters)
	{	
		return AdminPermission::where('uri', 'like', '%' . $parameters['search']['value'] . '%')->count();
	}
	
	function getPermissionList(array $parameters)
	{
		$result = AdminPermission::where('uri', 'like', '%' . $parameters['search']['value'] . '%');
		$result = $result->skip($parameters['skip'])->take($parameters['take'])->orderBy($parameters['order']['column'], $parameters['order']['sort'])->get();
		return $result;
	}
	
	function permissionDelete(int $id)
	{
		return AdminPermission::where('id', $id)->delete();
	}
	
	function getOneRow(String $column, $key)
	{
		return AdminPermission::where($column, $key)->first();
	}
	
	function permissionUpdate(String $column, $key, array $datas)
	{
		return AdminPermission::where($column, $key)->update($datas);
	}
}