xc<template>
	<div :class="['whitebox', $style.wrapper]">
		<div :class="$style.search">
			<icon :class="$style.svg" :svg="iconsearch"></icon>
			<input :disabled="loading==1" @input="debounceInput" v-model="word" :placeholder="placeholder">
			<span v-on:click="deleteInput()" v-if="word.length>0" :class="$style.btn"><icon v-ripple class="mdc-ripple-surface" :svg="iconclose"></icon></span>
		</div>
		<div v-if="loading==1">
			<div :class="[$style.loading, 'loading']" v-for="index in 15" :style="{ animationDelay: getRandomArbitrary(0.2,1)+'s' }">
				<div :class="[$style.loading_1, 'loading_color']" :style="{ width: getRandomArbitrary(50,100)+'%' }"></div>
				<div :class="[$style.loading_2, 'loading_color']" :style="{ width: getRandomArbitrary(50,50)+'%' }"></div>
			</div>
		</div>
		<errorMsg v-else-if="loading==2"></errorMsg>
		<div v-else-if="results<1" :class="$style.noresults">
			<h1>{{ $t('result.error_shoutouts['+error_shoutouts_random+']') }}</h1>
			<p>{{ $t('result.noresults_explain') }}</p>
		</div>
		<slot v-else>Nothing to hide.</slot>
	</div>
</template>

<script>  
import icon from './../components/icon.vue';
import {MDCRipple, MDCRippleFoundation, util} from '@material/ripple';
import debounce from 'debounce';
import errorMsg from './../components/errormsg.vue';

export default {  
	name: 'searchBox',
	data () {
		return {
			iconsearch: require('./../assets/search.svg'),
			iconclose: require('./../assets/close.svg'),
			error_shoutouts_random: this.getRandomInt(0,5),
			word: ""
		}
	},
	props: ['placeholder', 'loading', 'results'],
	components: {
		icon,
		errorMsg
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
		},
		deleteInput: function () {
			this.word = "";
			this.$emit('inputChange', "");
		}
	},
	directives: {
		ripple: {
			bind(el, binding, vnode) {
				MDCRipple.attachTo(el);
			}, update(el, binding, vnode) {
				MDCRipple.attachTo(el);
			}
		}
	}
};
</script>

<style src="@material/ripple/dist/mdc.ripple.min.css"/>

<style module>
	.wrapper {
		-webkit-transform: translate3d(0,0,0);
	}
	.search {
		fill: #a3a3a3;
		display: flex;
		padding: 15px 15px;
		padding-bottom: 10px;
		align-items: center;
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

	.search .svg {
		flex-shrink: 0;
		height: 38px;
		width: 38px;
	}
	.search .svg svg {
		height: 26px;
		width: 26px;
		margin: 6px;
	}

	.btn {
		border-radius: 50%;
		display: inline-block;
		cursor: pointer;
		height: 38px;
		width: 38px;
		display: inline-block;
		overflow-y: hidden;
	}

	.btn svg {
		height: 26px;
		width: 26px;
		margin: 6px;
	}
</style>