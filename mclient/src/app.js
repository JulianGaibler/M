import Vue from 'vue'
import i18n from './i18n'
import App from './frame/template.vue'

import LocalStorageController from './classes/LocalStorageController.js'
import DataController from './classes/DataController.js'

// The bus is used for sending and recieving events between components
window.bus = new Vue();

Vue.config.keyCodes = {
  nextchip: [32, 188, 13]
}

new Vue({
	el: document.getElementById("mount"),
	i18n,
	data () {
		var dataC;
		var storageC;
		try { dataC = new DataController(i18n) } catch(e) { dataC = e }
		try { storageC = new LocalStorageController(dataC, i18n) } catch(e) { storageC = e };
		dataC.setStorageC(storageC);

		return {
			storageC,
			dataC
		}
	},
	render: h => h(App),
})

