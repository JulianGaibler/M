<template>
	<radioSelector :data="data" :selectedIndex="sindex" :methd="change"></radioSelector>
</template>

<script>
import radioSelector from './../components/radio_selector.vue';

export default {  
	name: 'languageSelector',
	data () {
		let data = [
			{head: this.$t('language.de'), desc: this.$t('language.de', 'en')},
			{head: this.$t('language.en'), desc: this.$t('language.en', 'en')}
		];

		let lang = this.$root.$data.storageC.settings.language;
		let langnr = (lang==='de')?0:1;
		return {
			sindex: langnr,
			data: data
		}
	},
	methods: {
		change: function (lang) {
			switch(lang) {
				case 0:
					lang = 'de';
					break;
				default:
					lang = 'en';
			}
			this.$i18n.locale = lang;
			this.$root.$data.storageC.settings.language = lang;
			this.$root.$data.storageC.updateStorage();
		}
	},
	components: {
		radioSelector
	}
};
</script>
