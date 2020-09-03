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

.file_list {
	font-size: 14pt;
}

.reply_form {
	position: relative;
	width: 100%;
	height: auto;
	margin-bottom: 5px;
}

.reply_personal {
	position: relative;
	width: 100%;
	height: 30px;
	border-bottom: 1px black solid;
}

.replyer {
	position: relative;
	width: 50%;
	float: left;
	padding-left: 5px;
}

.reply_date {
	position: relative;
	width: 50%;
	float: right;
	padding-right: 5px;
	text-align: right;
}

.reply_option {
	position: relative;
	width: 100%;
	border-bottom: 1px black solid;
	padding-right: 5px;
}

.reply_text {
	position: relative;
	width: 100%;
	padding: 5px;
	border-bottom: 1px green dotted;
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
					<div class="card">
						<div class="card-header">
							@if($ideaBoard->censorship == 'Y')
							검열되었습니다.
							@else 
							{{ $ideaBoard->subject }}
							@endif
						</div>
						<div class="card-body">
							@if($ideaBoard->censorship == 'Y')
							검열되었습니다.
							@else 
							{!! $ideaBoard->contents !!}
							@endif
						</div>
						<div class="card-footer text-right">
							<div class="file_list">
								@if(count($files) > 0 && $ideaBoard->censorship != 'Y')
								<ul>
									@foreach($files as $file)
									<li id="li_{{ $file->id }}"><a href="#" onclick="file_get({{ $file->id }})">{{ $file->original_name }}</a></li>
									@endforeach
								</ul>
								@endif
							</div>
							@if(Auth::user()->id == $ideaBoard->user_id && $ideaBoard->writer == 'user')
							<button type="button" class="btn btn-success dis_check" onclick="location.href='/ideaBoard/modify/' + {{ $ideaBoard->id }}">수정</button>
							&nbsp;
							<button type="button" onclick="showDeleteModal({{ $ideaBoard->id }})" class="btn btn-danger dis_check">삭제</button>
							&nbsp;
							@endif
							<button type="button" onclick="location.href='/ideaBoard/list'" class="btn btn-primary dis_check">목록</button>
						</div>
					</div>
				</div>
				@if($ideaBoard->deleted_at == '')
				<div class="col-12">
					<br/>
					<div class="form-group">
						<textarea name="contents" id="summernote"></textarea>
					</div>
					
					<div class="form-group text-right" style="border-bottom: solid 1px red;">
						<button type="button" onclick="showReplyModal();" class="btn btn-primary dis_check">작성</button>
					</div>
				</div>
				@endif
				<div class="col-12">
					<br/>
					<section id="replys">
						<ul>
							<li v-for="list in lists">
								<div class="reply_form">
									<div class="reply_personal">
										<div class="replyer">[[ list.writer_name ]]</div>
										<div class="reply_date">[[ list.updated_at ]]</div>
									</div>
									<div class="reply_text" v-if="list.censorship == 'Y'">검열된 덧글입니다.</div>
									<div class="reply_text" v-else v-html="list.reply"></div>
									<div class="reply_option text-right" v-if="list.writer_id == authId && list.writer == 'user'">
										<button type="button" class="btn btn-sm btn-danger dis_check" @click="showReplyDelete(list.id)">삭제</button>
									</div>
								</div>
							</li>
						</ul>
						<!-- pagination -->
						<nav aria-label="..." class="float-right">
							<ul class="pagination">
								<template v-if="totalPage != 0">
									<li v-if="page == 1" class="page-item disabled">
										<span class="page-link" tabindex="-1">이전</span>
									</li>
									<li v-else class="page-item" style="cursor: pointer;">
										<span class="page-link" tabindex="-1" @click="goPage('prev')">이전</span>
									</li>
									
									<template v-for="pages in pagination">
									<li v-if="pages == page" class="page-item active">
										<span class="page-link" >[[ pages ]] <span class="sr-only">(current)</span></span>
									</li>
									<li v-else class="page-item" style="cursor: pointer;">
										<span class="page-link" @click="goPage(pages)">[[ pages ]]</span>
									</li>
									</template>
									
									
									<li v-if="totalPage == page" class="page-item disabled">
										<span class="page-link">다음</span>
									</li>
									<li v-else class="page-item" style="cursor: pointer;">
										<span class="page-link" @click="goPage('next')">다음</span>
									</li>
								</template>
							</ul>
						</nav>
						<!-- pagination -->
					<section>
				</div>
			</div>
		</section>
	</div>
</div>
@include('templates.confirm', ['confirm_title' => '게시글', 'confirm_body' => '게시글을 삭제하시겠습니까?'])
<form name="idea_form" method="post" action="/ideaBoard/delete">
{{ csrf_field() }}
<input type="hidden" name="_method" value="DELETE">
<input type="hidden" name="id" value="{{ $ideaBoard->id }}">
</form>
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
	setButtonDisable();
	$("#confirm_modal").attr('data-id', id);
	$("#confirm_modal").attr('data-param', 'board');
	$("#confirm_modal").find('h5').html('게시글 삭제');
	$("#confirm_modal").find('p').html('게시글을 삭제 하시겠습니까?<br/>덧글 내용은 삭제되지 않습니다.');
	$("#confirm_modal").modal('show');
	return false;
}

function showReplyModal() {
	setButtonDisable();
	$("#confirm_modal").attr('data-param', 'reply_write');
	$("#confirm_modal").find('h5').html('덧글');
	$("#confirm_modal").find('p').html('덧글을 작성하시겠습니까?');
	$("#confirm_modal").modal('show');
	return false;
}

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

function confirmed() {
	var dataParam = $("#confirm_modal").attr('data-param');
	
	if(dataParam == 'board') {
		onBoardDelete();
	} else if(dataParam == 'reply_write') {
		write_reply();
	} else if(dataParam == 'reply_delete') {
		deleteReply();
	}
	$("#confirm_modal").modal('hide');
}

function canceled() {
	setButtonEnable();
}

function onBoardDelete() {
	var f = document.idea_form;
	f._method.value = 'DELETE';
	f.action = '/ideaBoard/delete';
	f.submit();
}

function write_reply() {
	var id = document.getElementsByName('id')[0].value;
	var text = document.getElementsByName('contents')[0].value;
	
	axios.post('/ideaBoard/reply/write', {
		id: id,
		contents: text
	}).then(response =>{
		if(response.data == 'success') {
			$('#summernote').summernote('reset');
			toastr.info('덧글 작성에 성공하였습니다.');
			app.getBoard(1);
			setButtonEnable();
		} else if(response.data == 'id_null') {
			toastr.warning('해당 게시글이 존재하지 않습니다.');
			setButtonEnable();
			return false;
		} else if(response.data == 'content_null') {
			toastr.warning('덧글을 작성해 주세요.');
			setButtonEnable();
			document.getElementsByName('contents')[0].focus();
			return false;
		} else {
			toastr.error('에러가 발생하였습니다.');
			setButtonEnable();
			document.getElementsByName('contents')[0].focus();
			return false;
		}
	});
}

function deleteReply() {
	var id = $("#confirm_modal").attr('data-id');
	
	axios.delete('/ideaBoard/reply/delete/' + id).then(response =>{
		if(response.data == 'success') {
			toastr.info('덧글 삭제에 성공하였습니다.');
			setButtonEnable();
			app.getBoard(1);
		} else {
			toastr.error('에러가 발생하였습니다.');
			setButtonEnable()
			return false;
		}
	});
}

app = new Vue({
	el: '#replys',
	delimiters: ['[[', ']]'],
	data() {
		return {
			page: 1,
			lists: [],
			pagination: [],
			totalPage: 1,
			authId: '',
		}
	},
	mounted() {
		this.getBoard(this.page);
	},
	methods: {
		getBoard: function(page) {
			this.goSearch(page);
		},
		goPage: function(value) {
			var pageLink = value;
			if(value == 'prev') {
				pageLink = this.page - 1;
				if(pageLink < 1) {
					pageLink = 1;
				}
			} else if(value == 'next'){
				pageLink = this.page + 1;
				if(pageLink > this.totalPage) {
					pageLink = this.totalPage;
				}
			} else if(typeof value == 'number') {
				pageLink = value;
			}
			this.goSearch(pageLink);
		},
		goSearch: function(pageNum) {
			axios.get('/ideaBoard/reply/getList', {
				params: {
					page: pageNum,
					id: document.getElementsByName('id')[0].value,
				}
			}).then((response) => {
				app.page = pageNum;
				app.authId = response.data.authId;
				app.lists = response.data.lists;
				app.totalPage = response.data.pagination.totalPage;
				app.pagination = response.data.pagination.pages;
			});
		},
		showReplyDelete: function(key) {
			$("#confirm_modal").attr('data-id', key);
			$("#confirm_modal").attr('data-param', 'reply_delete');
			$("#confirm_modal").find('h5').html('덧글');
			$("#confirm_modal").find('p').html('덧글을 삭제하시겠습니까?');
			$("#confirm_modal").modal('show');
		},
	}
});
</script>
@endsection