<template>
	<radioSelector :data="data" :selectedIndex="selectedIndex" :methd="change"></radioSelector>
</template>

<script>
import radioSelector from './../components/radio_selector.vue';

export default {  
	name: 'priceSelector',
	data () {
		let data = [];
		for (var i = 0; i < 3; i++) {
			data[i] = {head: this.$t('prices.category')+" "+(i+1), desc: this.$t('prices.desc_'+(i+1))}
		}
		return {
			data: data,
			selectedIndex: this.$root.$data.storageC.settings.pricetype
		}
	},
	methods: {
		change: function (nr) {
			this.$root.$data.storageC.setPricetype(nr);
		}
	},
	components: {
		radioSelector
	}
};
</script>
