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
						<h1 class="m-0 text-dark">Dashboard</h1>
					</div><!-- /.col -->
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="#">Home</a></li>
							<li class="breadcrumb-item active">Dashboard v1</li>
						</ol>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->
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