<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fiddler extends Model
{
    protected $table = 'fiddler';
	
	protected $casts = [
		'created_at' => 'datetime:Y-m-d H:i:s',
		'updated_at' => 'datetime:Y-m-d H:i:s',
	];
	
	protected $fillable = [
        'user_id', 'random_key', 'html_type', 'html', 'css_type', 'css', 'js_type', 'script', 
    ];
}
