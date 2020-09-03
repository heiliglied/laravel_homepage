var table = $("#idea_list").DataTable(
	{
		'serverSide': true,
		'processing': true,
		'lengthMenu': [10, 20, 50, 100],
		'ajax': {
			'url': '/admin/ajax/ideaList',
			'type': 'GET',
			'dataSrc': function(response) {
				let data = response.data;
				return data;
			}
		},
		'columns': [
			{
				'data': 'id',
				'render': function(data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{'data': 'writer_name'},
			{
				'data': 'subject',
				'mRender': function(data, type, row, meta) {
					return '<span onclick="goView(\'' + row.id + '\')" style="cursor: pointer; text-decoration: underline;">' + row.subject + '</span>';
				}
			},
			{'data': 'files'},
			{'data': 'replis'},
			{'data': 'updated_at'},
			{
				'data': 'censorship',
				'render': function(data, type, row, meta) {
					if(data == 'Y') {
						return '검열됨';
					} else {
						return '정상';
					}
				}
			},
			{
				'data': null,
				'render': function(data, type, row, meta) {
					return '<button type="button" class="btn btn-sm btn-primary dis_check" onclick="show_censorship(\'' + data.id + '\', \'' + data.censorship + '\')">검열</button>&nbsp'
							+ '<button type="button" class="btn btn-sm btn-danger dis_check" onclick="show_delete(\'' + data.id + '\')">삭제</button>';
				}
			}
		],
		'columnDefs': [
			{
				'targets': 7,
				'orderable': false,
				'searchable': false,
				'className': 'text-center',
			}
		],
		'order': [
			[
				0, "desc"
			]
		]
	}
);

function goView(id) {
	location.href='/admin/contents/ideaBoard/view/' + id;
}

function show_censorship(key, status) {
	setButtonDisable();
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
	setButtonDisable();
	$("#confirm_modal").attr('data-id', key);
	$("#confirm_modal").attr('data-param', 'delete');
	$("#confirm_modal").find('h5').html('아이디어보드');
	$("#confirm_modal").find('p').html('게시글을 삭제하시겠습니까?<br/>덧글, 파일을 포함한 내용이 전부 삭제됩니다.');
	$("#confirm_modal").modal('show');
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
	var key = $("#confirm_modal").attr('data-id');
	axios.delete('/admin/ajax/ideaList/delete/' + key).then(result => {
		if(result.data == 'success') {
			table.draw();
			setButtonEnable();
		} else {
			toastr.error('오류가 발생하였습니다.');
			setButtonEnable();
			return false;
		}
	});
}

function boardCensored() {
	var key = $("#confirm_modal").attr('data-id');
	axios.patch('/admin/ajax/ideaList/censor/' + key).then(result => {
		if(result.data == 'success') {
			table.draw();
			setButtonEnable();
		} else {
			toastr.error('오류가 발생하였습니다.');
			setButtonEnable();
			return false;
		}
	});
}