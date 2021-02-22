const funcs = {
	data: function() {
		return {
			page: getParam('page') ? getParam('page') : 1,
			lists: ['temp'],
			search: getParam('search') ? getParam('search') : '',
			pagination: [],
			totalPage: 1,
		}
	},
	mounted() {
		this.getBoard(this.page, this.search);
	},
	methods: {
		getBoard(page, search) {
			this.goSearch(page, search, 'initial');
		},
		goPage(value) {
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
			this.goSearch(pageLink, this.search);
		},
		goSearch(pageNum, searchValue, initial) {
			axios.get('/ideaBoard/getList', {
				params: {
					page: pageNum,
					search: searchValue,
				}
			}).then((response) => {
				this.page = pageNum;
				this.lists = response.data.lists;
				this.totalPage = response.data.pagination.totalPage;
				this.pagination = response.data.pagination.pages;
				
				if(initial != 'initial') {
					var url = '//' + location.host + location.pathname + '?page=' + pageNum + '&search=' + searchValue;
					history.pushState('', '', url);
				}
			});
		},
		goView(id) {
			location.href='/ideaBoard/view/' + id;
		},
		onSubmit() {
			this.goSearch(1, this.search);
		},
		getParam(name) {
			var params = location.search.substr(location.search.indexOf("?") + 1);
			var sval = "";
			params = params.split("&");
			for (var i = 0; i < params.length; i++) {
				temp = params[i].split("=");
				if ([temp[0]] == name) { 
					sval = temp[1]; 
				}
			}
			return sval;
		}
	}
}

export default funcs