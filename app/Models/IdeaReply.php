<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdeaReply extends Model
{
    protected $table = 'idea_reply';
	
	protected $casts = [
		'created_at' => 'datetime:Y-m-d H:i:s',
		'updated_at' => 'datetime:Y-m-d H:i:s',
	];
		
	protected $fillable = [
        'writer', 'user_id', 'table_name', 'board_id', 'reply', 'parent_id', 'depth', 'censorship', 
    ];
}
