<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class ViewCount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $boardName)
    {
        $response = $next($request);
		
		DB::table($boardName)->where('id', $request->id)->update(
			[
				'view' => DB::raw('view + 1')
			]
		);
		
		return $response;
    }
}
