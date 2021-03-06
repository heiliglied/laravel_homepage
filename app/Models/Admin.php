<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
	use Notifiable, HasApiTokens;
	
	protected $table = 'admin';
	
	//protected $guard = 'admin';
	
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
