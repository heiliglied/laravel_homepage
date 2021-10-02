<?php

namespace App\Libs;

class Cryption
{
	private $key = '';
	private $chiper = "AES-256-CBC";
	private $option = true;
	private $iv = '';
	
	public function __construct(array $parameters = null)
	{
		$this->key = isset($parameters['key']) ? $parameters['key'] : env('CRYPT_KEY', 'abcdef123456');
		$this->chiper = isset($parameters['chiper']) ? $parameters['chiper'] : $this->chiper;
		$this->option = isset($parameters['option']) ? $parameters['option'] : $this->option;
		$this->iv = isset($parameters['iv']) ? $parameters['iv'] : str_repeat(chr(0), 16);
	}
	
	public function encrypt(String $plaintext)
	{
		return base64_encode(openssl_encrypt($plaintext, $this->chiper, $this->key, $this->option, $this->iv));
	}
	
	public function decrypt(String $encrypted)
	{
		return openssl_decrypt(base64_decode($encrypted), $this->chiper, $this->key, $this->option, $this->iv);
	}
}