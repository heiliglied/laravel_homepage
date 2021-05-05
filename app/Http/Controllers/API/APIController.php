<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Libs\JWTLibrary;

class APIController extends Controller
{
	private $jwt;
	
    public function __construct()
	{
		$this->jwt = new JWTLibrary();
	}
	
	protected function login(Request $request)
	{
		$check = $this->jwt->headerCheck($request->header(), ['Content-type' => 'application/json']);
		if($check['result'] == 'error') {
			return $this->jsonResult($check['code'], $check['result'], $check['data'], $check['message']);
		}
		
		$time = time();
		$expire = $time + $this->jwt->expired;
		
		$data = [
			'iss' => 'hungrysorrow',
			'sub' => 1,
			'aud' => 1,
			'exp' => $expire,
			'nbf' => $time,
			'iat' => $time,
			'jti' => uniqid(),
		];
		
		$encoded = $this->jwt->encode($data);
		
		$result = [
			'token' => $encoded,
			'token_type' => $this->jwt->authType,
			'expired_in' => $this->jwt->expired,
			'refresh_token' => '',
		];
		
		return $this->jsonResult(200, 'success', $result, '정상적으로 조회되었습니다.');
	}
	
	private function jsonResult(int $httpcode, String $result, array $datas, String $message)
	{
		return response()->json(['result' => $result, 'datas' => $datas, 'message' => $message], $httpcode);
	}
}
