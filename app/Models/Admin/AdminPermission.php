<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model
{
    protected $table = 'admin_permission';
	public $timestamps = false;
	
	protected $fillable = [
        'rank', 'uri',
    ];
}
