@extends('layouts.layout')

@section('title')
Idea Factory
@endsection

@section('heads')
<link rel="stylesheet" type="text/css" href="/mix/css/app.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/toastr.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/main.css" />
<style>
.fiddler {
	background-color: transparent !important;
}

.fiddler_contents {
	position: relative;
	width: 100%;
	min-height: calc(100% - 110px);
}

.list_layer {
	position: absolute;
	right: 0;
	width: 200px;
	height: auto;
	background-color: white;
	z-index: 20;
	display: none;
	border: solid 2px black;
}

#myList a {
	color: blue;
}
</style>
@endsection

@section('contents')
<div class="wrapper bg-white">
	<div class="navigation bg-aliceblue">
	@include('layouts.main.nav')
	</div>
	
	<nav class="navbar navbar-expand-md navbar-light">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#fiddleNavBar">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="fiddleNavBar">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item fiddler">
					<a class="nav-link" href="javascript:onResult()">실행</a>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				@if(auth()->check())
				<li class="nav-item fiddler">
					<a class="nav-link" href="javascript:showModal();">저장</a>
				</li>
				@if($random_key != '')
				<li class="nav-item fiddler">
					<a class="nav-link" href="javascript:showDeleteModal();">삭제</a>
				</li>	
				@endif
				<li class="nav-item fiddler">
					<a class="nav-link" href="/zzapfiddler">새 문서</a>
				</li>
				<li class="nav-item fiddler">
					<a class="nav-link" href="javascript:showList();">내 목록</a>
					<div class="list_layer" id="link_list">
						<div style="width: 100%; text-align: right;">
							<i class="fas fa-window-close fa-2x" onclick="close_link();"></i>
						</div>
						<div style="color: blue; font-size: 18px; text-align: center" id="myList">
							
						</div>
					</div>
				</li>
				@endif
			</ul>
		</div>
	</nav>
	<form name="fiddler_form" method="post" action="/zzapfiddler/save">
	{{ csrf_field() }}
	<input type="hidden" name="random_key" value="{{ $random_key }}">
	<input type="hidden" name="_method" value="">
	<div class="fiddler_contents bg-white">
		<div style="position: relative; width: 99%; height: auto; margin: 0 auto;">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6">
						<label for="html_input">HTML</label>
						<textarea id="html_input" name="html_input" class="text_area" style="width: 99%; height: 350px; resize: none; background-color: black; color: white;">{{ $html }}</textarea>
					</div>
					<div class="col-md-6">
						<label> CSS 
						<select name="css_type">
							<option value="css" @if($css_type == 'css') selected @endif>CSS</option>
							<option value="scss" @if($css_type == 'scss') selected @endif>SCSS</option>
							<option value="sass" @if($css_type == 'sass') selected @endif>SASS</option>
						</select>
						</label>
						<textarea id="css_input" name="css_input" class="text_area" style="width: 99%; height: 350px; resize: none; background-color: black; color: white;">{{ $css }}</textarea>
					</div>
					<div class="col-md-6">
						<label> Javascript 
						<select name="js_type">
							<option value="es5" @if($css_type == 'es5') selected @endif>ES5</option>
							<option value="es6" @if($css_type == 'es6') selected @endif>ES6</option>
						</select>
						</label>
						<textarea id="js_input" name="js_input" class="text_area" style="width: 99%; height: 350px; resize: none; background-color: black; color: white;">{{ $script }}</textarea>
					</div>
					<div class="col-md-6">
						<label> Result </label>
						<div style="width: 99%; height: 350px; border: solid 1px black;">
							<iframe id="show_result" src="" width="100%" height="100%"></iframe>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</form>
</div>
@endsection
@include('templates.confirm', ['confirm_title' => '짭피들러', 'confirm_body' => '내용을 저장하시겠습니까?'])
@section('scripts')
<script src="/mix/js/manifest.js"></script>
<script src="/mix/js/vendor.js"></script>
<script src="/mix/js/app.js"></script>
<script src="/mix/js/bootstrap.bundle.min.js"></script>
<script>
@if(session('msg'))
	toastr.error("{{ session('msg') }}");
@endif

function confirmed() {
	onSave();
	$("#confirm_modal").modal('hide');
}

function canceled() {
	
}

function showModal() {
	$("#confirm_modal").modal('show');
	$("#confirm_modal").attr('data-param', 'save');
	$("#confirm_modal").find('h5').html('짭피들러');
	$("#confirm_modal").find('p').html('내용을 저장하시겠습니까?');
	return false;
}

function showDeleteModal() {
	$("#confirm_modal").modal('show');
	$("#confirm_modal").attr('data-param', 'delete');
	$("#confirm_modal").find('h5').html('짭피들러');
	$("#confirm_modal").find('p').html('내용을 삭제하시겠습니까?');
	return false;
}

function onSave() {
	if($("#confirm_modal").attr('data-param') == 'delete') {
		document.fiddler_form._method.value = 'DELETE';
		document.fiddler_form.action = '/zzapfiddler/delete';
	} else {
		document.fiddler_form._method.value = '';
		document.fiddler_form.action = '/zzapfiddler/save';
	}
	document.fiddler_form.submit();
}

function onResult() {
	delete localStorage["html"];
	delete localStorage["css"];
	delete localStorage["js"];
	
	var html_val = document.getElementsByName('html_input')[0].value;
	var css_val = document.getElementsByName('css_input')[0].value;
	var js_val = document.getElementsByName('js_input')[0].value;

	localStorage.setItem('js', js_val);
	localStorage["html"] = html_val;
	localStorage["css"] = css_val;
	
	loadPage();
}

function loadPage() {
	document.getElementById("show_result").setAttribute('src', '/zzapfiddler/show/result');
}

function showList() {
	axios.post('/zzapfiddler/getList', {
		
	}).then(result => {
		var links = "";
		Array.prototype.forEach.call(result.data, (datas, i)=>{
			var line = '';
			if(i != 0) {
				line = '<br/>';
			}
			if(datas.random_key == '{{ $random_key }}') {
				links += line + 'now ' + datas.random_key;
			} else {
				links += line + '<a href="/zzapfiddler/' + datas.random_key + '">' + datas.random_key + '</a>';
			}
		});
		document.getElementById("myList").innerHTML = links;
		document.getElementById("link_list").style.display = 'block';
	});
}

function close_link() {
	document.getElementById("link_list").style.display = 'none';
}
</script>
@endsection

