@extends('layouts.adminLayout')

@section('title')
Admin
@endsection

@section('heads')
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
							<li class="breadcrumb-item"><a href="/admin/contents/fiddler">짭피들러</a></li>
							<li class="breadcrumb-item active">피들러 관리</li>
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
								<h3 class="card-title">피들러 관리</h3>
							</div>
							<!-- /.card-header -->
							<div class="card-body">
								<table id="fiddler_list" class="table table-bordered table-hover">
									<thead>
										<th>No.</th>
										<th>ID</th>
										<th>작성자</th>
										<th>고유값</th>
										<th>생성일</th>
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
@include('templates.confirm', ['confirm_title' => '피들러 설정', 'confirm_body' => '해당 스크립트를 삭제하시겠습니까?'])
@endsection

@section('scripts')
<script src="/plugin/adminlte/dist/js/adminlte.min.js"></script>
<script src="/mix/js/dataTables.bootstrap4.min.js"></script>
<script>
@include('errors.permission')
let table = $("#fiddler_list").DataTable(
	{
		'serverSide': true,
		'processing': true,
		'lengthMenu': [10, 20, 50, 100],
		'ajax': {
			'url': '/admin/ajax/FiddlerList',
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
			{'data': 'user_id'},
			{'data': 'name'},
			{'data': 'random_key'},
			{'data': 'updated_at'},
			{
				'data': null,
				'render': function(data, type, row, meta) {
					return '<button type="button" class="btn btn-sm btn-primary" onclick="show(\'' + data.random_key + '\')">보기</button>&nbsp'
							+ '<button type="button" class="btn btn-sm btn-danger" onclick="show_delete(\'' + data.id + '\')">삭제</button>';
				}
			}
		],
		'columnDefs': [
			{
				'targets': 5,
				'orderable': false,
				'className': 'text-center',
			}
		]
	}
);

function setButtonDisable() {
	var btns = document.getElementsByClassName("dis_check");
	Array.prototype.forEach.call(btns, function(e){
		e.setAttribute('disabled', 'disabled');
	});
}

function setButtonEnable() {
	var btns = document.getElementsByClassName("dis_check");
	Array.prototype.forEach.call(btns, function(e){
		e.removeAttribute('disabled');
	});
}

function show(key) {
	var link = window.open('/zzapfiddler/' + key, '_blank');
	link.focus();
}

function show_delete(key) {
	setButtonDisable();
	$("#confirm_modal").attr('data-id', key);
	$("#confirm_modal").modal('show');
}

function confirmed() {
	fiddlerDelete();
	//$("#confirm_modal").removeAttr('data-id');
	$("#confirm_modal").modal('hide');
}

function canceled() {
	$("#confirm_modal").removeAttr('data-id');
}

function fiddlerDelete() {
	var key = $("#confirm_modal").attr('data-id');
	axios.delete('/admin/ajax/fiddlerDelete/' + key).then((response) => {
		if(response.data != 'success') {
			toastr.error('오류가 발생하였습니다.');
			setButtonEnable();
			return false;
		} else {
			table.draw();
		}
	});
}
</script>
@endsection