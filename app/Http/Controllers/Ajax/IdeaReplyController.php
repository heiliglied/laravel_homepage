<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\IdeaReplyService;
use App\Traits\Pagination;

use Auth;
use App\Events\BoardNewEvents;

class IdeaReplyController extends Controller
{
	use Pagination;
	
    public function __construct() {}
	
	protected function create(Request $request)
	{
		$writer = "";
		
		if(Auth::getDefaultDriver() == 'web') {
			$writer = 'user';
		} else {
			$writer = Auth::getDefaultDriver();
		}
		
		if($request->id == '') {
			return 'id_null';
		}
		
		if($request->contents == '') {
			return 'content_null';
		}
		
		$datas = [
			'writer' => $writer,
			'user_id' => Auth::user()->id,
			'table_name' => 'idea_board',
			'board_id' => $request->id,
			'reply' => $request->contents,
			'parent_id' => 0,
			'depth' => 0,
		];
		
		try {
			$ideaReplyService = IdeaReplyService::getInstance();
			$ideaReplyService->create($datas);
			
			//이벤트 호출.
			//event(new BoardNewEvents(['type' => 'reply', 'num' => $request->id]));
			//자신을 제외하고 이벤트 발생.
			broadcast(new BoardNewEvents(['type' => 'reply', 'writer' => Auth::user()->name, 'subject' => '']))->toOthers();
			
			return 'success';
		} catch(\Exception $e) {			
			return 'error';
		}
	}
	
	protected function getList(Request $request)
	{
		$page = $request->page ? $request->page : 1;
		
		$pageSize = 10;
		$showPages = 10;
		
		$ideaReplyService = IdeaReplyService::getInstance();
		$filteredCount = $ideaReplyService->filteredCount('idea_board', $request->id);
				
		$skip = ($page - 1) * $pageSize;
		
		$parameters = [
			'skip' => true,
			'currentPage' => (int)$page,
			'pageSize' => $pageSize,
			'showPages' => $showPages,
			'totalCount' => $filteredCount,
		];
		
		$pagination = $this->showPagination($parameters);
		
		$data = $ideaReplyService->getList($skip, $pageSize, 'idea_board', $request->id);

		$return = [
			'totalCount' => $filteredCount,
			'pagination' => $pagination,
			'lists' => $data,
			'authId' => Auth::user()->id,
		];
		
		return $return;
	}
	
	protected function deleteReply(Request $request)
	{
		try {
			$ideaReplyService = IdeaReplyService::getInstance();
			$ideaReplyService->delete('id', $request->id);
			return 'success';
		} catch(\Exception $e) {
			return 'error';
		}
	}
	
	protected function censor(Request $request)
	{
		try {
			$ideaReplyService = IdeaReplyService::getInstance();
			$ideaReplyService->censor('id', $request->id);
			return 'success';
		} catch(\Exception $e) {
			return 'error';
		}
	}
}
