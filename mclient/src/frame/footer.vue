<template>
	<footer :class="$style.wrapper">
		<div :class="$style.constraint">
			<div v-ripple :class="['mdc-ripple-surface', $style.elem]" tabindex="0" v-on:click="chviewname('viewhighlights')">
				<div v-bind:class="[currentView=='viewhighlights' ? $style.active : '']">
					<icon :svg="iconthumbs"></icon>
					<span>{{ $t('frame.highlights') }}</span>
				</div>
			</div>
			<div v-ripple :class="['mdc-ripple-surface', $style.elem]" tabindex="0" v-on:click="chviewname('viewmensas')">
				<div v-bind:class="[currentView=='viewmensas' ? $style.active : '']">
					<icon :svg="iconrestaurant"></icon>
					<span>{{ $t('frame.mensa') }}</span>
				</div>
			</div>
			<div v-ripple :class="['mdc-ripple-surface', $style.elem]" tabindex="0" v-on:click="chviewname('viewsettings')">
				<div v-bind:class="[currentView=='viewsettings' ? $style.active : '']">
					<icon :svg="iconsettings"></icon>
					<span>{{ $t('frame.settings') }}</span>
				</div>
			</div>
		</div>
	</footer>
</template>

<script>
import icon from './../components/icon.vue';
import {MDCRipple, MDCRippleFoundation, util} from '@material/ripple';

export default {
	data () {
		return {
			iconthumbs: require('./../assets/thumbs.svg'),
			iconrestaurant: require('./../assets/restaurant.svg'),
			iconsettings: require('./../assets/settings.svg'),
			logocolor: "#651fff"
		}
	},
	props: ['currentView'],
	components: {
		icon
	},
	methods: {
		chviewname: function (viewname) {
			bus.$emit('changeview', viewname);
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
}
</script>



<style module>

	.wrapper {
		background-color: white;
		border-top: 1px solid #e4e4e4;
		box-shadow: 0 0 25px rgba(0, 0, 0, 0.06);
	}
	.constraint {
		display: flex;
		max-width: 450px;
		margin: auto;
	}
	.elem {
		flex-grow: 1;
		text-align: center;
		padding: 8px;
		color: #757575;
		-webkit-user-select: none;    
		-moz-user-select: none;
		-ms-user-select: none;
		cursor: default;
	}
	.elem svg {
		height: 24px;
		fill: #757575;
	}
	.elem span {
		display: block;
		font-size: 12px;
	}

	.active {
		color: #651fff;
	}
	.active svg {
		fill: #651fff;
	}

	.inactive {
		opacity: .6;
	}

	/*iPhone X*/
	@media only screen 
	and (device-width : 375px) 
	and (device-height : 812px)
	and (-webkit-device-pixel-ratio : 3) {
		.wrapper {
			padding-bottom: 20px;
		}
	}

</style>
