<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRank extends Model
{
    protected $table = 'admin_rank';
	public $timestamps = false;
	
	protected $fillable = [
        'rank', 'name', 'default',
    ];
}
