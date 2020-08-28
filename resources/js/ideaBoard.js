window.app = new Vue({
	el: '#app',
	delimiters: ['[[', ']]'],
	data() {
		return {
			page: 1,
			lists: ['temp'],
			search: '',
			pagination: [],
			totalPage: 1,
		}
	},
	mounted() {
		this.getBoard(this.page, this.search);
	},
	methods: {
		getBoard: function(page, search) {
			this.goSearch(page, search, 'initial');
		},
		goPage: function(value) {
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
		goSearch: function(pageNum, searchValue, initail) {
			axios.get('/ideaBoard/getList', {
				params: {
					page: pageNum,
					search: searchValue,
				}
			}).then((response) => {
				console.log(response.data.lists);
				app.page = pageNum;
				app.lists = response.data.lists;
				app.totalPage = response.data.pagination.totalPage;
				app.pagination = response.data.pagination.pages;
				
				if(initail != 'initial') {
					var url = '//' + location.host + location.pathname + '?page=' + this.page + '&search=' + this.search;
					history.pushState('', '', url);
				}
			});
		},
		onSubmit: function() {
			this.goSearch(1, this.search);
		},
		onShow: (id) => {
			location.href = '/ideaBoard/view/' + id;
		}
	}
});