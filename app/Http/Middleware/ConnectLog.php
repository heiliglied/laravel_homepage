<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;

class ConnectLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
		
		$user_id = "";
		
		if(Auth::user()) {
			$user_id = Auth::user()->id;
		} else {
			$user_id = session()->getId();
		}
		
		$date = date('Y-m-d H:i:s');

		DB::table('connect_log')->insert(
			[
				'user_id' => $user_id,
				'site_uri' => $request->path(),
				'ip' => $request->ip(),
				'year' => substr($date, 0, 4),
				'month' => substr($date, 5, 2),
				'created_at' => $date,
			]
		);
		
        return $response;
    }
}
