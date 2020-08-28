<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\IdeaBoardService;
use App\Services\IdeaReplyService;
use App\Services\FileService;
use Storage;
use App\Libs\FileLibrary;
use DB;
use App\Traits\Pagination;

class IdeaBoardController extends Controller
{
	use Pagination;
	
	private $disk = 'local';
	
    public function __construct() {}
	
	protected function deleteFile(Request $request)
	{
		$fileService = FileService::getInstance();
		$file = $fileService->getOneRow($request->id);
		try {
			DB::beginTransaction();
			$fileService->deleteRow('id', (int)$request->id);
			$fileLibrary = new FileLibrary();
			$fileLibrary->deleteFile($file->renamed_name);
			DB::commit();
			return 'success';
		} catch(\Exception $e) {
			//return $e->getMessage();
			return 'fail';
		}
	}
	
	protected function getList(Request $request)
	{
		$page = $request->page ? $request->page : 1;
		
		$pageSize = 10;
		$showPages = 10;
		
		$ideaBoardService = IdeaBoardService::getInstance();
		$totalCount = $ideaBoardService->total();
		$filteredCount = $ideaBoardService->filteredCount($request->search);
		
		$skip = ($page - 1) * $pageSize;
		
		$parameters = [
			'skip' => true,
			'currentPage' => (int)$page,
			'pageSize' => $pageSize,
			'showPages' => $showPages,
			'totalCount' => $filteredCount,
		];
		
		$pagination = $this->showPagination($parameters);
		
		$data = $ideaBoardService->getList($skip, $pageSize, $request->search);

		$return = [
			'totalCount' => $totalCount,
			'filteredCount' => $filteredCount,
			'pagination' => $pagination,
			'lists' => $data,
		];
		
		return $return;
	}
	
	protected function getListDT(Request $request)
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
		
		$ideaBoardService = IdeaBoardService::getInstance();
		$data = $ideaBoardService->getListDT($parameters);
		if($data[0]->id == null) {
			$data = [];
		}
		
		$return = [
			"recordsTotal" => $ideaBoardService->total(),
			"recordsFiltered" => $ideaBoardService->filteredCount($request->search['value']),
			"data" => $data,
		];
		
		return $return;
	}
	
	protected function censor(Request $request)
	{
		try {
			$ideaBoardService = IdeaBoardService::getInstance();
			$ideaBoardService->censor('id', $request->id);
			return 'success';
		} catch(\Exception $e) {
			return 'error';
		}
	}
	
	protected function delete(Request $request)
	{
		$fileList = [];
		
		try {
			DB::beginTransaction();
			$ideaBoardService = IdeaBoardService::getInstance();
			$ideaReplyService = IdeaReplyService::getInstance();
			$fileService = FileService::getInstance();
			$ideaBoardService->delete(['id' => $request->id]);
			$ideaReplyService->conditionDelete(['board_id' => $request->id, 'table_name' => 'idea_board']);
			$file_lists = $fileService->getList(['board_num' => $request->id, 'table_name' => 'idea_board']);
			foreach($file_lists as $value) {
				array_push($fileList, $value->renamed_name);
			}
			$fileService->deleteList(['board_num' => $request->id, 'table_name' => 'idea_board']);
			DB::commit();
		} catch(\Exception $e) {
			DB::rollback();
			return 'error';
		}
		
		$fileLibrary = new FileLibrary();
		foreach($fileList as $fval) {
			$fileLibrary->deleteFile($fval);
		}
		
		return 'success';
	}
}
