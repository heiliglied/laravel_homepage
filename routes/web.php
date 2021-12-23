<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/ttt', function(){
	broadcast(new \App\Events\BoardNewEvents(['type' => 'reply', 'writer' => 'ababab', 'subject' => '']))->toOthers();
});
Route::get('/', function () {
    return view('home');
});

//로그인 로그아웃
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'loginForm']);
Route::get('/logout', 'Auth\LoginController@logout');
Route::post('/signIn', 'Auth\LoginController@signIn');

//비밀번호 찾기, 갱신.
Route::get('/forgotPassword', 'Auth\ForgotPasswordController@forgotPassword');
Route::post('/findPassword', 'Auth\ForgotPasswordController@findPassword');
Route::get('/password_resets/{email}/{token}', 'Auth\ResetPasswordController@resetForm');
Route::patch('/resetPassword', 'Auth\ResetPasswordController@resetPassword');

//회원가입
Route::get('/register', 'Auth\RegisterController@register');
Route::post('/signUp', 'Auth\RegisterController@signUp');
Route::post('/ajax/userIdCheck', 'Ajax\UserMemberController@idCheck');

//짭피들러
Route::group(['prefix' => 'zzapfiddler'], function(){
	Route::get('{random_key?}', 'Fiddler\FiddlerController@index');
	Route::get('show/result', 'Fiddler\FiddlerController@show');
	Route::post('save', 'Fiddler\FiddlerController@save')->middleware('auth');
	Route::post('getList', 'Fiddler\FiddlerController@getList')->middleware('auth');
	Route::delete('delete', 'Fiddler\FiddlerController@delete')->middleware('auth');
});

Route::group(['middleware' => ['auth']], function(){
	Route::get('/user/mypage', 'User\UserController@mypage');
	Route::patch('/user/update', 'User\UserController@update');
	
	Route::group(['prefix' => 'ideaBoard'], function(){
		Route::get('list', 'Board\IdeaBoardController@list');
		Route::get('getList', 'Ajax\IdeaBoardController@getList');
		Route::get('write', 'Board\IdeaBoardController@write');
		Route::post('create', 'Board\IdeaBoardController@create');
		Route::get('modify/{id}', 'Board\IdeaBoardController@modify');
		Route::post('download', 'Board\IdeaBoardController@download');
		Route::delete('deleteFile/{id}', 'Ajax\IdeaBoardController@deleteFile');
		Route::patch('update', 'Board\IdeaBoardController@update');
		Route::get('view/{id}', 'Board\IdeaBoardController@view')->middleware('view_increse:idea_board');
		Route::delete('delete/{id?}', 'Board\IdeaBoardController@delete');
		Route::post('reply/write', 'Ajax\IdeaReplyController@create');
		Route::get('reply/getList', 'Ajax\IdeaReplyController@getList');
		Route::delete('reply/delete/{id}', 'Ajax\IdeaReplyController@deleteReply');
	});
});

Route::group(['prefix' => 'admin'], function(){
	//비로그인 상태에서 접근.
	Route::get('login', 'Admin\Auth\LoginController@login')->name('adminLogin');
	Route::get('logout', 'Admin\Auth\LoginController@logout')->name('adminLogout');
	Route::post('signIn', 'Admin\Auth\LoginController@signIn')->name('adminSignIn');
	
	Route::get('regist', 'Admin\Auth\RegisterController@regist')->name('adminRegist');
	Route::post('signUp', 'Admin\Auth\RegisterController@signUp')->name('adminSignUp');
	
	//인증 미들웨어 확인
	Route::group(['middleware' => ['auth:admin', 'admin.permission']], function() {
		Route::get('/', 'Admin\IndexController@index');
		
		Route::group(['prefix' => 'settings'], function(){
			Route::get('/site', 'Admin\Settings\SiteSettingController@view');
			Route::post('/setSettings', 'Admin\Settings\SiteSettingController@set');
			Route::get('/rank', 'Admin\Settings\RankController@list');
			Route::get('/member', 'Admin\Settings\MemberController@adminList');
			Route::get('/member/write', 'Admin\Settings\MemberController@adminWrite');
			Route::get('/member/modify/{id}', 'Admin\Settings\MemberController@adminModify');
			Route::post('/member/create', 'Admin\Settings\MemberController@adminCreate');
			Route::patch('/member/update', 'Admin\Settings\MemberController@adminUpdate');
			Route::get('/permission', 'Admin\Settings\PermissionController@list');
			Route::get('/permission/write', 'Admin\Settings\PermissionController@write');
			Route::post('/permission/create', 'Admin\Settings\PermissionController@create');
			Route::get('/permission/modify/{id}', 'Admin\Settings\PermissionController@modify');
			Route::patch('/permission/update', 'Admin\Settings\PermissionController@update');
		});
		
		Route::group(['prefix' => 'users'], function(){
			Route::get('/rank', 'Admin\Users\RankController@list');
			Route::get('/users', 'Admin\Users\UserController@list');
			Route::get('/users/write', 'Admin\Users\UserController@userWrite');
			Route::get('/users/modify/{id}', 'Admin\Users\UserController@userModify');
			
			Route::post('/users/create', 'Admin\Users\UserController@userCreate');
			Route::patch('/users/update', 'Admin\Users\UserController@userUpdate');
			Route::delete('/userDelete/', 'Admin\Users\UserController@userDelete');
		});
		
		Route::group(['prefix' => 'contents'], function(){
			Route::get('/fiddler', 'Admin\Contents\FiddlerController@list');
			Route::get('/ideaBoard/list', 'Admin\Contents\IdeaBoardController@list');
			Route::get('/ideaBoard/view/{id}', 'Admin\Contents\IdeaBoardController@view');
			Route::get('/ideaBoard/modify/{id}', 'Admin\Contents\IdeaBoardController@modify');
			Route::post('/ideaBoard/download', 'Admin\Contents\IdeaBoardController@download');
			Route::patch('/ideaBoard/update', 'Admin\Contents\IdeaBoardController@update');
			Route::patch('/ideaBoard/censor', 'Admin\Contents\IdeaBoardController@censor');
			Route::delete('/ideaBoard/delete', 'Admin\Contents\IdeaBoardController@delete');
		});
		
		Route::group(['prefix' => 'test'], function(){
			Route::get('/cast', 'Admin\Testing\TestController@cast');
			Route::get('/castList', 'Admin\Testing\TestController@castList');
			Route::get('/castWrite', 'Admin\Testing\TestController@castWrite');
			Route::post('/castCreate', 'Admin\Testing\TestController@castCreate');
		});
		
		Route::group(['prefix' => 'ajax'], function(){
			Route::get('/adminRankList', 'Ajax\AdminRankController@getList');
			Route::post('/adminRankInsert', 'Ajax\AdminRankController@insert');
			Route::delete('/adminRankDelete', 'Ajax\AdminRankController@delete');
			Route::patch('/adminRankUpdate', 'Ajax\AdminRankController@update');
			
			Route::post('/adminIdCheck', 'Ajax\AdminMemberController@idCheck');
			Route::get('/adminList', 'Ajax\AdminMemberController@adminList');
			Route::delete('/adminDelete/{id}', 'Ajax\AdminMemberController@adminDelete');
			
			Route::get('/permissionList', 'Ajax\AdminPermissionController@permissionList');
			Route::delete('/permissionDelete/{id}', 'Ajax\AdminPermissionController@permissionDelete');
			
			Route::get('/FiddlerList', 'Ajax\FiddlerController@fiddlerList');
			Route::delete('/fiddlerDelete/{id}', 'Ajax\FiddlerController@delete');
			
			Route::get('/ideaList', 'Ajax\IdeaBoardController@getListDT');
			Route::patch('/ideaList/censor/{id}', 'Ajax\IdeaBoardController@censor');
			Route::delete('/ideaList/delete/{id}', 'Ajax\IdeaBoardController@delete');
			
			Route::get('/userRankList', 'Ajax\UserRankController@getList');
			Route::post('/userRankInsert', 'Ajax\UserRankController@insert');
			Route::delete('/userRankDelete', 'Ajax\UserRankController@delete');
			Route::patch('/userRankUpdate', 'Ajax\UserRankController@update');
			Route::patch('/userRankSetDefault', 'Ajax\UserRankController@setDefault');
			
			Route::post('/userIdCheck', 'Ajax\UserMemberController@idCheck');
			Route::get('/userList', 'Ajax\UserMemberController@userList');
			Route::patch('/userExcept/{id}', 'Ajax\UserMemberController@userExcept');
			
			Route::delete('/ideaBoard/deleteFile/{id}', 'Ajax\IdeaBoardController@deleteFile');
			Route::post('/ideaBoard/reply/write', 'Ajax\IdeaReplyController@create');
			Route::get('/ideaBoard/reply/getList', 'Ajax\IdeaReplyController@getList');
			Route::patch('/ideaBoard/reply/censor/{id}', 'Ajax\IdeaReplyController@censor');
			Route::delete('/ideaBoard/reply/delete/{id}', 'Ajax\IdeaReplyController@deleteReply');
		});
	});
});

Route::group(['prefix' => 'apis'], function(){
	Route::get('/login', 'API\APIController@login');
});

//Auth::routes();
