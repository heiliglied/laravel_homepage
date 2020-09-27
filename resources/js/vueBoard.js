//import BoardJs from './components/scripts/BoardScript.js'
import BoardComponent from './components/BoardComponent.vue'

var EventBus = new Vue();
window.app = new Vue({
	el: '#app',	
	components: {
		'board-component': BoardComponent
	},
	/*
	mounted() {
		this.getBoard(this.page, this.search);
	},
	*/
});