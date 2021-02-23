@extends('layouts.adminLayout')

@section('title')
Admin
@endsection

@section('heads')
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
						<h1 class="m-0 text-dark">접근권한 설정</h1>
					</div><!-- /.col -->
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/settings/rank">환경설정</a></li>
							<li class="breadcrumb-item active">접근권한 관리</li>
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
								<h3 class="card-title">접근권한 등록</h3>
							</div>
							<form name="rank_form" method="post" action="/admin/settings/permission/create" onSubmit="return create_permission(this)">
							{{ csrf_field() }}
							<div class="card-body">
								<div class="form-group">
									<label for="rank">관리자 등급설정 </label>
									<select name="rank" class="form-control" id="rank" required>
										@foreach($rank as $admin_rank)
										<option value="{{ $admin_rank->rank }}">{{ $admin_rank->name }}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group">
									<label for="uri">URI </label>
									<input type="text" name="uri" value="" id="uri" class="form-control" required placeholder="권한을 등록할 URI를 주소창에서 복사하여 기록해 주세요.">
									<div id="uri_status">
									</div>
								</div>
								<div class="row">
								@foreach($errors->all() as $error)
								{{ $error }}<br/>
								@endforeach()
								</div>
							</div>
							<div class="card-footer text-right">
								<button type="button" onclick="location.href='/admin/settings/permission'" class="btn btn-info">목록</button>
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
<script src="/plugin/adminlte/dist/js/adminlte.min.js"></script>
<script>
@include('errors.permission')
function create_permission(form) {
	if(form.uri.value == '') {
		document.getElementById('uri_status').innerHTML = 'URI를 입력해 주세요.';
		form.uri.focus();
		return false;
	}
}
</script>
@endsection