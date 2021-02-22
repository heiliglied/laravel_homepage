<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Services\UserRankService;
use Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:20'],
            'user_id' => ['required', 'string', 'email', 'max:120', 'unique:users', 'regex:/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ]);
    }
	
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'user_id' => $data['user_id'],
            'password' => Hash::make($data['password']),
			'rank' => $data['rank'],
        ]);
    }
	
	protected function register(Request $request)
	{
		return view('auth.register');
	}
	
	protected function signUp(Request $request)
	{
		$valid_chk = $this->validator($request->all());

		if(!$valid_chk->fails()) {
			//기본값 찾기
			$rank = 1;
			
			$userRankService = UserRankService::getInstance();
			$default_rank = $userRankService->getOneRow('default', 'Y');
			
			if($default_rank->rank != '') {
				$rank = $default_rank->rank;
			}
			
			$this->create(
				[
					'user_id' => $request->user_id,
					'password' => $request->password,
					'name' => $request->name,
					'rank' => $rank,
				]
			);
			Auth::guard()->attempt(['user_id' => $request->input('user_id'), 'password' => $request->input('password')], $request->has('remember'));
			return redirect()->Intended($this->redirectTo);
		} else {
			return redirect('/')->withErrors($valid_chk)->withInput();
		}
	}
}
