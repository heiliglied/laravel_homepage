<?php

namespace App\Models\App;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $table = 'files';
	
	protected $fillable = [
        'table_name', 'board_num', 'original_name', 'renamed_name',
    ];
}
