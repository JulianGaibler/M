import Vue from 'vue'
import VueI18n from 'vue-i18n'

Vue.use(VueI18n)

const i18n = new VueI18n({
	locale: 'en',
	messages: {
		'en': require('./en.json'),
		'de': require('./de.json')
	}
})

if (module.hot) {
	module.hot.accept(['./en.json', './de.json'], () => {
		i18n.setLocaleMessage('en', require('./en.json'))
		i18n.setLocaleMessage('de', require('./de.json'))
	})
}

export default i18n