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
			app.page = pageNum;
			app.lists = response.data.lists;
			app.totalPage = response.data.pagination.totalPage;
			app.pagination = response.data.pagination.pages;
			
			if(initail != 'initial') {
				var url = '//' + location.host + location.pathname + '?page=' + pageNum + '&search=' + searchValue;
				history.pushState('', '', url);
			}
		});
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

export default funcs