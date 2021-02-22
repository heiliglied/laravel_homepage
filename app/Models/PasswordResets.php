<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordResets extends Model
{
    protected $table = 'password_resets';
	//public $timestamps = false;
	const UPDATED_AT = null;
	public $primaryKey = 'email';
	
	protected $fillable = [
        'email', 'token',
    ];
}