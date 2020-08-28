<?php

namespace App\Http\Controllers\Admin\Contents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Interfaces\AsideMenuInterface;

class FiddlerController extends Controller implements AsideMenuInterface 
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
		$menu_view = $this->activeMenuList('contents', 'zzapfiddler');
		return view('admin.contents.fiddler.list', ['menu' => $menu_view]);
	}
}
