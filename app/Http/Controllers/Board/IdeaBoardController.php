<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Services\IdeaBoardService;
use App\Services\FileService;
use App\Services\IdeaReplyService;
use Auth;
use DB;
use Storage;
use App\Libs\FileLibrary;

use App\Events\BoardNewEvents;

class IdeaBoardController extends Controller
{
	private $disk = 'local';
	
    public function __construct() {}
	
	protected function list(Request $request)
	{
		return view('board.idea.list');
	}

	protected function write(Request $request)
	{
		return view('board.idea.write');
	}
	
	protected function create(Request $request)
	{
		$valid_chk = $this->validator($request->all());

		if($valid_chk->fails()) {
			return back()->withErrors($valid_chk)->withInput();
		}
		
		$files = "";
		
		if(!empty($request->file())) {
			if(count($request->file('files')) > 5) {
				return back()->withErrors(['msg' => '최대 업로드 갯수를 초과하였습니다.'])->withInput();
			}
			
			$file_chk = $this->fileValidator($request->file());
			if($file_chk->fails()) {
				return back()->withErrors($file_chk)->withInput();
			}
			$files = "Y";
		}
		
		try {
			DB::beginTransaction();
			$ideaBoardService = IdeaBoardService::getInstance();
			
			$last_id = $ideaBoardService->create(
				[
					'writer' => 'user',
					'user_id' => Auth::user()->id,
					'subject' => $request->subject,
					'contents' => $request->contents,
					'content_type' => 'idea_board',
					'files' => $files
				]
			);
			
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
						'board_num' => $last_id->id,
						'original_name' => $value['original_name'],
						'renamed_name' => $value['renamed_name'],
						'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
						'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
					];
					
					array_push($insert, $fileData);
				}
				
				$fileService = FileService::getInstance();
				$fileService->insert($insert);
			}
			
			DB::commit();
			
			//이벤트 호출.
			event(new BoardNewEvents(['type' => 'board', 'num' => $last_id->id]));
			//자신을 제외하고 이벤트 발생.
			//broadcast(new BoardNewEvents(['type' => 'board', 'num' => $last_id->id]))->toOthers();
			
			return redirect('/ideaBoard/list');
			
		} catch(\Exception $e) {
			DB::rollback();
			abort(500);
		}
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
		
		if(($ideaBoard->user_id != Auth::user()->id) && ($ideaBoard->writer != 'user')) {
			return back()->with('msg', '글을 수정할 권한이 없습니다.');
		}
		
		$files = $fileService->getList($condition);
		
		$return = [
			'board' => $ideaBoard,
			'files' => $files,
		];
		
		return view('board.idea.modify', $return);
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
			
			return redirect('/ideaBoard/list');
			
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
	
	protected function download(Request $request)
	{
		$fileService = FileService::getInstance();
		$file = $fileService->getOneRow($request->fileId);
		
		return Storage::disk($this->disk)->download($file->renamed_name, $file->original_name);
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
		
		if($ideaBoard == null) {
			abort(404);
		}
		
		$files = $fileService->getList($condition);
		
		$return = [
			'ideaBoard' => $ideaBoard,
			'files' => $files,
			'condition' => $condition,
		];
		
		return view('board.idea.view', $return);
	}
	
	protected function delete(Request $request)
	{
		$condition = [
			'table_name' => 'idea_board',
			'board_num' => $request->id
		];
		
		$ideaBoardService = IdeaBoardService::getInstance();
		$fileService = FileService::getInstance();
		$ideaReplyService = IdeaReplyService::getInstance();
		//$ideaBoard = $ideaBoardService->getOneRow('id', $request->id);
		$files = $fileService->getList($condition);
		$replies = $ideaReplyService->filteredCount('idea_board', $request->id);
		
		if($replies > 0) {
			try {
				DB::beginTransaction();
				$fileService->deleteList($condition);
				$ideaBoardService->update('id', $request->id, ['subject' => '삭제된 글입니다.', 'contents' => '', 'censorship' => 'N', 'deleted_at' => \Carbon\Carbon::now()]);
				DB::commit();
			} catch(\Exception $e) {
				abort(500);
			}
			
			$fileLibrary = new FileLibrary();
			foreach($files as $value) {
				$fileLibrary->deleteFile($value->renamed_name);
			}
		} else {
			try {
				DB::beginTransaction();
				$fileService->deleteList($condition);
				$ideaBoardService->delete(['id' => $request->id]);
				DB::commit();
			} catch(\Exception $e) {
				abort(500);
			}
			
			$fileLibrary = new FileLibrary();
			foreach($files as $value) {
				$fileLibrary->deleteFile($value->renamed_name);
			}
		}
		return redirect('/ideaBoard/list');
	}
}
	