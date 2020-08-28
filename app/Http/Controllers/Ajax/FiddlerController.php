<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\FiddlerService;

class FiddlerController extends Controller
{
    public function __construct() {}
	
	protected function fiddlerList(Request $request)
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
		
		$fiddlerService = FiddlerService::getInstance();
		
		$return = [
			"recordsTotal" => $fiddlerService->getTotalCount(),
			"recordsFiltered" => $fiddlerService->getFilteredCount($parameters),
			"data" => $fiddlerService->getList($parameters),
		];
		
		return $return;
	}
	
	protected function delete(Request $request)
	{
		$fiddlerService = FiddlerService::getInstance();
		
		try {
			$fiddlerService->delete((int)$request->id);
		} catch(\Exception $e) {
			return 'error';
		}
		
		return 'success';
	}
}
