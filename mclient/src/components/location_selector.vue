<template>
	<div>
		<radioSelector :data="[ {head: this.$t('settings.location.never')},
								{head: this.$t('settings.location.ask')},
								{head: this.$t('settings.location.always')}]"
					   :selectedIndex="selectedIndex"
					   :methd="change"></radioSelector>
	</div>
</template>

<script>
import radioSelector from './../components/radio_selector.vue';


export default {  
	name: 'locationSelector',
	data () {
		return {
			selectedIndex: this.$root.$data.storageC.settings.location
		}
	},
	methods: {
		change: function (nr) {
			this.$root.$data.storageC.setLocation(nr);
			console.log(this.$root.$data.storageC.settings.location);
		}
	},
	components: {
		radioSelector
	}
};
</script>
