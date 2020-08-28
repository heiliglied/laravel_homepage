<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\UserRankService;
use App\Traits\Pagination;
use DB;

class UserRankController extends Controller
{
    use Pagination;
	
    public function __construct() 
	{
		
	}
	
	protected function getList(Request $request) 
	{
		$pageSize = 10;
		
		$userRankService = UserRankService::getInstance();
		$total_count = $userRankService->getTotalRecordCount();
		
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
			'lists' => $userRankService->getList((int)$request->page, $pageSize)
		];
		
		return $result;
	}
	
	protected function insert(Request $request)
	{
		$userRankService = UserRankService::getInstance();
		
		$datas = [
			'rank' => $request->input('rank'), 
			'name' => $request->input('name')
		];
		
		if($userRankService->getCount() < 1) {
			$datas['default'] = 'Y';
		}
		
		try {
			$userRankService->createRank($datas);
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
		$userRankService = UserRankService::getInstance();		
		$rank_data = $userRankService->getOneRow('rank', $request->rank);
		
		if($rank_data->default != '' || $rank_data->default != null) {
			return 'default';
		}
		
		try {
			$userRankService->deleteRank((int)$request->rank);
		} catch(\Exception $e) {
			return 'error';
		}
		
		return 'success';
	}
	
	protected function update(Request $request)
	{
		$userRankService = UserRankService::getInstance();
		
		try {
			$userRankService->updateRank((int)$request->rank, (string)$request->name);
		} catch(\Exception $e) {
			//return $e->getMessage();
			return 'error';
		}
		
		return 'success';
	}
	
	protected function setDefault(Request $request)
	{
		$userRankService = UserRankService::getInstance();
		
		DB::beginTransaction();
		
		try {
			$userRankService->advencedUpdate('rank', '>', '0', ['default' => '']);
			$userRankService->changeDefault($request->rank);
			DB::commit();
		} catch(\Exception $e) {
			DB::rollback();
			//return $e->getMessage();
			return 'error';
		}
		
		return 'success';
	}
}
