<?php

namespace App\Libs;

use File;
use Storage;

class FileLibrary
{
	private $uploadPath = "public";
	private $uploadStore = "local";
	
	public function __construct()
	{
		
	}
	
	public function multiUpload(array $files, String $path = "", String $store = "")
	{
		if($path != "") {
			$this->uploadPath = $path;
		}
		
		if($store != "") {
			$this->uploadStore = "local";
		}
		
		$folder = $this->makeDirectory($this->uploadPath);
		
		$result = [
			'success' => true,
			'data' => [],
		];
		
		foreach($files as $key => $value) {
			try {
				$realname = Storage::disk($this->uploadStore)->put($path, $value);				
				array_push($result['data'], ['original_name' => $value->getClientOriginalName(), 'renamed_name' => $realname, 'key_name' => $key]);
			} catch(\Exception $e) {
				array_push($result, ['success' => false]);
				return $result;
			}
		}
		return $result;
	}
	
	public function upload(array $files, String $path = "", String $store = "")
	{
		if($path != "") {
			$this->uploadPath = $path;
		}
		
		if($store != "") {
			$this->uploadStore = "local";
		}
		
		$folder = $this->makeDirectory($this->uploadPath);
		
		$result = [
			'success' => true,
			'data' => [],
		];
		
		foreach($files as $value) {
			try {
				$realname = Storage::disk($this->uploadStore)->put($path, $value);				
				array_push($result['data'], ['original_name' => $value->getClientOriginalName(), 'renamed_name' => $realname]);
			} catch(\Exception $e) {
				array_push($result, ['success' => false]);
				return $result;
			}
		}
	
		return $result;
	}
	
	public function deleteFile(String $file) {
		try {
			File::delete(Storage::disk($this->uploadStore)->path($file));
			return true;
		} catch(\Exception $e) {
			return false;
		}
	}
	
	public function moveFromTemporaryToNew(String $temp, String $real)
	{
		$year = date("Y");
		$month = date("m");
		
		$this->makeDirectory($year);
		$this->makeDirectory($year . "/" . $month);
		
		try {
			Storage::disk($this->uploadStore)->move($temp, $year . "/" . $month . "/" . $real);
			return $year . "/" . $month . "/" . $real;
		} catch(\Exception $e) {
			return $e->getMessage();
			return false;
		}
	}
	
	public function copyFromTemporaryToNew(String $temp, String $real)
	{
		$year = date("Y");
		$month = date("m");
		
		$this->makeDirectory($year);
		$this->makeDirectory($year . "/" . $month);
		
		try {
			Storage::disk($this->uploadStore)->copy($temp, $year . "/" . $month . "/" . $real);
			return $year . "/" . $month . "/" . $real;
		} catch(\Exception $e) {
			return $e->getMessage();
			return false;
		}
	}
		
	protected function makeDirectory(String $path)
	{
		$folder = Storage::disk($this->uploadStore)->path($path);
		if(!File::isDirectory($folder)) {
			$oldumask = umask(0);
			File::makeDirectory($folder, 0777, true, true);
			umask($oldumask);
		}
	}

	private function deleteDirectory(String $path)
	{
		$folder = Storage::disk($this->uploadStore)->path($path);
		if(File::isDirectory($folder)) {
			File::deleteDirectory($folder);
		}
	}
	
	private function findDirectory(String $path)
	{
		$folder = Storage::disk($this->uploadStore)->path($path);
		if(File::isDirectory($folder)) {
			return true;
		} else {
			return false;
		}
	}
}