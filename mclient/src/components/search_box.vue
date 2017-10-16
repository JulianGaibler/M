<template>
	<div class="whitebox">
		<div :class="$style.search">
			<icon :class="$style.svg" :svg="iconsearch"></icon>
			<input :disabled="loading==1" @input="debounceInput" v-model="word" :placeholder="placeholder">
		</div>
		<div v-if="loading==1">
			<div :class="[$style.loading, 'loading']" v-for="index in 15" :style="{ animationDelay: getRandomArbitrary(0.2,1)+'s' }">
				<div :class="[$style.loading_1, 'loading_color']" :style="{ width: getRandomArbitrary(50,100)+'%' }"></div>
				<div :class="[$style.loading_2, 'loading_color']" :style="{ width: getRandomArbitrary(50,50)+'%' }"></div>
			</div>
		</div>
		<div v-else-if="loading==2" :class="$style.noresults">
			<h1>{{ $t('result.error_shoutouts['+getRandomInt(0,5)+']') }}</h1>
			<p>{{ $t('result.loaderror_explain') }}</p>
		</div>
		<div v-else-if="results<1" :class="$style.noresults">
			<h1>{{ $t('result.noresults_headline') }}</h1>
			<p>{{ $t('result.noresults_quote') }}</p>
		</div>
		<slot v-else>Nothing to hide.</slot>
	</div>
</template>

<script>  
import icon from './../components/icon.vue';
import debounce from 'debounce';

export default {  
	name: 'searchBox',
	data () {
		return {
			iconsearch: require('./../assets/search.svg'),
			word: ""
		}
	},
	props: ['placeholder', 'loading', 'results'],
	components: {
		icon
	},
	methods: {
		debounceInput: debounce(function (e) {
			this.$emit('inputChange', this.word);
		}, 300),
		getRandomArbitrary: function (min, max) {
			return Math.random() * (max - min) + min;
		},
		getRandomInt: function (min, max) {
			return Math.floor(Math.random() * (max - min + 1)) + min;
		}
	}
};
</script>

<style module>
	.search {
		fill: #a3a3a3;
		display: flex;
		padding: 20px;
		padding-bottom: 10px;
		align-items: center;
	}
	.search .svg {
		flex-shrink: 0;
		width: 25px;
		height: 25px;
	}
	.search .svg svg {
		width: 100%;
		height: 100%;
	}
	.search input {
		flex: 1;
		border: none;
		text-transform: uppercase;
		padding: 5px;
		outline: none;
		font-size: 14px;
		font-family: 'Roboto Condensed', sans-serif;
	}

	.loading {
		padding: 15px 20px;
	}
	.loading_1 {
		height: 16px;
		margin-bottom: 10px;
		width: 100%;
	}
	.loading_2 {
		height: 14px;
		width: 80%;
	}
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