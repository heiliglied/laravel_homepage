<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Interfaces\AsideMenuInterface;
use App\Traits\Settings;

class SiteSettingController extends Controller implements AsideMenuInterface 
{
	use Settings;
	
    public function __construct() {}
	
	public function activeMenuList(String $open_menu, String $active_menu) : array {
		return [
			'open' => $open_menu,
			'active' => $active_menu,
		];
	}
	
	protected function view(Request $request)
	{
		$settings = $this->getSettings();
		$menu_view = $this->activeMenuList('settings', 'site_setting');

		$return = [
			'setting' => $settings,
			'menu' => $menu_view
		];
		
		$menu_view = $this->activeMenuList('settings', 'site_setting');
		return view('admin.settings.site', $return);
	}
	
	protected function set(Request $request)
	{
		$datas = [
			'adminRankOrder' => $request->adminRankOrder,
			'userRankOrder' => $request->userRankOrder,
		];
		
		$this->setSettings($datas);
		
		return redirect()->back();
	}
}
