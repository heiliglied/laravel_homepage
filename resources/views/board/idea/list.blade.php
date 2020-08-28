@extends('layouts.layout')

@section('title')
Idea Factory
@endsection

@section('heads')
<link rel="stylesheet" type="text/css" href="/mix/css/app.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/toastr.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/main.css" />
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
					<!--<board-component></board-component>-->
					<!-- search bar -->
					<form @submit.prevent="onSubmit">
					<div class="input-group mb-3">
						<input type="text" v-model="search" class="form-control" placeholder="검색어를 입력하세요." aria-label="Recipient" aria-describedby="button-search">
						<div class="input-group-append">
							<button class="btn btn-outline-secondary" type="submit" id="button-search"><i class="fas fa-search active"></i></button>
						</div>
					</div>
					</form>
					<!-- search bar -->
					<table class="table table-striped">
						<colgroup>
							<col width="8%"/>
							<col width=""/>
							<col width="10%"/>
							<col width="15%"/>
							<col width="15%"/>
						</colgroup>
						<thead>
							<tr>
								<th>번호</th>
								<th>제목</th>
								<th>조회수</th>
								<th>작성자</th>
								<th>작성일</th>
							</tr>
						</thead>
						<tbody>
							<template v-if="lists.length > 0">
							<tr v-for="list in lists" @click="onShow(list.id)">
								<td align="center">[[ list.id ]]</td>
								<td v-if="list.censorship == 'Y'" style="cursor: pointer;">검열되었습니다.</td>
								<td v-else style="cursor: pointer;">[[ list.subject ]]</td>
								<td align="center">[[ list.view ]]</td>
								<td align="center">[[ list.writer_name ]]</td>
								<td align="center">[[ list.created_at ]]</td>
							</tr>
							</template>
							<template v-else>
							<tr>
								<td colspan="5" align="center" style="font-size: 18px; height: 100px; vertical-align: middle; background-color: white;">해당 데이터가 없습니다.</td>
							</tr>
							</template>
						</tbody>
					</table>
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
<script src="/mix/js/manifest.js"></script>
<script src="/mix/js/vendor.js"></script>
<script src="/mix/js/app.js"></script>
<script src="/mix/js/bootstrap.bundle.min.js"></script>
<script src="{{ mix('/mix/js/ideaBoard.js') }}"></script>
<script>
window.onpopstate = function(event) {
	var page = getParam('page');
	var search = getParam('search');
	if(page == '') {
		page = 1;
	}
	app.getBoard(page, search);
}
</script>
@endsection