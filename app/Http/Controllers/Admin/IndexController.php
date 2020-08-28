<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//메뉴 active 상태 표기를 위해 인터페이스 형태로 강제로 구현시킴.
use App\Interfaces\AsideMenuInterface;

class IndexController extends Controller implements AsideMenuInterface 
{
	public function activeMenuList(String $open_menu, String $active_menu) : array {
		return [
			'open' => $open_menu,
			'active' => $active_menu,
		];
	}
	
    public function __construct() {}
	
	protected function index(Request $request) {
		
		$menu_view = $this->activeMenuList('dashboard', '');
		
		return view('admin.index', ['menu' => $menu_view]);
	}
}
