//import BoardJs from './components/scripts/BoardScript.js'
import BoardComponent from './components/BoardComponent.vue'

export const EventBus = new Vue({
	el: '#app',	
	components: {
		'board-component': BoardComponent
	}
});