@extends('layouts.layout')

@section('title')
Admin
@endsection

@section('heads')
<link rel="stylesheet" type="text/css" href="/mix/css/app.css" />
<link rel="stylesheet" type="text/css" href="/plugin/adminlte/dist/css/adminlte.min.css" />

@endsection

@section('body_class')
class="hold-transition sidebar-mini layout-fixed"
@endsection

@section('contents')
<div class="wrapper">
@include('layouts.admin.nav')
@include('layouts.admin.aside')

	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">관리자 수정</h1>
					</div><!-- /.col -->
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/settings/rank">환경설정</a></li>
							<li class="breadcrumb-item active">관리자 등록/수정</li>
						</ol>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->
		
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card card-primary">
							<div class="card-header">
								<h3 class="card-title">관리자 수정</h3>
							</div>
							<form name="rank_form" method="post" action="/admin/settings/member/update" onSubmit="return create_admin(this)">							
							{{ csrf_field() }}
							@method('PATCH')
							<input type="hidden" name="id" value="{{ $member->id }}">
							<input type="hidden" name="user_id" value="{{ $member->user_id }}">
							<div class="card-body">
								<div class="form-group">
									<label for="user_id">사용자ID * </label>
									<div class="input-group">
										{{ $member->user_id }}
									</div>
									<div id="id_status">
									</div>
								</div>
								<div class="form-group">
									<div class="custom-control custom-checkbox">
										<input class="custom-control-input" type="checkbox" name="changePassword" id="changePassword" value="Y">
										<label for="changePassword" class="custom-control-label">비밀번호 갱신</label>
									</div>
								</div>
								<div class="form-group">
									<label for="password">비밀번호 * </label>
									<input type="password" name="password" value="" id="password" class="form-control" disabled>
								</div>
								<div class="form-group">
									<label for="password_confirmation">비밀번호확인 * </label>
									<input type="password" name="password_confirmation" value="" id="password_confirmation" class="form-control" disabled>
									<div id="password_status">
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="rank">관리자 등급설정 * </label>
											<select name="rank" class="form-control" id="rank" required>
												@foreach($rank as $admin_rank)
												<option value="{{ $admin_rank->rank }}" @if($admin_rank->rank == $member->rank) selected @endif>{{ $admin_rank->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="email">이메일 * </label>
											<input type="email" name="email" class="form-control" value="{{ $member->email }}" id="email" required>
										</div>
									</div>													
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="name">이름</label>
											<input type="text" name="name" class="form-control" value="{{ $member->name }}" id="name">
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="contact">연락처</label>
											<input type="text" name="contact" class="form-control" value="{{ $member->contact }}" id="contact">
										</div>
									</div>
								</div>
								<div class="row">
								@foreach($errors->all() as $error)
								{{ $error }}<br/>
								@endforeach()
								</div>
							</div>
							<div class="card-footer text-right">
								* 은 필수 입력사항입니다. 
								<button type="button" onclick="location.href='/admin/settings/member'" class="btn btn-info">목록</button>
								&nbsp;
								<button type="submit" class="btn btn-primary">수정</button>
							</div>
							</form>
						</div>
					</div>
				</div>
			</div><!-- /.container-fluid -->
		</section>
		
	</div>

</div>
@include('layouts.admin.footer')
@include('layouts.admin.righttoggle')
@endsection

@section('scripts')
<script src="/mix/js/manifest.js"></script>
<script src="/mix/js/vendor.js"></script>
<script src="/mix/js/app.js"></script>
<script src="/mix/js/bootstrap.bundle.min.js"></script>
<script src="/plugin/adminlte/dist/js/adminlte.min.js"></script>
<script>
@include('errors.permission')
window.onload = function() {
	checkPassword(document.getElementById('changePassword'));
}

var cb = document.getElementById('changePassword');
cb.addEventListener('click', function(event) {
	checkPassword(this);
});

function checkPassword(obj) {
	if(obj.checked == true) {
		document.getElementById('password').setAttribute('required', 'required');
		document.getElementById('password_confirmation').setAttribute('required', 'required');
		document.getElementById('password').removeAttribute('disabled');
		document.getElementById('password_confirmation').removeAttribute('disabled');
	} else {
		document.getElementById('password').setAttribute('disabled', 'disabled');
		document.getElementById('password_confirmation').setAttribute('disabled', 'disabled');
		document.getElementById('password').removeAttribute('required');
		document.getElementById('password_confirmation').removeAttribute('required');
	}
}

function create_admin(form) {
	if(document.getElementById('changePassword').checked == true) {
		if(form.password.value != form.password_confirmation.value) {
			document.getElementById('password_status').innerHTML = '입력하신 비밀번호가 일치하지 않습니다.';
			form.password.focus();
			return false;
		}
	}
}
</script>
@endsection