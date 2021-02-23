@extends('layouts.adminLayout')

@section('title')
Admin
@endsection

@section('heads')
<link rel="stylesheet" type="text/css" href="/plugin/adminlte/dist/css/adminlte.min.css" />
<style>
[v-cloak] {
	display: none;
}
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
						<h1 class="m-0 text-dark">관리자 등급 설정</h1>
					</div><!-- /.col -->
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/settings/rank">환경설정</a></li>
							<li class="breadcrumb-item active">관리자 등급 설정</li>
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
								<h3 class="card-title">관리자 등급</h3>
							</div>
							<!-- /.card-header -->
							<div class="card-body">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th style="width: 60px">등급</th>
											<th>등급명</th>
											<th style="width: 10vw">Action</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="items in lists">
											<td align="center">[[ items.rank ]]</td>
											<td>[[ items.name ]]</td>
											<td align="center">
												<button type="button" class="btn btn-primary btn-sm" style="cursor: pointer" v-on:click="rankModify(items.rank, items.name)">수정</button>
												&nbsp;
												<button type="button" class="btn btn-danger btn-sm" style="cursor: pointer" v-on:click="deleteConfirm(items.rank)">삭제</button>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<br/>
				<br/>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">랭크 등록</h3>
							</div>
							<div class="card-body">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th style="width: 80px">등급</th>
											<th>등급명</th>
											<th v-if="write_mode == 'insert'" style="width: 60px">
												Action
											</th>
											<th v-else style="width: 10vw">
												Action
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<select name="rank" class="form-control">
													@for($i = 1; $i <= 9; $i++)
													<option value="{{ $i }}">{{ $i }}</option>
													@endfor
												</select>
											</td>
											<td>
												<input type="text" name="name" value="" class="form-control">
											</td>
											<td v-if="write_mode == 'insert'">
												<button type="button" style="cursor: pointer" onclick = "rank.writeRank()" class="btn btn-info btn-sm">등록</button>
											</td>
											<td v-else>
												<button type="button" style="cursor: pointer" onclick = "rank.update()" class="btn btn-success btn-sm">수정</button>
												&nbsp;
												<button type="button" style="cursor: pointer" onclick = "rank.cancel()" class="btn btn-info btn-sm">취소</button>
												
											</td>
										</tr>
									</tbody>
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
@include('templates.confirm', ['confirm_title' => '등급 설정', 'confirm_body' => '등급을 삭제하시겠습니까?'])
@endsection

@section('scripts')
<script src="/plugin/adminlte/dist/js/adminlte.min.js"></script>
<script>
@include('errors.permission')
const rank = new Vue({
	el: "#rank_body",
	delimiters: ['[[', ']]'],
	data: {
		page: 1,
		lists: [],
		rank: document.getElementsByName('rank')[0].value, 
		name: document.getElementsByName('name')[0].value,
		write_mode: 'insert',
	},
	mounted() {
		this.getRankList(1);
	},
	methods: {
		getRankList: (page) => {
			axios.get('/admin/ajax/adminRankList', {
				page: page
			}).then((response) => {
				rank.lists = response.data.lists;
			});
		}, 
		writeRank: () => {
			this.rank = document.getElementsByName('rank')[0].value;
			this.name = document.getElementsByName('name')[0].value;
			this.write_mode = 'insert';
			
			if(rank.rankCheck() == 'name_invalid') {
				toastr.warning('등록명을 입력해 주세요.');
				return false;
			}
			
			axios.post('/admin/ajax/adminRankInsert', {
				rank: this.rank,
				name: this.name,
				write_mode: this.write_mode,
			}).then((response)=>{
				let return_message = response.data;
				
				if(return_message == 'duplicate') {
					toastr.error('이미 등록된 랭크입니다.');
					return false;
				} else if(return_message == 'error') {
					toastr.error('오류가 발생하였습니다.');
					return false;
				}
				
				document.getElementsByName('name')[0].value = '';
				rank.getRankList(1);
			});
		},
		rankCheck: () => {
			if(this.name == '') {
				return 'name_invalid';
			}
		},
		deleteConfirm: (key) => {
			this.rank = key;
			$("#confirm_modal").modal('show');
		},
		
		rankDelete: () => {
			axios.delete('/admin/ajax/adminRankDelete', {
				data: {
					rank: this.rank,
				}
			}).then((response)=>{
				if(response.data != 'success') {
					toastr.error('오류가 발생하였습니다.');
					return false;
				}
				rank.getRankList(1);
			});
		},
		rankModify: (key, name) => {
			rank.write_mode = 'update';
			document.getElementsByName('rank')[0].value = key;
			document.getElementsByName('name')[0].value = name;
			var op = document.getElementsByName("rank")[0].getElementsByTagName("option");
			for (var i = 0; i < op.length; i++) {
			// lowercase comparison for case-insensitivity
				(op[i].value.toLowerCase() != key) ? op[i].disabled = true  : op[i].disabled = false ;
			}
		},
		cancel: () => {
			rank.write_mode = 'insert';
			document.getElementsByName('name')[0].value = '';
			rank.rankRelease();
		},
		rankRelease: () => {
			var op = document.getElementsByName("rank")[0].getElementsByTagName("option");
			for (var i = 0; i < op.length; i++) {
				op[i].disabled = false;
			}
		},
		update: () => {
			let key = document.getElementsByName('rank')[0].value;
			let name = document.getElementsByName('name')[0].value;
			
			axios.patch('/admin/ajax/adminRankUpdate', {
				rank: key,
				name: name,
			}).then((response)=>{
				let return_message = response.data;
				
				if(return_message == 'error') {
					toastr.error('오류가 발생하였습니다.');
					return false;
				}
				
				rank.write_mode = 'insert';
				document.getElementsByName('name')[0].value = '';
				rank.rankRelease();
				rank.getRankList(1);
			});
		}
	},
});

function confirmed() {
	rank.rankDelete();
	$("#confirm_modal").modal('hide');
}
</script>
@endsection