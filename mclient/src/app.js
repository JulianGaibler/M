import Vue from 'vue'
import i18n from './i18n'
import App from './frame/template.vue'

import LocalStorageController from './classes/LocalStorageController.js'
import NetworkController from './classes/NetworkController.js'

// The bus is used for sending and recieving events between components
window.bus = new Vue();

Vue.config.keyCodes = {
  nextchip: [32, 188, 13]
}

new Vue({
	el: document.getElementById("mount"),
	i18n,
	data () {
		var netC;
		var storageC;
		try { netC = new NetworkController(i18n) } catch(e) { netC = e }
		try { storageC = new LocalStorageController(netC, i18n) } catch(e) { storageC = e };
		netC.setStorageC(storageC);

		return {
			storageC,
			netC
		}
	},
	render: h => h(App),
})

