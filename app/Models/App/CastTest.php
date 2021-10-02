<?php

namespace App\Models\App;

use Illuminate\Database\Eloquent\Model;
//use App\Casts\Cryption as Cryption;
use App\Casts\CryptSingleTone as Cryption;

class CastTest extends Model
{
    protected $table = 'cast_test';
	
	protected $fillable = [
        'normal', 'casted',
    ];
	
	protected $casts = [
		'created_at' => 'datetime:Y-m-d H:i:s',
		'updated_at' => 'datetime:Y-m-d H:i:s',
		//'casted' => Cryption::class,
		'casted' => Cryption::class,
	];
}
