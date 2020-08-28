<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\AdminPermissionService;

class AdminPermissionController extends Controller
{
    public function __construct() {}
	
	protected function permissionList(Request $request)
	{
		$parameters = [
			'skip' => $request->start,
			'take' => $request->length,
			'order' => [
				'column' => $request->columns[$request->order[0]['column']]['data'],
				'sort' => $request->order[0]['dir'],
			],
			'search' => [
				'column' => '',
				'value' => $request->search['value'],
			],
		];
		$adminPermissionService = AdminPermissionService::getInstance();
		
		$return = [
			"recordsTotal" => $adminPermissionService->permissionTotalCount(),
			"recordsFiltered" => $adminPermissionService->getPermissionFilteredCount($parameters),
			"data" => $adminPermissionService->getPermissionList($parameters),
		];
		
		return $return;
	}
	
	protected function permissionDelete(Request $request)
	{
		$adminPermissionService = AdminPermissionService::getInstance();
		
		try {
			$adminPermissionService->permissionDelete((int)$request->id);
		} catch(\Exception $e) {
			return 'error';
		}
		
		return 'success';
	}
}
