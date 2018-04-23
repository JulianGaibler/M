<template>  
	<div :class="$style.noresults">
		<h1>{{ $t('result.error_shoutouts['+error_shoutouts_random+']') }}</h1>
		<p v-if="type===0">{{ $t('result.loaderror_explain') }}</p>
		<p v-else-if="type===1">{{ $t('result.noresults_explain') }}</p>
	</div>
</template>

<script>
import Helpers from './../classes/Helpers.js'

export default {  
	name: 'errorMsg',
	data () {
		return {
			error_shoutouts_random: this.getRandomInt(0,5)
		}
	},
	props: {
		type: {
			type: Number,
			default: 0,
			required: false
		}
	},
	methods: {
		getRandomInt: Helpers.getRandomInt
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