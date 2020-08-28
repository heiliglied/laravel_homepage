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
						<h1 class="m-0 text-dark">사용자 등록</h1>
					</div><!-- /.col -->
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/users/users">사용자 관리</a></li>
							<li class="breadcrumb-item active">사용자 관리</li>
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
								<h3 class="card-title">사용자 등록</h3>
							</div>
							<form name="rank_form" method="post" action="/admin/users/users/create" onSubmit="return create_user(this)">
							{{ csrf_field() }}
							<input type="hidden" name="enable_id" value="disable">
							<div class="card-body">								
								<div class="form-group">
									<label for="user_id">사용자ID * </label>
									<div class="input-group">
										<input type="text" name="user_id" value="{{ old('user_id') }}" id="user_id" class="form-control" required>
										<div class="input-group-prepend">
											<button type="button" class="btn btn-info" onclick="check_id()">ID 중복체크</button>
										</div>
									</div>
									<div id="id_status">
									</div>
								</div>
								<div class="form-group">
									<label for="password">비밀번호 * </label>
									<input type="password" name="password" value="" id="password" class="form-control" required>
								</div>
								<div class="form-group">
									<label for="password_confirmation">비밀번호확인 * </label>
									<input type="password" name="password_confirmation" value="" id="password_confirmation" class="form-control" required>
									<div id="password_status">
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="rank">사용자 등급설정 * </label>
											<select name="rank" class="form-control" id="rank" required>
												@foreach($rank as $user_rank)
												<option value="{{ $user_rank->rank }}">{{ $user_rank->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="email">이메일 * </label>
											<input type="email" name="email" class="form-control" id="email" required>
										</div>
									</div>													
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="name">이름 * </label>
											<input type="text" name="name" class="form-control" id="name" required>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="contact">연락처</label>
											<input type="text" name="contact" class="form-control" id="contact">
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
								<button type="button" onclick="location.href='/admin/users/users'" class="btn btn-info">목록</button>
								&nbsp;
								<button type="submit" class="btn btn-primary">등록</button>
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
function check_id() {
	axios.post('/admin/ajax/userIdCheck', {
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
}
</script>
@endsection