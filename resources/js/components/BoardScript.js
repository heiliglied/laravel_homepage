const funcs = {
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
	goSearch(pageNum, searchValue, initail) {
		axios.get('/ideaBoard/getList', {
			params: {
				page: pageNum,
				search: searchValue,
			}
		}).then((response) => {
			this.page = pageNum;
			this.totalPage = response.data.pagination.totalPage;
			this.lists = response.data.lists;
			this.pagination = response.data.pagination;
			/*
			this.lists.filter(response.data.lists => {
				return response.data.lists;
			});
			
			this.pagination.filter(response.data.pagination => {
				return response.data.pagination;
			});
			*/
			if(initail != 'initial') {
				var url = '//' + location.host + location.pathname + '?page=' + pageNum + '&search=' + searchValue;
				history.pushState('', '', url);
			}
		});
	},
	onSubmit() {
		this.goSearch(1, this.search);
	}
}

export default funcs