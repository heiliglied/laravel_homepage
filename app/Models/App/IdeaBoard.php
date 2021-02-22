<?php

namespace App\Models\App;

use Illuminate\Database\Eloquent\Model;

class IdeaBoard extends Model
{
    protected $table = 'idea_board';
	
	protected $casts = [
		'created_at' => 'datetime:Y-m-d H:i:s',
		'updated_at' => 'datetime:Y-m-d H:i:s',
	];
		
	protected $fillable = [
        'writer', 'user_id', 'subject', 'contents', 'content_type', 'view', 'files', 'censorship', 'deleted_at',
    ];
}
