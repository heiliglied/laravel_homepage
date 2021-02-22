<?php

namespace App\Models\App;

use Illuminate\Database\Eloquent\Model;

class UserRank extends Model
{
    protected $table = 'user_rank';
	public $timestamps = false;
	
	protected $fillable = [
        'rank', 'name', 'default',
    ];
}
