<template> 
		<div v-on:click="info.function()" :class="$style.wrapper">
			<icon v-ripple :class="['mdc-ripple-surface', $style.button]" :svg="info.svg"></icon>
		</div>
</template>

<script>
import icon from './../components/icon.vue';
import {MDCRipple, MDCRippleFoundation, util} from '@material/ripple';

export default {
	name: 'ActionButton',
	props: ['info'],
	data () {
		return {

		}
	},
	methods: {
		getRandomArbitrary: function (min, max) {
			return Math.random() * (max - min) + min;
		}
	},
	components: {
		icon
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
}
</script>

<style src="@material/ripple/dist/mdc.ripple.min.css"/>

<style module>
	.wrapper {
		display: inline-block;
		margin: 5px;
	}
	.button {
		border-radius: 50%;
		display: inline-block;
		cursor: pointer;
		height: 38px;
		width: 38px;
	}

	.button svg {
		height: 26px;
		width: 26px;
		margin: 6px;
	}
</style>