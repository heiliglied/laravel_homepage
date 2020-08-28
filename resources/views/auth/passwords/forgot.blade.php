@extends('layouts.layout')

@section('title')
Idea Factory
@endsection

@section('heads')
<link rel="stylesheet" type="text/css" href="/mix/css/app.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/toastr.css" />
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
				<span class="sign_title">비밀번호 찾기</span>
				<form name="password_form" method="post" action="/findPassword">
				{{ csrf_field() }}
				<div class="form-group">
					<label for="user_id">사용자ID(가입된 ID)</label>
					<div class="input-group">
						<input type="text" name="user_id" value="{{ old('user_id') }}" id="user_id" class="form-control" required>
					</div>
					<div id="id_status">
						{{ __('passwords.find') }}
					</div>
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
<script src="/mix/js/manifest.js"></script>
<script src="/mix/js/vendor.js"></script>
<script src="/mix/js/app.js"></script>
<script src="/mix/js/bootstrap.bundle.min.js"></script>
<script>
@if(session('find_info') == 'success')
	toastr.info('{{ __("passwords.sendlink") }}');
@endif
</script>
@endsection