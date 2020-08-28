<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Services\AdminService;
use App\Models\AdminRank;

class RegisterController extends Controller
{
    public function __construct() 
	{
		
	}
	
	protected function regist(Request $request)
	{
		$adminService = AdminService::getInstance();
		if($adminService->adminTotalCount() < 1) {
			return view('admin.auth.regist');
		} else {
			return redirect('/admin/login');
		}
	}
	
	protected function signUp(Request $request)
	{
		$validCheck = $this->validator($request);
		
		if($validCheck->fails()) {
			return redirect('/admin/regist')->withErrors($validCheck)->withInput();
		}
		
		//관리자 초기 아이디 생성.
		try {
			if(AdminRank::where('rank', 0)->count() < 1) {
				AdminRank::insert(
					[
						'rank' => 0,
						'name' => '최고관리자'
					]
				);
			}
			
			$adminService = AdminService::getInstance();
			$adminService->createAuth(
				[
					'user_id' => $request->user_id,
					'password' => bcrypt($request->password),
					'rank' => 0, 
					'name' => '사이트관리자',
				]
			);
			
		} catch(\Exception $e) {
			//print_r($e->getMessage());
			//exit;
			abort(500);
		}
		
		return redirect('/admin/login');
	}
	
	private function validator(Request $request) 
	{
		$rules = [
			'user_id' => 'required|min:5|max:20|unique:admin',
			'password' => 'required|min:6|max:20|confirmed',
		];
		
		$validator = Validator::make($request->all(), $rules);
		
		return $validator;
	}
}
