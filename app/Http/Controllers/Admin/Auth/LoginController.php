<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Models\Admin;

class LoginController extends Controller
{
	use AuthenticatesUsers;
	
    public function __construct() 
	{
		$this->middleware('guest')->except('logout');
	}
	
	protected function login(Request $request)
	{
		$admin_cnt = Admin::where('rank', 0)->count();
		if($admin_cnt < 1) {
			return redirect(route('adminRegist'));
		} else {
			return view('admin.auth.login');
		}
	}
	
	protected function signIn(Request $request)
	{
		$this->validateLogin($request);
		
		if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
	}
	
	protected function logout(Request $request)
	{
		//$sessionKey = $this->guard()->getName();
		$this->guard()->logout();
		//$request->session()->forget($sessionKey);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect('/admin/login');
	}
	
	private function validateLogin(Request $request)
	{
		$request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
	}
	
	protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }
		
		//$admin = Admin::where('user_id', $request->user_id)->first();
		//$plainTextToken = $admin->createToken('adminToken')->plainTextToken;
		
        return $request->wantsJson()
                    ? new Response('', 204)
                    : redirect()->intended('/admin');
    }
	
	protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
	
	protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }
	
	protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }
	
	protected function authenticated(Request $request, $user)
    {
        //
    }
	
	public function username()
    {
        return 'user_id';
    }
	
	protected function guard()
    {
        return Auth::guard('admin');
    }
}
