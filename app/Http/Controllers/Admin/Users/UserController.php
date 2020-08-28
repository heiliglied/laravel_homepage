<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Interfaces\AsideMenuInterface;
use App\Services\UserRankService;
use App\Services\UserService;

class UserController extends Controller implements AsideMenuInterface 
{
    public function __construct() {}
	
	public function activeMenuList(String $open_menu, String $active_menu) : array {
		return [
			'open' => $open_menu,
			'active' => $active_menu,
		];
	}
	
	protected function list(Request $request)
	{
		$menu_view = $this->activeMenuList('users', 'users');
		return view('admin.users.users.list', ['menu' => $menu_view]);
	}
	
	protected function userWrite(Request $request)
	{
		$userRankService = UserRankService::getInstance();
		if($userRankService->getTotalRecordCount() < 1) {
			return redirect('admin/users/rank')->with('rank_null', 'true');
		} else {
			$return = [
				'menu' => $this->activeMenuList('users', 'users'),
				'rank' => $userRankService->get(),
			];
			
			return view('admin.users.users.write', $return);
		}
	}
	
	protected function userCreate(Request $request)
	{
		$validation = $this->validator($request);
		
		if($validation->fails()) {
			return redirect()->back()->withErrors($validation)->withInput();
		} else {
			$userService = UserService::getInstance();
			try{ 
				$userService->createUser([
					'user_id' => $request->user_id,
					'password' => bcrypt($request->password),
					'rank' => $request->rank,
					'name' => $request->name,
					'email' => $request->email,
					'contact' => $request->contact,
					'social_path' => '관리자페이지',
				]);
			} catch(\Exception $e) {
				abort(500);
			}
			return redirect('/admin/users/users');
		}
	}
	
	protected function validator(Request $request, array $rules = null)
	{
		if($rules == null) {
			$rules = [
				'user_id' => 'unique:users|required|max:20|min:5',
				'password' => 'required|confirmed|min:6',
				'rank' => 'required',
				'email' => 'required',
				'name' => 'required'
			];
		}
		
		return Validator::make($request->all(), $rules);
	}
	
	protected function userModify(Request $request)
	{
		$userService = UserService::getInstance();
		$userRankService = UserRankService::getInstance();
		$return = [
			'menu' => $this->activeMenuList('users', 'users'),
			'user' => $userService->getOneRow('id', $request->id),
			'rank' => $userRankService->getList(1, 1000),
		];
		
		return view('admin.users.users.modify', $return);
	}
	
	protected function userUpdate(Request $request)
	{
		$datas = [
			'rank' => $request->rank,
			'email' => $request->email,
			'name' => $request->name,
			'contact' => $request->contact,
			'except' => $request->except,
		];
		
		$rules = [
			'email' => 'required|email',
			'name' => 'required',
		];
		
		if($request->changePassword == 'Y') {
			$rules['password'] = 'required|confirmed|min:6';
			$datas['password'] = bcrypt($request->password);
		}
		
		$validation = $this->validator($request, $rules);
		
		if($validation->fails()) {
			return redirect()->back()->withErrors($validation)->withInput();
		} else {
			$userService = UserService::getInstance();
			$user_data = $userService->getOneRow('id', $request->id);
			
			if($user_data->except != $request->except) {
				$datas['excepted_at'] = \Carbon\Carbon::now();
			}
			
			try{
				$userService->updateUser('id', $request->id, $datas);
			} catch(\Exception $e) {
				abort(500);
			}
			return redirect('/admin/users/users');
		}
	}
	
	protected function userDelete(Request $request)
	{
		$userService = UserService::getInstance();
		try {
			$userService->userDelete((int)$request->id);
		} catch(\Exception $e) {
			abort(500);
		}
		
		return redirect('/admin/users/users');
	}
}
