<template> 
	<div :class="$style.box">
		<div :class="$style.left">
			<icon :className="[$style.iconstyle, 'loading']" :style="{ animationDelay: getRandomArbitrary(0.2,1)+'s' }" :svg="theM"></icon>
		</div>
		<div :class="$style.right">
			<loadGlow :extStyle="$style.loadingA" :dimension="{min:30, max:60, end: '%'}"></loadGlow>
			<loadGlow :extStyle="$style.loadingB" :dimension="{min:30, max:60, end: '%'}"></loadGlow>
		</div>
	</div>
</template>

<script>
import loadGlow from './../components/loadGlow.vue';
import icon from './../components/icon.vue';

export default {
name: 'foodItemLoad',
	data () {
		return {
			theM: require('./../assets/theM.svg')
		}
	},
	methods: {
		getRandomArbitrary: function (min, max) {
			return Math.random() * (max - min) + min;
		}
	},
	components: {
		icon,
		loadGlow
	}
}
</script>

<style module lang="scss">
	.box {
		display: flex;
		cursor: pointer;
		padding: 15px 20px;
		.right {
			flex: 1;
			display: flex;
			flex-flow: column;
			justify-content: center;
			.loadingA {
				height: 14px;
				margin-bottom: 5px;
			}
			.loadingB {
				height: 10px;
			}
		}
		.iconstyle svg {
			height: 30px;
			margin: 5px 10px 5px 0;
			fill: #aa94ec;
		}
	}
</style>