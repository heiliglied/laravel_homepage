@extends('layouts.layout')

@section('title')
Idea Factory
@endsection

@section('heads')
<link rel="stylesheet" type="text/css" href="/mix/css/app.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/toastr.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/main.css" />
<style>
.mypage {
	width: 90%;
	margin: 0 auto;
	padding-top: 2%;
}
</style>
@endsection

@section('contents')
<div class="wrapper bg-white">
	<div class="navigation bg-aliceblue">
	@include('layouts.main.nav')
	</div>
	<div class="contents bg-white">
		<form name="mypage_form" method="post" action="/user/update">
		{{ csrf_field() }}
		<input type="hidden" name="_method" value="PATCH">
		<input type="hidden" name="user_id" value="{{ Auth::user()->user_id }}">
		<div class="mypage">
			<div class="form-group">
				<label for="">사용자 ID</label>
				<span class="form-control">{{ Auth::user()->user_id }}</span>
			</div>
			<div class="form-group">
				<label for="name">이름</label>
				<input type="text" name="name" class="form-control" id="name" placeholder="이름" value="{{ Auth::user()->name }}">
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox" name="password_change" value="Y" id="password_change" onchange="resetCheck()">
				<label class="form-check-label" for="password_change">
				비밀번호 갱신
				</label>
			</div>
			<div class="form-group">
				<label for="password">비밀번호</label>
				<input type="password" name="password" class="form-control" id="password" placeholder="비밀번호" disabled pattern="(?=^.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$">
			</div>
			<div class="form-group">
				<label for="password_confirmation">비밀번호 확인</label>
				<input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="비밀번호" disabled pattern="(?=^.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$">
			</div>
			<div class="form-group">
					@foreach($errors->all() as $error)
					{{ $error }}<br/>
					@endforeach()
				</div>
			<div class="text-right">
				<button type="submit" class="btn btn-primary">변경</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="/mix/js/manifest.js"></script>
<script src="/mix/js/vendor.js"></script>
<script src="/mix/js/app.js"></script>
<script src="/mix/js/bootstrap.bundle.min.js"></script>
<script>
window.onload = function() {
	resetCheck();
}

function resetCheck() {
	if(document.getElementById("password_change").checked == true) {
		document.getElementById('password').removeAttribute('disabled');		
		document.getElementById('password').setAttribute('required', 'required');
		document.getElementById('password_confirmation').removeAttribute('disabled');
		document.getElementById('password_confirmation').setAttribute('required', 'required');
		
	} else {
		document.getElementById('password').setAttribute('disabled', 'disabled');
		document.getElementById('password').removeAttribute('required');
		document.getElementById('password_confirmation').setAttribute('disabled', 'disabled');
		document.getElementById('password_confirmation').removeAttribute('required');
	}
}

@if(session('msg'))
	toastr.info('{{ session("msg") }}');
@endif
</script>
@endsection