<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    protected function test(Request $request)
	{
		return view('test');
	}
	
	protected function getBuffer(Request $request)
	{	
		header('X-Accel-Buffering: no;');
		//ob_start("ob_gzhandler");
		ob_start();
		for($i = 0; $i < 100; $i++) {
			echo $i . "<br/>";
			echo str_pad("", 4096);
			ob_flush();
			flush();
			sleep(1);
		}		
		ob_end_flush();
	}
}
