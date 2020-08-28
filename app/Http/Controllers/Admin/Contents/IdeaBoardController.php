<?php

namespace App\Http\Controllers\Admin\Contents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Services\IdeaBoardService;
use App\Services\IdeaReplyService;
use App\Services\FileService;
use Storage;
use DB;
use App\Libs\FileLibrary;

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
	
	protected function modify(Request $request)	
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
		return view('admin.contents.board.idea.modify', $return);
	}
	
	protected function update(Request $request)
	{
		$valid_chk = $this->validator($request->all());

		if($valid_chk->fails()) {
			return back()->withErrors($valid_chk)->withInput();
		}
		
		$files = "";
		
		$datas = [
			'subject' => $request->subject,
			'contents' => $request->contents,
		];
		
		$fileService = FileService::getInstance();
		$uploaded_file = $fileService->getFileCount('idea_board', $request->id);
		$file_count = $uploaded_file;
		
		if(!empty($request->file())) {
			$file_count = count($request->file('files')) + $uploaded_file;
			if($file_count > 5) {
				return back()->withErrors(['msg' => '최대 업로드 갯수를 초과하였습니다.'])->withInput();
			}
			
			$file_chk = $this->fileValidator($request->file());
			if($file_chk->fails()) {
				return back()->withErrors($file_chk)->withInput();
			}
			$files = "Y";
		}
		
		if($file_count > 0) {
			$datas['files'] = 'Y';
		} else {
			$datas['files'] = 'N';
		}
		
		try {
			DB::beginTransaction();
			$ideaBoardService = IdeaBoardService::getInstance();
			$ideaBoardService->update('id', $request->id, $datas);
			
			if($files == 'Y') {
				$fileLibrary = new FileLibrary();
				$result = $fileLibrary->multiUpload($request->file(), 'ideaBoard/' . date('Ym'));
				
				if($result['success'] == false) {
					throw new \Exception('file upload fail');
				}
				
				$insert = [];
				foreach($result['data'] as $key => $value) {
					$fileData = [
						'table_name' => 'idea_board',
						'board_num' => $request->id,
						'original_name' => $value['original_name'],
						'renamed_name' => $value['renamed_name'],
						'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
						'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
					];
					
					array_push($insert, $fileData);
				}
				
				$fileService->insert($insert);
			}
			
			DB::commit();
			
			return redirect('/admin/contents/ideaBoard/list');
			
		} catch(\Exception $e) {
			DB::rollback();
			abort(500);
		}
	}
	
	protected function validator(array $data)
    {
        return Validator::make($data, [
            'subject' => ['required', 'string'],
            'contents' => ['required', 'string'],
        ]);
    }
	
	protected function fileValidator(array $data)
    {
        return Validator::make($data, [
            'files.*' => ['max:10240'],
        ], ['files.*.max' => '업로드 용량을 초과하였습니다.']);
    }
	
	protected function censor(Request $request)
	{
		try {
			$ideaBoardService = IdeaBoardService::getInstance();
			$ideaBoardService->censor('id', $request->id);
			return redirect('/admin/contents/ideaBoard/list');
		} catch(\Exception $e) {
			return back()->withErrors(['msg' => '검열에 실패하였습니다.']);
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
			return back()->withErrors(['msg' => '삭제에 실패하였습니다.']);
		}
		
		$fileLibrary = new FileLibrary();
		foreach($fileList as $fval) {
			$fileLibrary->deleteFile($fval);
		}
		
		return redirect('/admin/contents/ideaBoard/list');
	}
}
