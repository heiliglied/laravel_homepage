<?php

namespace App\Libs;

//composer require firebase/php-jwt
use \Firebase\JWT\JWT;
use \Firebase\JWT\BeforeValidException;
use \Firebase\JWT\ExpiredException;
use \Firebase\JWT\SignatureInvalidException;

class JWTLibrary
{
	private $salt = "abcd"; //salt key;
	public $authType = "Bearer";
	public $expired = 3600;
	private $algorithm = "HS256";
	private $leeway = 30;
	
	public function __construct()
	{
		
	}
	
	//header 검증. 해당 헤더의 값이 있는지 없는지 key-value 쌍으로 검사시킴.
	//* $checklist = ['key' => 'value'];
	public function headerCheck(array $headers, array $checklist)
	{
		$result = "";
		$code = "";
		$message = "";
		
		$checked = [];

		foreach($checklist as $key => $value) {			
			if(!isset($headers[strtolower($key)])) {
				array_push($checked, $key);
			} else {
				if($headers[strtolower($key)][0] != strtolower($value)) {
					array_push($checked, $key);
				}
			}
		}
		
		if(count($checked) > 0) {
			$result = "error";
			$code = 401;
			$message = "해당 헤더값이 존재하지 않습니다.";
		} else {
			$result = "ok";
			$code = 200;
			$message = "정상적인 데이터입니다.";
		}
		
		return [
			'result' => $result,
			'code' => $code,
			'data' => $checked,
			'message' => $message,
		];
	}
		
	//encode header, signature는 자동으로 생성됨.
	public function encode(array $payload)
	{
		/*
		$header = $this->urlsafeB64Encode(json_encode(["typ" => "JWT","alg" => $this->algorithm]));
		$body = $this->urlsafeB64Encode(json_encode($payload));
		//hmac 알고리즘은 선택한 알고리즘에 따라 변경됨.
		$signature = $this->urlsafeB64Encode(hash_hmac("sha256", $header . "." . $body, $this->salt));
		$jwt = $header . "." . $body . "." . $signature;
		*/
		
		//위 작업을 자동으로 처리해 줌.
		return JWT::encode($payload, $this->salt, $this->algorithm);
	}
	
	//decode
	public function decode(String $authenization)
	{
		$token = $this->authenization($authenization);
		return JWT::decode($token, $this->salt, $this->algorithm)
	}
	
	//expire 연장
	
	
	//authenticate 검증.
	private function authenization(String $authenization)
	{
		$auth = explode(" ", $authenization);
		
		if(count($auth) != 2) {
			throw new BeforeValidException('인증값이 잘못 전달되었습니다.');
		}
		
		$token_type = $auth[0];
		$token = $auth[1];
		
		if(strtolower($token_type) != strtolower($this->authType)) {
			throw new BeforeValidException('인증 타입이 일치하지 않습니다.');
		}
		
		return $token;
	}
	
	//로그아웃 기능 블랙리스트 테이블에 저장 후, 미들웨어에서 검증하도록 추가.
	private function checkToken(String $token)
	{
		try {
			DB::table('jwt_blacklist')->insert(
				[
					'token' => $token,
					'created_at' => date('Y-m-d H:i:s'),
				]
			);
			return 'success';
		} catch(\Exception $e) {
			return 'error';
		}
		
	}
	
	//참고....
	/*
	private function urlsafeB64Encode(String $input)
	{
		return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
	}
	
	private static function urlsafeB64Decode(String $input)
    {
        $remainder = \strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= \str_repeat('=', $padlen);
        }
        return \base64_decode(\strtr($input, '-_', '+/'));
    }
	*/
}
