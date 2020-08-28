<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\AdminService;

class AdminMemberController extends Controller
{
    public function __construct() {}
	
	protected function idCheck(Request $request)
	{
		if($request->user_id == '') {
			return 'id_null';
		}
		
		$adminService = AdminService::getInstance();
		if($adminService->dupilcate($request->user_id) == 'duplicate') {
			return "duplicate";
		} else {
			return "enable";
		}
	}
	
	protected function adminList(Request $request)
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
		$adminService = AdminService::getInstance();
		
		$return = [
			"recordsTotal" => $adminService->adminTotalCount(),
			"recordsFiltered" => $adminService->getAdminFilteredCount($parameters),
			"data" => $adminService->getAdminList($parameters),
		];
		
		return $return;
	}
	
	protected function adminDelete(Request $request)
	{
		$adminService = AdminService::getInstance();
		
		try {
			$adminService->adminDelete((int)$request->id);
		} catch(\Exception $e) {
			return 'error';
		}
		
		return 'success';
	}
}
