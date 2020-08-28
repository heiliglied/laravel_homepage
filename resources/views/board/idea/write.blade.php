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
					<form name="idea_form" method="post" action="/ideaBoard/create" encType="multipart/form-data">
					{{ csrf_field() }}
					<div class="card">
						<div class="card-header">
							글 작성
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1"> 제목 </span>
								</div>
								<input type="text" class="form-control" placeholder="글 제목을 입력해 주세요" name="subject" required>
							</div>
							<div class="form-group">
								<textarea name="contents" id="summernote"></textarea>
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
							<button type="button" onclick="location.href='/ideaBoard/list'" class="btn btn-primary">목록</button>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>
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
</script>
@endsection