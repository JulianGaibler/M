<template> 
	
</template>

<script>
import icon from './../components/icon.vue';
import {MDCRipple, MDCRippleFoundation, util} from '@material/ripple';

export default {
	name: 'LocationFrame',
	data () {
		return {}
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

	.txtbutton {
		display: inline-block;
		cursor: pointer;
		border-radius: 2px;
		color: rgb(101, 31, 255);
		padding: 10px;
	}

	.largebtn {
		height: 51px;
		width: 51px;
	}
	.largebtn svg {
		height: 36px;
		width: 36px;
		margin: 8px;
	}
</style>