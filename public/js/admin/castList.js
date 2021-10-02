var table = $("#cast_list").DataTable(
	{
		'serverSide': true,
		'processing': true,
		'lengthMenu': [10, 20, 50, 100],
		'ajax': {
			'url': '/admin/test/castList',
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
			{'data': 'normal'},
			{'data': 'casted'},
			{'data': 'created_at'},
		],
		'columnDefs': [
			{
				'targets': 0,
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