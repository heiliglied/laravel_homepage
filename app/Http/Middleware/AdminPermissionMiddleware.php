<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Admin\AdminPermission;

use App\Traits\Settings;

class AdminPermissionMiddleware
{
	use Settings;	
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$redirect_path = $request->path() != 'admin' ? $request->path() : 'root';		
		$permission = AdminPermission::where('uri', 'like', '%' . $redirect_path . '%')->first();
		
		//$permission = AdminPermission::where('uri', $request->path())->first();

		if($permission != null) {
			
			$settings = $this->getSettings();

			if($settings['adminRankOrder'] == 'asc') {
				if(Auth::user()->rank > $permission->rank) {
					return redirect()->back()->with('permission_denied', '접근 권한이 없습니다.');
				}
			} else {
				if(Auth::user()->rank < $permission->rank) {
					return redirect()->back()->with('permission_denied', '접근 권한이 없습니다.');
				}
			}
		}

        return $next($request);
    }
}
