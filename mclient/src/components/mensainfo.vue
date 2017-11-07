<template>
	<div :class="$style.headline">
		<span v-if="basicdata">
			<h1>{{basicdata.nameA}}</h1>
			<h2>{{basicdata.nameB}}</h2>
		</span>
		<span v-if="basicdata===false">
			<loadGlow :extStyle="[$style.loading, $style.loadh1]" :dimension="{min:40, max:50, end: '%'}"></loadGlow>
			<loadGlow :extStyle="[$style.loading, $style.loadh2]" :dimension="{min:60, max:80, end: '%'}"></loadGlow>
		</span>
		<loadGlow v-if="!additionaldata" :extStyle="$style.loading" :dimension="{min:30, max:60, end: '%'}"></loadGlow>
		<span v-else><openingTimes :times="additionaldata.location.times"></openingTimes></span>
	</div>
</template>

<script>
import icon from './../components/icon.vue';
import moment from "moment";
import locale_de from "moment/locale/de";
import locale_en from "moment/locale/en-gb";

export default {  
	name: 'openingTimes',
	data () {
		return {

		}
	},
	props: ['x'],
	components: {
		moment
	},
	mounted: function() {

	},
	components: {
		icon
	},
	methods: {

	}
};
</script>

<style module>

</style>