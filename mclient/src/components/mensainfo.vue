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

		<div v-if="additionaldata">
			<div :class="$style.elem">{{additionaldata.location.adress}}</div>
			<div :class="$style.elem"><div v-for="item in additionaldata.location.transfer.split(' oder ')">{{item}}</div></div>
			<div :class="$style.elem">{{additionaldata.location.phone}}</div>
		</div>
	</div>
</template>

<script>
import loadGlow from './../components/loadGlow.vue';
import openingTimes from './../components/opening_times.vue';
import ActionButton from './../components/action_button.vue';

export default {  
	name: 'mensaInfo',
	data () {
		return {

		}
	},
	props: ['basicdata', 'additionaldata'],
	mounted: function() {

	},
	components: {
		loadGlow,
		openingTimes,
		ActionButton
	},
	methods: {

	}
};
</script>

<style src="@material/button/dist/mdc.button.min.css"/>

<style module>
	.headline {
		color: #303030;
		font-family: 'Roboto Condensed', sans-serif;
		margin-right: 20px;
		min-width: 50%;
	}
	.headline h1 {
		font-size: 25px;
		font-weight: 400;
		margin: 0;
	}
	.headline h2 {
		font-size: 35px;
		font-weight: 700;
		margin: 0px 0px 10px 0;
	}
	.loadh1 {
		height: 25px;
	}
	.loadh2 {
		height: 35px;
	}
	.loadh3 {
		height: 16px;
		margin: 2px 0 15px 0;
	}

	.elem {
		margin-top: 15px;
	}
</style>