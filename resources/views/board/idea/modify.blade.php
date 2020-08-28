@extends('layouts.layout')

@section('title')
Idea Factory
@endsection

@section('heads')
<link rel="stylesheet" type="text/css" href="/mix/css/app.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/toastr.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/main.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/summernote.min.css" />
<style>
.section {
	position: relative;
	width: 90%;
	margin: 0 auto;
	padding: 20px;
}
</style>
@endsection

@section('contents')
<div class="wrapper bg-white">
	<div class="navigation bg-aliceblue">
	@include('layouts.main.nav')
	</div>
	<div class="contents bg-white">
		<section class="section">
			<div class="row">
				<div class="col-12">
					<form name="idea_form" method="post" action="/ideaBoard/update" encType="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" name="_method" value="PATCH">
					<input type="hidden" name="id" value="{{ $board->id }}">
					<div class="card">
						<div class="card-header">
							글 수정
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1"> 제목 </span>
								</div>
								<input type="text" class="form-control" placeholder="글 제목을 입력해 주세요" name="subject" required value="{{ $board->subject }}">
							</div>
							<div class="form-group">
								<textarea name="contents" id="summernote">{{ $board->contents }}</textarea>
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
							<button type="submit" class="btn btn-success">등록</button>
							&nbsp;
							<button type="button" onclick="showDeleteModal({{ $board->id }})" class="btn btn-danger">삭제</button>
							&nbsp;
							<button type="button" onclick="location.href='/ideaBoard/list'" class="btn btn-primary">목록</button>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>
@include('templates.confirm', ['confirm_title' => '첨부파일', 'confirm_body' => '파일을 삭제하시겠습니까?'])
<form name="download" method="post" action="/ideaBoard/download">
{{ csrf_field() }}
<input type="hidden" name="fileId" value="">
</form>
@endsection
@section('scripts')
<script src="/mix/js/manifest.js"></script>
<script src="/mix/js/vendor.js"></script>
<script src="/mix/js/app.js"></script>
<script src="/mix/js/bootstrap.bundle.min.js"></script>
<script src="/mix/js/summernote.min.js"></script>
<script>
$(document).ready(function() {

	if(window.innerWidth >= 480) {
		$('#summernote').summernote({
			height: 500,
			minheight: 500,
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
})

function file_get(id) {
	var f = document.download;
	f.fileId.value = id;
	f.submit();
}

function onDelete() {
	var key = $("#confirm_modal").attr('data-id');
	axios.delete('/ideaBoard/deleteFile/' + key).then((response) => {
		if(response.data == 'success') {
			toastr.info('파일을 삭제하였습니다.');
			document.getElementById('li_' + key).remove();
		} else {
			toastr.error('삭제에 실패하였습니다.');
		}
	});
}

function confirmed() {
	if($("#confirm_modal").attr('data-param') == 'board') {
		onBoardDelete();
	} else {
		onDelete();
	}
	$("#confirm_modal").modal('hide');
}

function canceled() {}

function onBoardDelete() {
	var f = document.idea_form;
	f._method.value = 'DELETE';
	f.action = '/ideaBoard/delete';
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

function showDeleteModal(id) {
	$("#confirm_modal").attr('data-id', id);
	$("#confirm_modal").attr('data-param', 'board');
	$("#confirm_modal").find('h5').html('게시글 삭제');
	$("#confirm_modal").find('p').html('게시글을 삭제 하시겠습니까?');
	$("#confirm_modal").modal('show');
	return false;
}
</script>
@endsection