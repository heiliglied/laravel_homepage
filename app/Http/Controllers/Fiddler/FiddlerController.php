<?php

namespace App\Http\Controllers\Fiddler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\FiddlerService;
use Illuminate\Support\Str;
use Auth;

class FiddlerController extends Controller
{
    public function __construct() {}
	
	protected function index(Request $request)
	{
		$fiddlerService = FiddlerService::getInstance();
		$data = $fiddlerService->getOneRow('random_key', $request->random_key);
		
		$result = [
			'random_key' => '',
			'html_type' => '',
			'html' => '',
			'css_type' => '',
			'css' => '',
			'js_type' => '',
			'script' => '',
		];
		
		if($data != null) {
			$result = [
				'random_key' => $data->random_key,
				'html_type' => $data->html_type,
				'html' => $data->html,
				'css_type' => $data->css_type,
				'css' => $data->css,
				'js_type' => $data->js_type,
				'script' => $data->script,
			];
		}
		
		return view('fiddler.zzapfiddler', $result);
	}
	
	protected function save(Request $request)
	{
		$key = $request->random_key;
		$user_id = Auth::check() ? Auth::user()->id : session_id();
		$fiddlerService = FiddlerService::getInstance();
		
		if($key == '') {
			while(true) {
				$key = Str::random(12);
				if($fiddlerService->getOneRow('random_key', $key) == null) {
					break;
				}
			}
		}
		
		$data = [
			'user_id' => $user_id,
			'random_key' => $key,
			'html' => $request->html_input,
			'css_type' => $request->css_type,
			'css' => $request->css_input,
			'js_type' => $request->js_type,
			'script' => $request->js_input,
		];
		
		try {
			if($fiddlerService->getCount('user_id', $user_id) < 10) {
				$fiddlerService->updateOrCreate('random_key', $key, $data);	
			} else {
				return back()->with('msg', '최대 10개까지 저장할 수 있습니다.');
			}
			
		} catch(\Exception $e) {
			abort(500);
		}
		
		return redirect('/zzapfiddler/' . $key);
	}
	
	protected function show(Request $request)
	{
		return view('fiddler.iframe');
	}
	
	protected function getList(Request $request)
	{
		$fiddlerService = FiddlerService::getInstance();
		return $fiddlerService->getMyList('user_id', Auth::user()->id);
	}
	
	protected function delete(Request $request)
	{
		$fiddlerService = FiddlerService::getInstance();
		$data = $fiddlerService->getOneRow('random_key', $request->random_key);
		if($data->user_id == Auth::user()->id) {
			$fiddlerService->delete($data->id);
			return redirect('/zzapfiddler');
		} else {
			return back()->with('msg', '본인의 데이터만 삭제할 수 있습니다.');
		}
	}
}
