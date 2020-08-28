<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\AdminRankService;
use App\Traits\Pagination;

class AdminRankController extends Controller
{
	use Pagination;
	
    public function __construct() 
	{
		
	}
	
	protected function getList(Request $request) 
	{
		$pageSize = 10;
		
		$adminRankService = AdminRankService::getInstance();
		$total_count = $adminRankService->getTotalRecordCount();
		
		$parameters = [
			'skip' => true,
			'currentPage' => $request->page,
			'pageSize' => $pageSize,
			'showPages' => 5,
			'totalCount' => $total_count
		];
		
		$pagination = $this->showPagination($parameters);
		
		$result = [
			'pagination' => $pagination,
			'lists' => $adminRankService->getList((int)$request->page, $pageSize)
		];
		
		return $result;
	}
	
	protected function insert(Request $request)
	{
		$adminRankService = AdminRankService::getInstance();
		
		try {
			$adminRankService->createRank(['rank' => $request->input('rank'), 'name' => $request->input('name')]);
		} catch(\Exception $e) {
			if(stripos($e->getMessage(), 'Duplicate') !== false) {
				return 'duplicate';
			} else {
				return 'error';
			}
		}
		
		return 'success';
	}
	
	protected function delete(Request $request)
	{
		$adminRankService = AdminRankService::getInstance();
		
		try {
			$adminRankService->deleteRank((int)$request->rank);
		} catch(\Exception $e) {
			return 'error';
		}
		
		return 'success';
	}
	
	protected function update(Request $request)
	{
		$adminRankService = AdminRankService::getInstance();
		
		try {
			$adminRankService->updateRank((int)$request->rank, (string)$request->name);
		} catch(\Exception $e) {
			return $e->getMessage();
			return 'error';
		}
		
		return 'success';
	}
}
