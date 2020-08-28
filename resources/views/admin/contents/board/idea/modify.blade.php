@extends('layouts.layout')

@section('title')
Admin
@endsection

@section('heads')
<link rel="stylesheet" type="text/css" href="/mix/css/app.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/toastr.css" />
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
						<form name="idea_form" method="post" action="/admin/contents/ideaBoard/update" encType="multipart/form-data">
						{{ csrf_field() }}
						<input type="hidden" name="_method" value="PATCH">
						<input type="hidden" name="id" value="{{ $ideaBoard->id }}">
						<div class="card">
							<div class="card-header">
								글 수정
							</div>
							<div class="card-body">
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text" id="basic-addon1"> 제목 </span>
									</div>
									<input type="text" class="form-control" placeholder="글 제목을 입력해 주세요" name="subject" required value="{{ $ideaBoard->subject }}">
								</div>
								<div class="form-group">
									<textarea name="contents" id="summernote">{{ $ideaBoard->contents }}</textarea>
								</div>
								<div class="form-group">
									<ul>
										@foreach($files as $file)
										<li id="li_{{ $file->id }}"><a href="#" onclick="file_get({{ $file->id }})">{{ $file->original_name }}</a> <button type="button" onclick="showModal({{ $file->id }})">삭제</button></li>
										@endforeach
									</ul>
								</div>
								<div class="input-group mb-3">
									<div class="custom-file">
										<input type="file" name="files[]" class="custom-file-input" id="files" aria-describedby="fileUploadAddon" multiple lang="ko">
										<label class="custom-file-label" for="fileUploadAddon">파일을 선택하세요.</label>
									</div>
								</div>
								<div class="row">
								@foreach($errors->all() as $error)
								{{ $error }}<br/>
								@endforeach()
								</div>
							</div>
							<div class="card-footer text-right">
								<button type="submit" class="btn btn-success">수정</button>
								&nbsp;
								<button type="button" onclick="location.href='/admin/contents/ideaBoard/list'" class="btn btn-primary">목록</button>
							</div>
						</div>
						</form>
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
			height: 480,
			minheight: 480,
			maximumImageFileSize: 1024*1024,
		});
	} else {
		$('#summernote').summernote({
			height: 200,
			minheight: 200,
			maximumImageFileSize: 1024*1024,
		});
	}
	
});

document.querySelector('.custom-file-input').addEventListener('change',function(e){
	var fileName = document.getElementById("files").files;
	var fileList = '';
	Array.prototype.forEach.call(fileName, function(f, i) {
		if(i != 0) {
			fileList += ', ';
		}
		fileList += f.name;
	});
	
	var nextSibling = e.target.nextElementSibling;
	nextSibling.innerText = fileList;
});

function file_get(id) {
	var f = document.download;
	f.fileId.value = id;
	f.submit();
}

function showModal(id) {
	$("#confirm_modal").attr('data-id', id);
	$("#confirm_modal").attr('data-param', 'file');
	$("#confirm_modal").find('h5').html('첨부파일');
	$("#confirm_modal").find('p').html('파일을 삭제하시겠습니까?');
	$("#confirm_modal").modal('show');
	return false;
}

function confirmed() {
	if($("#confirm_modal").attr('data-param') == 'file') {
		fileDelete();
	}
	$("#confirm_modal").modal('hide');
}

function fileDelete() {
	var key = $("#confirm_modal").attr('data-id');
	axios.delete('/admin/ajax/ideaBoard/deleteFile/' + key).then((response) => {
		if(response.data == 'success') {
			toastr.info('파일을 삭제하였습니다.');
			document.getElementById('li_' + key).remove();
		} else {
			toastr.error('삭제에 실패하였습니다.');
		}
	});
}
</script>
@endsection