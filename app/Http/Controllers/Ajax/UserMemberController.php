<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\UserService;

class UserMemberController extends Controller
{
    public function __construct() {}
	
	protected function idCheck(Request $request)
	{
		if($request->user_id == '') {
			return 'id_null';
		}
		
		$userService = UserService::getInstance();
		if($userService->dupilcate($request->user_id) == 'duplicate') {
			return "duplicate";
		} else {
			return "enable";
		}
	}
	
	protected function userList(Request $request)
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
		$userService = UserService::getInstance();
		
		$return = [
			"recordsTotal" => $userService->userTotalCount(),
			"recordsFiltered" => $userService->getUserFilteredCount($parameters),
			"data" => $userService->getUserList($parameters),
		];
		
		return $return;
	}
	
	protected function userExcept(Request $request)
	{
		$userService = UserService::getInstance();
		
		try {
			$userService->userExcept((int)$request->id);
		} catch(\Exception $e) {
			return 'error';
		}
		
		return 'success';
	}
}
