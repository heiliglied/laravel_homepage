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
						<h1 class="m-0 text-dark">사이트 설정</h1>
					</div><!-- /.col -->
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/settings/rank">환경설정</a></li>
							<li class="breadcrumb-item active">사이트 설정</li>
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
						<form name="setting_form" method="post" action="/admin/settings/setSettings">
						{{ csrf_field() }}
						<div class="card card-primary">
							<div class="card-header">
								<h3 class="card-title">사이트 설정 관리</h3>
							</div>
							<div class="card-body">
								<div class="form-group">
									<label for="adminRankOrder">관리자 등급설정 </label>
									<select name="adminRankOrder" class="form-control" id="adminRankOrder" required>
										<option value="asc" @if($setting['adminRankOrder'] == 'asc') selected @endif>작을수록 높은 등급</option>
										<option value="desc" @if($setting['adminRankOrder'] == 'desc') selected @endif>클수록 높은 등급</option>
									</select>
								</div>
								<div class="form-group">
									<label for="userRankOrder">사용자 등급설정 </label>
									<select name="userRankOrder" class="form-control" id="userRankOrder" required>
										<option value="asc" @if($setting['userRankOrder'] == 'asc') selected @endif>작을수록 높은 등급</option>
										<option value="desc" @if($setting['userRankOrder'] == 'desc') selected @endif>클수록 높은 등급</option>
									</select>
								</div>
							</div>
							<div class="card-footer text-right">
								<button type="submit" class="btn btn-primary">저장</button>
							</div>
						</div>
						</form>
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
</script>
@endsection