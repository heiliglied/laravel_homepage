@extends('layouts.layout')

@section('title')
Idea Factory
@endsection

@section('heads')
<style>

</style>
@endsection

@section('contents')
<div class="wrapper bg-white">
	<div class="navigation bg-aliceblue">
	@include('layouts.main.nav')
	</div>
	<div class="contents bg-white">
		<div class="sign_layer">
			<div class="sign_form">
				<span class="sign_title">로 그 인</span>
				<form name="login_form" method="post" action="/signIn">
				{{ csrf_field() }}
				<input type="hidden" name="enable_id" value="disable">
				<div class="form-group">
					<label for="user_id">사용자ID </label>
					<div class="input-group">
						<input type="email" name="user_id" value="{{ old('user_id') }}" id="user_id" class="form-control" required>
					</div>
					<div id="id_status">
					</div>
				</div>
				<div class="form-group">
					<label for="password">비밀번호 </label>
					<input type="password" name="password" value="" id="password" class="form-control" required pattern="(?=^.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$">
				</div>
				<div class="form-group">
					<label><input type="checkbox" name="remember" style="width: auto;">Remember Me.</label>
				</div>
				<div class="form-group">
					@foreach($errors->all() as $error)
					{{ $error }}<br/>
					@endforeach()
				</div>
				<span class="sign_btn">
					<button type="submit" class="btn btn-primary">로그인</button>
				</span>
				</form>
				<br/>
				<a href="/forgotPassword"><span class="sign_title">비밀번호를 잊으셨나요?</span></a>
			</div>
			<div class="sign_addon"></div>
		</div>
	</div>
</div>
@endsection

@section('scripts')

@endsection

