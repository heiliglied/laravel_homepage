<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use App\Models\User;
use App\Models\PasswordResets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
	
	protected function forgotPassword(Request $request)
	{
		return view('auth.passwords.forgot');
	}
	
	protected function findPassword(Request $request)
	{
		$request->validate(['user_id' => 'required|email']);
		
		$user_data = User::where('user_id', $request->input('user_id'))->first();
		
		if($user_data == null) {
			return redirect()->back()->withErrors(['msg' => '해당하는 사용자가 없습니다.'])->withInput();	
		} else {
			$email = $user_data->user_id;
			$time = \Carbon\Carbon::now()->toDateTimeString();
			$token = base64_encode($email . 'p_k' . $time);
			
			$query = "insert into password_resets(email, token, created_at) "
					. "values('" . $email . "', '" . $token . "', '" . $time . "') "
					. "on duplicate key update token = '" . $token . "', created_at = '" . $time . "'";
			
			DB::select($query);
			
			$replace_data = ['link' => env('APP_URL') . '/password_resets/' . $email . '/' . $token, 'time' => '1시간'];
			
			Mail::send('mail.password_reset', $replace_data, function($message) use($email){
				$message->from('heiliglied@gmail.com');
				$message->to($email)->subject('Idea Factory Email Reset Link Information.');
			});
			
			return back()->with('find_info', 'success');
		}
	}
}
