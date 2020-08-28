<?php

namespace App\Services;

use App\Models\IdeaBoard;
use DB;

class IdeaBoardService
{
	static private $instance = null;
	
	static function getInstance() 
	{
		if(self::$instance == null) {
			self::$instance = new IdeaBoardService();
		}
		
		return self::$instance;
	}
	
	public function create(array $data)
	{
		return IdeaBoard::create($data);
	}
	
	public function getOneRow(String $column, $value)
	{
		return IdeaBoard::where($column, $value)->first();
	}
	
	public function update(String $column, $value, array $data)
	{
		return IdeaBoard::where($column, $value)->update($data);
	}
	
	public function total()
	{
		return IdeaBoard::count();
	}
	
	public function filteredCount($value)
	{
		return IdeaBoard::leftjoin('users', 'idea_board.user_id', '=', 'users.id')
						->leftjoin('admin', 'idea_board.user_id', '=', 'admin.id')
						->where(function($query) use($value){
							$query->where('idea_board.subject', 'like', '%' . $value . '%')
								->orWhere('users.name', 'like', '%' . $value . '%')
								->orWhere('admin.name', 'like', '%' . $value . '%');
						})->count();
	}
	
	public function getList(int $skip, int $take, $value)
	{
		return IdeaBoard::leftjoin('users', 'idea_board.user_id', '=', 'users.id')
						->leftjoin('admin', 'idea_board.user_id', '=', 'admin.id')
						->leftjoin('files', function($query){
							$query->on('files.board_num', '=', 'idea_board.id')
								->where('files.table_name', '=', 'idea_board');
						})
						->leftjoin('idea_reply', function($query){
							$query->on('idea_reply.board_id', '=', 'idea_board.id')
								->where('idea_reply.table_name', '=', 'idea_board');
						})
						->where(function($query) use($value){
							$query->where('idea_board.subject', 'like', '%' . $value . '%')
								->orWhere('users.name', 'like', '%' . $value . '%')
								->orWhere('admin.name', 'like', '%' . $value . '%');
						})
						->select(DB::raw(
							'idea_board.id as id, ' .
							'idea_board.subject as subject,' . 
							'if(idea_board.writer = "user", users.name, admin.name) as writer_name, ' .
							'if(idea_board.writer = "user", users.id, admin.id) as writer_id, ' .
							'idea_board.view as view, ' .
							'idea_board.files as fileYN, ' .
							'idea_board.created_at as created_at, ' .
							'idea_board.updated_at as updated_at, ' .
							'idea_board.censorship as censorship, ' .
							'count(files.id) as files, ' . 
							'count(idea_reply.id) as replis'
						))
						->skip($skip)
						->take($take)
						->orderBy('idea_board.id', 'desc')
						->groupBy('idea_board.id')
						->get();
	}
	
	public function getListDT($parameters)
	{
		$result = IdeaBoard::where('idea_board.id', '>', 0);
		$result = $result->leftjoin('users', 'users.id', '=', 'idea_board.user_id');
		$result = $result->leftjoin('admin', 'admin.id', '=', 'idea_board.user_id');
		$result = $result->leftjoin('files', function($query){
			$query->on('files.board_num', '=', 'idea_board.id')
			->where('files.table_name', '=', 'idea_board');
		});
		$result = $result->leftjoin('idea_reply', function($query){
			$query->on('idea_reply.board_id', '=', 'idea_board.id')
			->where('idea_reply.table_name', '=', 'idea_board');
		});
		$result = $result->select(DB::raw(
										'idea_board.id as id, ' .
										'idea_board.subject as subject,' . 
										'if(idea_board.writer = "user", users.name, admin.name) as writer_name, ' .
										'if(idea_board.writer = "user", users.id, admin.id) as writer_id, ' .
										'idea_board.view as view, ' .
										'idea_board.files as fileYN, ' .
										'idea_board.created_at as created_at, ' .
										'idea_board.updated_at as updated_at, ' .
										'idea_board.censorship as censorship, ' .
										'count(files.id) as files, ' . 
										'count(idea_reply.id) as replis'
										)
								);
		
		$result = $result->where(function($query) use($parameters) {
			$query->where('idea_board.subject', 'like', '%' . $parameters['search']['value'] . '%')
				->orWhere('users.name', 'like', '%' . $parameters['search']['value'] . '%')
				->orWhere('admin.name', 'like', '%' . $parameters['search']['value'] . '%');
		});
		
		$result = $result->skip($parameters['skip'])->take($parameters['take'])->orderBy($parameters['order']['column'], $parameters['order']['sort'])->get();
		return $result;
	}
	
	public function delete(array $condition)
	{
		return IdeaBoard::where(function($query) use($condition) {
			foreach($condition as $key => $value) {
				$query->where($key, $value);
			}
		})->delete();
	}
	
	public function censor(String $column, int $id)
	{
		return IdeaBoard::where($column, $id)
						->update(
							[
								'censorship' => DB::raw('if(censorship="Y", "N", "Y")'),
							]
						);
	}
}