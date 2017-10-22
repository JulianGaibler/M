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
		var dataC;
		var storageC;
		try { storageC = new LocalStorageController(i18n) } catch(e) { storageC = e };
		try { dataC = new DataController(storageC, i18n) } catch(e) { dataC = e }
		storageC.setDataC(dataC);

		return {
			storageC,
			dataC
		}
	},
	render: h => h(App),
})

