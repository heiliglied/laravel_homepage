@extends('layouts.layout')

@section('title')
Idea Factory
@endsection

@section('heads')
<link rel="stylesheet" type="text/css" href="/mix/css/app.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/main.css" />
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
				<span class="sign_title">회 원 가 입</span>
				<form name="register_form" method="post" action="/signUp" onSubmit="return create_user(this)">
				{{ csrf_field() }}
				<input type="hidden" name="enable_id" value="disable">
				<div class="form-group">
					<label for="user_id">사용자ID </label>
					<div class="input-group">
						<input type="email" name="user_id" value="{{ old('user_id') }}" id="user_id" class="form-control" required>
						<div class="input-group-prepend">
							<button type="button" class="btn btn-info" onclick="check_id()">ID 중복체크</button>
						</div>
					</div>
					<div id="id_status">
					</div>
				</div>
				<div style="color: red;">
				{{ __('passwords.pattern') }}
				</div>
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
					<label for="name">이름 </label>
					<input type="text" name="name" class="form-control" id="name" required>
					<div id="name_status"></div>
				</div>
				<div class="form-group">
					@foreach($errors->all() as $error)
					{{ $error }}<br/>
					@endforeach()
				</div>
				<span class="sign_btn">
					<button type="submit" class="btn btn-primary">등록</button>
				</span>
				</form>
			</div>
			<div class="sign_addon"></div>
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
function check_id() {
	axios.post('/ajax/userIdCheck', {
		user_id: document.getElementsByName('user_id')[0].value
	}).then((response) => {
		if(response.data == 'enable') {
			document.getElementById('id_status').innerHTML = '사용 가능한 아이디입니다.';
			document.getElementsByName('enable_id')[0].value = 'enable';
		} else if(response.data == 'duplicate') {
			document.getElementById('id_status').innerHTML = '이미 사용중인 아이디입니다.';
			document.getElementsByName('enable_id')[0].value = 'disable';
		} else if(response.data == 'id_null') {
			document.getElementById('id_status').innerHTML = '아이디를 입력 해 주세요.';
			document.getElementsByName('enable_id')[0].value = 'disable';
		}
	});
}

function create_user(form) {
	if(form.user_id.value == '') {
		document.getElementById('id_status').innerHTML = '아이디를 입력 해 주세요.';
		document.getElementsByName('enable_id')[0].value = 'disable';
		form.user_id.focus();
		return false;
	}
	
	if(form.enable_id.value != 'enable') {
		document.getElementById('id_status').innerHTML = 'ID 중복체크를 해 주세요.';
		form.user_id.focus();
		return false;
	}
	
	if(form.password.value != form.password_confirmation.value) {
		document.getElementById('password_status').innerHTML = '입력하신 비밀번호가 일치하지 않습니다.';
		form.password.focus();
		return false;
	}
	
	if(form.name.value == '') {
		document.getElementById('name_status').innerHTML = '사용하실 이름을 입력해 주세요.';
		form.name.focus();
		return false;
	}
}
</script>
@endsection

