<?php

namespace App\Http\Controllers\Admin\Testing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Interfaces\AsideMenuInterface;

use App\Models\App\CastTest;

class TestController extends Controller implements AsideMenuInterface 
{
    public function __construct() {}
	
	public function activeMenuList(String $open_menu, String $active_menu) : array {
		return [
			'open' => $open_menu,
			'active' => $active_menu,
		];
	}
	
	protected function cast(Request $request)
	{
		$menu_view = $this->activeMenuList('test', 'cast');
		return view('admin.test.cast', ['menu' => $menu_view]);
	}
	
	protected function castList(Request $request)
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
		
		$total = CastTest::count();
		$filterdCount = CastTest::where(function($query) use($parameters){
			$query->where('normal', 'like', '%' . $parameters['search']['value'] . '%')->orWhere('casted', 'like', '%' . $parameters['search']['value'] . '%');
		})->count();
		$data = CastTest::where(function($query) use($parameters){
			$query->where('normal', 'like', '%' . $parameters['search']['value'] . '%')->orWhere('casted', 'like', '%' . $parameters['search']['value'] . '%');
		})->skip($parameters['skip'])->take($parameters['take'])->orderBy($parameters['order']['column'], $parameters['order']['sort'])->get();
		
		if(empty($data)) {
			$data = [];
		}

		$return = [
			"recordsTotal" => $total,
			"recordsFiltered" => $filterdCount,
			"data" => $data,
		];
		
		return $return;
	}	
	
	protected function castWrite(Request $request)
	{
		$menu_view = $this->activeMenuList('test', 'cast');
		return view('admin.test.castWrite', ['menu' => $menu_view]);
	}
	
	protected function castCreate(Request $request)
	{
		$data = [
			'normal' => $request->normal,
			'casted' => $request->casted,
		];
		
		CastTest::create($data);
		
		return redirect('/admin/test/cast');
	}
}
