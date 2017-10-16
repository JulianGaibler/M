import Vue from 'vue'
import i18n from './i18n'
import App from './frame/template.vue'

import LocalStorageController from './classes/LocalStorageController.js'
import DataController from './classes/DataController.js'


window.bus = new Vue();

new Vue({
	el: document.getElementById("mount"),
	i18n,
	data () {
		let storageC;
		try { storageC = new LocalStorageController(i18n) } catch(e) { storageC = e };
		let dataC;
		try { dataC = new DataController() } catch(e) { dataC = e }

		return {
			storageC,
			dataC
		}
	},
	render: h => h(App),
})

