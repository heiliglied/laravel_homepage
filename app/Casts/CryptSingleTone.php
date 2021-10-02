<?php 

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\Libs\CryptSingleTone as Chipher;

class CryptSingleTone implements CastsAttributes
{
	protected $chipher;
	
	public function __construct() {
		$this->chipher = Chipher::getInstance();
	}
	
	public function get($model, $key, $value, $attributes)
	{
		return $value != null ? $this->chipher->decrypt($value) : '';
	}
	
	public function set($model, $key, $value, $attributes)
	{
		return $value != null ? $this->chipher->encrypt($value) : '';
	}
}