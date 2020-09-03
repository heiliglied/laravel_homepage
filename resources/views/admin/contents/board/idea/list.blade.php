@extends('layouts.layout')

@section('title')
Admin
@endsection

@section('heads')
<link rel="stylesheet" type="text/css" href="/mix/css/app.css" />
<link rel="stylesheet" type="text/css" href="/plugin/adminlte/dist/css/adminlte.min.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/dataTables.bootstrap4.min.css" />
<style>
</style>
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
						<h1 class="m-0 text-dark">컨텐츠 관리</h1>
					</div><!-- /.col -->
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/contents/ideaBoard/list">아이디어보드</a></li>
							<li class="breadcrumb-item active">게시판 관리</li>
						</ol>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->
		
		<section class="content" id="rank_body">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">게시판 관리</h3>
							</div>
							<!-- /.card-header -->
							<div class="card-body">
								<table id="idea_list" class="table table-bordered table-hover">
									<thead>
										<th>No.</th>
										<th>작성자</th>
										<th>제목</th>
										<th>파일</th>
										<th>덧글</th>
										<th>생성일</th>
										<th>검열</th>
										<th>Action</th>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		
	</div>

</div>
@include('layouts.admin.footer')
@include('layouts.admin.righttoggle')
@include('templates.confirm', ['confirm_title' => '', 'confirm_body' => ''])
@endsection

@section('scripts')
<script src="/mix/js/manifest.js"></script>
<script src="/mix/js/vendor.js"></script>
<script src="/mix/js/app.js"></script>
<script src="/mix/js/bootstrap.bundle.min.js"></script>
<script src="/plugin/adminlte/dist/js/adminlte.min.js"></script>
<script src="/mix/js/dataTables.bootstrap4.min.js"></script>
<script src="/js/admin/boardList.js"></script>
<script>
@include('errors.permission')

</script>
@endsection