<template>  
	<div :class="$style.noresults">
		<h1>{{ $t('result.error_shoutouts['+error_shoutouts_random+']') }}</h1>
		<p>{{ $t('result.loaderror_explain') }}</p>
	</div>
</template>

<script>  
export default {  
	name: 'errorMsg',
	data () {
		return {
			error_shoutouts_random: this.getRandomInt(0,5)
		}
	},
	methods: {
		getRandomInt: function (min, max) {
			return Math.floor(Math.random() * (max - min + 1)) + min;
		}
	}
};
</script>

<style module>
	.noresults {
		display: flex;
		align-items: center;
		justify-content: center;
		flex-flow: column;
		min-height: 30vh;
		color: #651fff;
		margin: 0 20px 50px 20px;
		text-align: center;
		font-family: 'Roboto Condensed', sans-serif;
	}
	.noresults > h1 {
		margin: 10px;
		font-size: 30px;
	}
	.noresults > p {
		margin: 0;
		font-size: 16px;
	}
</style>