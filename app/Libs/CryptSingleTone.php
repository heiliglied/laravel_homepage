<?php

namespace App\Libs;

class CryptSingleTone
{
	static private $key = '';
	static private $chiper = "AES-256-CBC";
	static private $option = true;
	static private $iv = '';
	
	static private $instance = null;
	
	static function getInstance(array $parameters = null) 
	{
		if(self::$instance == null) {
			self::$instance = new CryptSingleTone($parameters);
		}
		
		return self::$instance;
	}
	
	public function __construct(array $parameters = null)
	{
		self::$key = isset($parameters['key']) ? $parameters['key'] : env('CRYPT_KEY', 'abcdef123456');
		self::$chiper = isset($parameters['chiper']) ? $parameters['chiper'] : self::$chiper;
		self::$option = isset($parameters['option']) ? $parameters['option'] : self::$option;
		self::$iv = isset($parameters['iv']) ? $parameters['iv'] : str_repeat(chr(0), 16);
	}
	
	public function encrypt(String $plaintext)
	{
		return base64_encode(openssl_encrypt($plaintext, self::$chiper, self::$key, self::$option, self::$iv));
	}
	
	public function decrypt(String $encrypted)
	{
		return openssl_decrypt(base64_decode($encrypted), self::$chiper, self::$key, self::$option, self::$iv);
	}
}