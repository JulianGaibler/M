<template> 
	<div :class="[extStyle, 'loading', 'loading_color']" :style="{ width: getRandomArbitrary(dimension.min,dimension.max)+dimension.end, animationDelay: getRandomArbitrary(0.2,1)+'s' }"></div>
</template>

<script>
export default {
name: 'loadGlow',
	props: ['extStyle', 'dimension'],
	methods: {
		getRandomArbitrary: function (min, max) {
			return Math.random() * (max - min) + min;
		}
	}
}
</script>

<style module></style>