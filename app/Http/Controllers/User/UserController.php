<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct() {}
	
	protected function mypage(Request $request)
	{
		return view('user.mypage');
	}
	
	protected function update(Request $request)
	{
		$rules = [
			'name' => ['required', 'string', 'max:20'],
		];
		
		$data = [
			'name' => $request->name,
		];
		
		if($request->password_change == 'Y') {
			$rules['password'] = ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'];
			$data['password'] = Hash::make($request->password);
		}
		
		$request->validate($rules);
		
		try {
			$userService = UserService::getInstance();
			$userService->updateUser('user_id', $request->user_id, $data);
			return redirect()->back()->with('msg', '사용자 정보가 갱신되었습니다.');
		} catch(\Exception $e) {
			return redirect()->back()->with('msg', '에러가 발생하였습니다.');
		}
	}
}
