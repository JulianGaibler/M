<template>
	<div>
		<radioSelector :data="[ {head: this.$t('labels.everything')},
								{head: this.$t('labels.vegetarian')},
								{head: this.$t('labels.vegan')}]"
					   :selectedIndex="selectedIndex"
					   :methd="change"></radioSelector>
		<hr>
		<textSwitch v-model="enabled" :text="$t('settings.hideadditives')"></textSwitch>
		<errorMsg v-if="error"></errorMsg>
		<chipsfield v-else-if="enabled" :items="this.$root.$data.storageC.settings.additives" :suggestions="suggestions"></chipsfield>
	</div>
</template>

<script>
import radioSelector from './../components/radio_selector.vue';
import chipsfield from './../components/chipsfield.vue';
import textSwitch from './../components/text_switch.vue';
import errorMsg from './../components/errormsg.vue';

import Helpers from './../classes/Helpers.js'

export default {  
	name: 'dietSelector',
	data () {
		let l = this.$root.$data.storageC.settings.additives.length;
		return {
			selectedIndex: this.$root.$data.storageC.settings.diet,
			suggestions: [],
			error: false,
			enabled: (l > 0)
		}
	},
	mounted() {
		if (this.enabled) this.toggleData(true);
	},
	methods: {
		change: function (nr) {
			this.$root.$data.storageC.setDiettype(nr);
		},
		toggleData: function(val) {
			this.error = false;
			if (val && this.suggestions.length < 1) {
				this.$root.$data.dataC.getAdditives().then((result) => {
					let lang = this.$root.$data.storageC.settings.language;
					this.suggestions = Helpers.translateAdditives(result, null, lang);
				},
				(reason) => {
					this.error = true;
				});
			} else {
				this.$root.$data.storageC.settings.additives = [];
				this.$root.$data.storageC.updateStorage();
			}
		}
	},
	watch: {
		enabled: function(val) {
			this.toggleData(val);
		}
	},
	components: {
		radioSelector,
		chipsfield,
		textSwitch,
		errorMsg
	}
};
</script>
