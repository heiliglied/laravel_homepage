<?php

namespace App\Services;

use App\Models\IdeaReply;
use DB;

class IdeaReplyService
{
	static private $instance = null;
	
	static function getInstance() 
	{
		if(self::$instance == null) {
			self::$instance = new IdeaReplyService();
		}
		
		return self::$instance;
	}
	
	public function create(array $data)
	{
		return IdeaReply::create($data);
	}
	
	public function filteredCount(String $table_name, int $board_num)
	{
		return IdeaReply::where('table_name', $table_name)->where('board_id', $board_num)->count();
	}
	
	public function getList(int $skip, int $take, String $table_name, int $board_num)
	{
		return IdeaReply::leftjoin('users', 'idea_reply.user_id', '=', 'users.id')
						->leftjoin('admin', 'idea_reply.user_id', '=', 'admin.id')
						->where('idea_reply.table_name', $table_name)
						->where('idea_reply.board_id', $board_num)
						->select(DB::raw(
							'idea_reply.id as id, ' .
							'idea_reply.reply as reply,' . 
							'if(idea_reply.writer = "user", users.name, admin.name) as writer_name, ' .
							'if(idea_reply.writer = "user", users.id, admin.id) as writer_id, ' .
							'idea_reply.writer as writer, ' . 
							'idea_reply.created_at as created_at, ' .
							'idea_reply.updated_at as updated_at, ' .
							'idea_reply.censorship as censorship '
						))
						->skip($skip)
						->take($take)
						->orderBy('idea_reply.id', 'desc')
						->groupBy('idea_reply.id')
						->get();
	}
	
	public function delete(String $column, $value)
	{
		return IdeaReply::where($column, $value)->delete();
	}
	
	public function conditionDelete(array $condition)
	{
		return IdeaReply::where(function($query) use($condition) {
			foreach($condition as $key => $value) {
				$query->where($key, $value);
			}
		})->delete();
	}
}