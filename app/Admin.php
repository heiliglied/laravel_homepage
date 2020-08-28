<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
	protected $table = 'admin';
	
    use Notifiable;
	
	protected $fillable = [
        'user_id', 'password', 'rank', 'name', 'email', 'contact', 
    ];
	
	protected $hidden = [
        'password', 'remember_token', 
    ];
	
	protected $casts = [
        'email_verified_at' => 'datetime', 
    ];
}
