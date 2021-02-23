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
				<span class="sign_title">비밀번호 갱신</span>
				<form name="password_form" method="post" action="/resetPassword">
				{{ csrf_field() }}
				<input type="hidden" name="_method" value="PATCH">
				<input type="hidden" name="email" value="{{ $email }}">
				<input type="hidden" name="token" value="{{ $token }}">
				<div class="form-group">
					<label for="password">비밀번호 </label>
					<input type="password" name="password" value="" id="password" class="form-control" required pattern="(?=^.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$">
				</div>
				<div class="form-group">
					<label for="password_confirmation">비밀번호확인 </label>
					<input type="password" name="password_confirmation" value="" id="password_confirmation" class="form-control" required pattern="(?=^.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$">
					<div id="password_status"></div>
				</div>
				<div class="form-group">
					@foreach($errors->all() as $error)
					{{ $error }}<br/>
					@endforeach()
				</div>
				<span class="sign_btn">
					<button type="submit" class="btn btn-primary">확인</button>
				</span>
				</form>
			</div>
			<div class="sign_addon"></div>
		</div>
	</div>
</div>
@endsection

@section('scripts')

@endsection

