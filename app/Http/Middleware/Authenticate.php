<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
		// guard가 추가되어 redirect 경로를 변경함!
		/*
        if (! $request->expectsJson()) {
            return route('login');
        }
		*/
    }
}
