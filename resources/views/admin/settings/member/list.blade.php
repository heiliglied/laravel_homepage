@extends('layouts.adminLayout')

@section('title')
Admin
@endsection

@section('heads')
<link rel="stylesheet" type="text/css" href="/plugin/adminlte/dist/css/adminlte.min.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/dataTables.bootstrap4.min.css" />
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
						<h1 class="m-0 text-dark">관리자 목록</h1>
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
								<h3 class="card-title">관리자 목록</h3>
							</div>
							<div class="card-body">
								<table id="admin_list" class="table table-bordered table-hover">
									<thead>
										<th>No.</th>
										<th>등급</th>
										<th>ID</th>
										<th>이름</th>
										<th>Email</th>
										<th>연락처</th>
										<th>Action</th>
									</thead>
								</table>
							</div>
							<div class="card-footer text-right">
								<button type="button" onclick="location.href='/admin/settings/member/write'" class="btn btn-info">신규등록</button>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /.container-fluid -->
		</section>
		
	</div>

</div>
@include('layouts.admin.footer')
@include('layouts.admin.righttoggle')
@include('templates.confirm', ['confirm_title' => '관리자 삭제', 'confirm_body' => '관리자를 삭제하시겠습니까?'])
@endsection

@section('scripts')
<script src="/plugin/adminlte/dist/js/adminlte.min.js"></script>
<script src="/mix/js/dataTables.bootstrap4.min.js"></script>
<script>
@include('errors.permission')
let table = $("#admin_list").DataTable(
	{
		'serverSide': true,
		'processing': true,
		'lengthMenu': [10, 20, 50, 100],
		'ajax': {
			'url': '/admin/ajax/adminList',
			'type': 'GET',
			'dataSrc': function(response) {
				let data = response.data;
				return data;
			}
		},
		'columns': [
			{
				'data': 'id',
				'render': function(data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{'data': 'rank'},
			{'data': 'user_id'},
			{'data': 'name'},
			{'data': 'email'},
			{'data': 'contact'},
			{
				'data': null,
				'render': function(data, type, row, meta) {
					return '<button type="button" class="btn btn-sm btn-primary" onclick="modify(\'' + data.id + '\')">수정</button>&nbsp'
							+ '<button type="button" class="btn btn-sm btn-danger" onclick="show_delete(\'' + data.id + '\')">삭제</button>';
				}
			}
		],
		'columnDefs': [
			{
				'targets': 6,
				'orderable': false,
				'className': 'text-center',
			}
		]
	}
);

function modify(key) {
	location.href = '/admin/settings/member/modify/' + key;
}

function show_delete(key) {
	$("#confirm_modal").attr('data-id', key);
	$("#confirm_modal").modal('show');
}

function confirmed() {
	adminDelete();
	//$("#confirm_modal").removeAttr('data-id');
	$("#confirm_modal").modal('hide');
}

function canceled() {
	$("#confirm_modal").removeAttr('data-id');
}

function adminDelete() {
	var key = $("#confirm_modal").attr('data-id');
	axios.delete('/admin/ajax/adminDelete/' + key).then((response) => {
		if(response.data != 'success') {
			toastr.error('오류가 발생하였습니다.');
			return false;
		} else {
			table.draw();
		}
	});
}
</script>
@endsection