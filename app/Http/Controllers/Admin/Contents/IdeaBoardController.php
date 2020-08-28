<?php

namespace App\Http\Controllers\Admin\Contents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\IdeaBoardService;
use App\Services\FileService;

use Storage;

use App\Interfaces\AsideMenuInterface;

class IdeaBoardController extends Controller implements AsideMenuInterface 
{
	private $disk = 'local';
	
    public function __construct() {}
	
	public function activeMenuList(String $open_menu, String $active_menu) : array {
		return [
			'open' => $open_menu,
			'active' => $active_menu,
		];
	}
	
	protected function list(Request $request)
	{
		$menu_view = $this->activeMenuList('contents', 'ideaBoard');
		return view('admin.contents.board.idea.list', ['menu' => $menu_view]);
	}
	
	protected function view(Request $request)
	{
		$condition = [
			'table_name' => 'idea_board',
			'board_num' => $request->id
		];
		
		$ideaBoardService = IdeaBoardService::getInstance();
		$fileService = FileService::getInstance();
		$ideaBoard = $ideaBoardService->getOneRow('id', $request->id);
		$menu_view = $this->activeMenuList('contents', 'ideaBoard');
		
		if($ideaBoard == null) {
			abort(404);
		}
		
		$files = $fileService->getList($condition);
		
		$return = [
			'menu' => $menu_view,
			'ideaBoard' => $ideaBoard,
			'files' => $files,
			'condition' => $condition,
		];
		
		$menu_view = $this->activeMenuList('contents', 'ideaBoard');
		return view('admin.contents.board.idea.view', $return);
	}
	
	protected function download(Request $request)
	{
		$fileService = FileService::getInstance();
		$file = $fileService->getOneRow($request->fileId);
		
		return Storage::disk($this->disk)->download($file->renamed_name, $file->original_name);
	}
	
}
