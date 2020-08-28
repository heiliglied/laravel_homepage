@extends('layouts.layout')

@section('title')
Admin
@endsection

@section('heads')
<link rel="stylesheet" type="text/css" href="/mix/css/app.css" />
<link rel="stylesheet" type="text/css" href="/plugin/adminlte/dist/css/adminlte.min.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/summernote.min.css" />
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
						<form name="idea_form" method="post" encType="multipart/form-data">
						{{ csrf_field() }}
						<input type="hidden" name="_method" value="">
						<input type="hidden" name="id" value="{{ $ideaBoard->id }}">
						<div class="card">
							<div class="card-header">
								{{ $ideaBoard->subject }}
								<span class="pull-right">asdf</span>
							</div>
							<div class="card-body">
								{!! $ideaBoard->contents !!}
							</div>
							<div class="card-footer text-right">
								<div class="file_list">
									<ul>
										@foreach($files as $file)
										<li id="li_{{ $file->id }}"><a href="#" onclick="file_get({{ $file->id }})">{{ $file->original_name }}</a></li>
										@endforeach
									</ul>
								</div>
								<button type="button" class="btn btn-success" onclick="location.href='/admin/contents/ideaBoard/modify/' + {{ $ideaBoard->id }}">수정</button>
								&nbsp;
								<button type="button" onclick="show_censorship({{ $ideaBoard->id }}, '{{ $ideaBoard->censorship }}')" class="btn btn-primary">검열</button>
								&nbsp;
								<button type="button" onclick="showDeleteModal({{ $ideaBoard->id }})" class="btn btn-danger">삭제</button>
								&nbsp;
								<button type="button" onclick="location.href='/admin/contents/ideaBoard/list'" class="btn btn-primary">목록</button>
							</div>
						</div>
					</div>
					<div class="col-12">
						<br/>
						<div class="form-group">
							<textarea name="contents" id="summernote"></textarea>
						</div>
						<div class="form-group text-right" style="border-bottom: solid 1px red;">
							<button type="button" onclick="write_reply();" class="btn btn-primary">덧글 작성</button>
						</div>
					</div>
				</div>
			</div>
		</section>
		
	</div>

</div>
@include('layouts.admin.footer')
@include('layouts.admin.righttoggle')
@include('templates.confirm', ['confirm_title' => '게시판 설정', 'confirm_body' => ''])

<form name="download" method="post" action="/admin/contents/ideaBoard/download">
{{ csrf_field() }}
<input type="hidden" name="fileId" value="">
</form>
@endsection

@section('scripts')
<script src="/mix/js/manifest.js"></script>
<script src="/mix/js/vendor.js"></script>
<script src="/mix/js/app.js"></script>
<script src="/mix/js/bootstrap.bundle.min.js"></script>
<script src="/plugin/adminlte/dist/js/adminlte.min.js"></script>
<script src="/mix/js/dataTables.bootstrap4.min.js"></script>
<script src="/mix/js/summernote.min.js"></script>
<script>
@include('errors.permission')

$(document).ready(function() {

	if(window.innerWidth >= 480) {
		$('#summernote').summernote({
			toolbar: [
				['style', ['style']],
				['fontsize', ['fontsize']],
				['font', ['bold', 'italic', 'underline', 'clear']],
				['fontname', ['fontname']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['height', ['height']],
				['table', ['table']],
				['view', ['codeview']],
				['help', ['help']]
			],
			fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica Neue', 'Helvetica', 'Impact', 'Lucida Grande', 'Tahoma', 'Times New Roman', 'Verdana', 'Nanum Gothic', 'Malgun Gothic', 'Noto Sans KR', 'Apple SD Gothic Neo'],
			fontNamesIgnoreCheck: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica Neue', 'Helvetica', 'Impact', 'Lucida Grande', 'Tahoma', 'Times New Roman', 'Verdana', 'Nanum Gothic', 'Malgun Gothic', 'Noto Sans KR', 'Apple SD Gothic Neo'],
			fontSizes: ['8','9','10','11','12','13','14','15','16','17','18','19','20','24','30','36','48','64','82','150'],
			height: 150,
			minheight: 150,
			maximumImageFileSize: 1024*1024,
		});
	} else {
		$('#summernote').summernote({
			toolbar: [
				['style', ['style']],
				['fontsize', ['fontsize']],
				['font', ['bold', 'italic', 'underline', 'clear']],
				['fontname', ['fontname']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['height', ['height']],
				['table', ['table']],
				['view', ['codeview']],
				['help', ['help']]
			],
			fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica Neue', 'Helvetica', 'Impact', 'Lucida Grande', 'Tahoma', 'Times New Roman', 'Verdana', 'Nanum Gothic', 'Malgun Gothic', 'Noto Sans KR', 'Apple SD Gothic Neo'],
			fontNamesIgnoreCheck: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica Neue', 'Helvetica', 'Impact', 'Lucida Grande', 'Tahoma', 'Times New Roman', 'Verdana', 'Nanum Gothic', 'Malgun Gothic', 'Noto Sans KR', 'Apple SD Gothic Neo'],
			fontSizes: ['8','9','10','11','12','13','14','15','16','17','18','19','20','24','30','36','48','64','82','150'],
			height: 80,
			minheight: 80,
			maximumImageFileSize: 1024*1024,
		});
	}
	
});

function file_get(id) {
	var f = document.download;
	f.fileId.value = id;
	f.submit();
}

function showDeleteModal(id) {
	$("#confirm_modal").attr('data-id', id);
	$("#confirm_modal").attr('data-param', 'delete');
	$("#confirm_modal").find('h5').html('게시글 삭제');
	$("#confirm_modal").find('p').html('게시글을 삭제 하시겠습니까?');
	$("#confirm_modal").modal('show');
	return false;
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
	var f = document.idea_form;
	f.action = '/admin/contents/ideaBoard/delete';
	f._method.value = 'DELETE';
	f.submit();
}

function boardCensored() {
	var f = document.idea_form;
	f.action = '/admin/contents/ideaBoard/censor';
	f._method.value = 'PATCH';
	f.submit();
}
</script>
@endsection