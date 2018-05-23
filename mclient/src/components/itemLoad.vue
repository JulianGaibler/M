<template> 
	<div :class="$style.box">
			<loadGlow :extStyle="$style.loadingA" :dimension="{min:30, max:60, end: '%'}"></loadGlow>
			<loadGlow :extStyle="$style.loadingB" :dimension="{min:30, max:60, end: '%'}"></loadGlow>
	</div>
</template>

<script>
import loadGlow from './../components/loadGlow.vue';

export default {
name: 'itemLoad',
	methods: {
		getRandomArbitrary: function (min, max) {
			return Math.random() * (max - min) + min;
		}
	},
	components: {
		loadGlow
	}
}
</script>

<style module lang="scss">
	.box {
		.loadingA {
			height: 14px;
			margin-bottom: 5px;
		}
		.loadingB {
			height: 10px;
		}
		.iconstyle svg {
			height: 30px;
			margin: 5px 10px 5px 0;
			fill: #aa94ec;
		}
	}
</style>