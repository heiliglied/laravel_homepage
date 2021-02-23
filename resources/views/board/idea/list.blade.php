@extends('layouts.layout')

@section('title')
Idea Factory
@endsection

@section('heads')
<style>
[v-cloak] {
	display: none;
}

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
				<div class="col-12" id="app" v-cloak>
					<board-component ref="boardVue"></board-component>
				</div>
				@if(Auth::user())
				<div class="col-12 text-right">
					<button class="btn btn-primary" type="button" onclick="location.href='/ideaBoard/write'">글쓰기</button>
				</div>
				@endif
			</div>
		</section>
	</div>
</div>
@endsection

@section('scripts')
<script src="/mix/js/vueBoard.js"></script>
<script>
window.onpopstate = function(event) {
	var page = getParam('page');
	var search = getParam('search');
	if(page == '') {
		page = 1;
	}
	app.$refs.boardVue.getBoard(page, search);
}
</script>
@endsection