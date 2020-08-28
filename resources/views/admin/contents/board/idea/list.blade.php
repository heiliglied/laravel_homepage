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
@include('templates.confirm', ['confirm_title' => '피들러 설정', 'confirm_body' => '해당 스크립트를 삭제하시겠습니까?'])
@endsection

@section('scripts')
<script src="/mix/js/manifest.js"></script>
<script src="/mix/js/vendor.js"></script>
<script src="/mix/js/app.js"></script>
<script src="/mix/js/bootstrap.bundle.min.js"></script>
<script src="/plugin/adminlte/dist/js/adminlte.min.js"></script>
<script src="/mix/js/dataTables.bootstrap4.min.js"></script>
<script>
@include('errors.permission')
let table = $("#idea_list").DataTable(
	{
		'serverSide': true,
		'processing': true,
		'lengthMenu': [10, 20, 50, 100],
		'ajax': {
			'url': '/admin/ajax/ideaList',
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
			{'data': 'writer_name'},
			{
				'data': 'subject',
				'mRender': function(data, type, row, meta) {
					return '<span onclick="goView(\'' + row.id + '\')" style="cursor: pointer; text-decoration: underline;">' + row.subject + '</span>';
				}
			},
			{'data': 'files'},
			{'data': 'replis'},
			{'data': 'updated_at'},
			{
				'data': 'censorship',
				'render': function(data, type, row, meta) {
					if(data == 'Y') {
						return '검열됨';
					} else {
						return '정상';
					}
				}
			},
			{
				'data': null,
				'render': function(data, type, row, meta) {
					return '<button type="button" class="btn btn-sm btn-primary" onclick="show_censorship(\'' + data.id + '\', \'' + data.censorship + '\')">검열</button>&nbsp'
							+ '<button type="button" class="btn btn-sm btn-danger" onclick="show_delete(\'' + data.id + '\')">삭제</button>';
				}
			}
		],
		'columnDefs': [
			{
				'targets': 7,
				'orderable': false,
				'searchable': false,
				'className': 'text-center',
			}
		],
		'order': [
			[
				0, "desc"
			]
		]
	}
);

function goView(id) {
	location.href='/admin/contents/ideaBoard/view/' + id;
}

function show_censorship(key, status) {
	$("#confirm_modal").attr('data-id', key);
	$("#confirm_modal").attr('data-param', 'censored');
	$("#confirm_modal").find('h5').html('아이디어보드');
	if(status == 'N') {
		$("#confirm_modal").find('p').html('게시글을 검열하시겠습니까?<br/>사용자에게는 내용이 표시되지 않습니다.');
	} else {
		$("#confirm_modal").find('p').html('검열을 해지하시겠습니까?');
	}
	
	$("#confirm_modal").modal('show');
}

function show_delete(key) {
	$("#confirm_modal").attr('data-id', key);
	$("#confirm_modal").attr('data-param', 'delete');
	$("#confirm_modal").find('h5').html('아이디어보드');
	$("#confirm_modal").find('p').html('게시글을 삭제하시겠습니까?<br/>덧글, 파일을 포함한 내용이 전부 삭제됩니다.');
	$("#confirm_modal").modal('show');
}

function confirmed() {
	var param = $("#confirm_modal").attr('data-param');
	if(param == 'delete') {
		boardDelete();
	} else {
		boardCensored();
	}
	$("#confirm_modal").modal('hide');
}

function canceled() {
	$("#confirm_modal").removeAttr('data-id');
	$("#confirm_modal").removeAttr('data-param');
}

function boardDelete() {
	var key = $("#confirm_modal").attr('data-id');
	axios.delete('/admin/ajax/ideaList/delete/' + key).then(result => {
		if(result.data == 'success') {
			table.draw();
		} else {
			toastr.error('오류가 발생하였습니다.');
			return false;
		}
	});
}

function boardCensored() {
	var key = $("#confirm_modal").attr('data-id');
	axios.patch('/admin/ajax/ideaList/censor/' + key).then(result => {
		if(result.data == 'success') {
			table.draw();
		} else {
			toastr.error('오류가 발생하였습니다.');
			return false;
		}
	});
}
</script>
@endsection