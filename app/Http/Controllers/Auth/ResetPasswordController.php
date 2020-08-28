<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\PasswordResets;
use App\User;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
	
	protected function resetForm(Request $request)
	{
		$email = $request->email;
		$token = $request->token;
		
		$resets = PasswordResets::where('email', $email)->where('token', $token)->where('created_at', '>', \Carbon\Carbon::now()->subMinutes(60)->toDateTimeString())->first();
		if($resets != null) {
			return view('auth.passwords.reset', ['email' => $email, 'token' => $token]);
		} else {
			return view('errors.linkfail');
		}
	}
	
	protected function resetPassword(Request $request)
	{
		$user_data = User::where('user_id', $request->email)->first();
		$reset_token = PasswordResets::where('email', $request->email)->where('created_at', '>', \Carbon\Carbon::now()->subMinutes(60)->toDateTimeString())->first();
		
		if($reset_token == null) {
			return view('errors.timeEnd');
		}
		if($user_data == null) {
			return view('errors.linkfail');
		}
		
		$request->validate([
			'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
		]);
		
		User::where('user_id', $request->email)->update([
			'password' => Hash::make($request->password),
		]);
		
		return view('templates.passwordReset');
	}
}
