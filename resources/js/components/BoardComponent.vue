<template>
<form @submit.prevent="onSubmit">
<div class="input-group mb-3">
	<input type="text" v-model="search" class="form-control" placeholder="검색어를 입력하세요." aria-label="Recipient" aria-describedby="button-search">
	<div class="input-group-append">
		<button class="btn btn-outline-secondary" type="submit" id="button-search"><i class="fas fa-search active"></i></button>
	</div>
</div>
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
			<th align="center">번호</th>
			<th align="center">제목</th>
			<th align="center">조회수</th>
			<th align="center">작성자</th>
			<th align="center">작성일</th>
		</tr>
	</thead>
	<tbody>
		<template v-if="lists.length > 0">
		<tr v-for="(list, index) in lists" :key="index">
			<td align="center">{{ list.id }}</td>
			<td style="cursor: pointer;">{{ list.subject }}</td>
			<td align="center">{{ list.view }}</td>
			<td align="center">{{ list.writer_name }}</td>
			<td align="center">{{ list.created_at }}</td>
		</tr>
		</template>
		<template v-else>
		<tr>
			<td colspan="5" align="center" style="font-size: 18px; height: 100px; vertical-align: middle; background-color: white;">해당 데이터가 없습니다.</td>
		</tr>
		</template>
	</tbody>
</table>
<nav aria-label="..." class="float-right">
	<ul class="pagination">
		<template v-if="totalPage != 0">
			<li v-if="page == 1" class="page-item disabled">
				<span class="page-link" tabindex="-1">이전</span>
			</li>
			<li v-else class="page-item" style="cursor: pointer;">
				<span class="page-link" tabindex="-1" @click="goPage('prev')">이전</span>
			</li>
			
			<template v-for="(pages, index) in pagination">
			<span :key="index"></span>
			<li v-if="pages == page" class="page-item active">
				<span class="page-link" >{{ pages }} <span class="sr-only">(current)</span></span>
			</li>
			<li v-else class="page-item" style="cursor: pointer;">
				<span class="page-link" @click="goPage(pages)">{{ pages }}</span>
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
</form>
</template>
<script>
import BoardScript from './scripts/BoardScript.js'
export default BoardScript;
</script>