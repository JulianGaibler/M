<template>
	<div :class="$style.wrapper">
		<div :class="$style.a"></div>
			<icon :class="$style.b" :logocolor='[this.specialScreen ? "#fff" : "#ff543c"]' :svg="logo"></icon>
		<div :class="$style.a"></div>
	</div>
</template>

<script>
import icon from './../components/icon.vue';

export default {
	data () {
		return {
			logo: require('./../assets/logo.svg')
		}
  	},
	components: {
		icon
	},
	props: ['specialScreen']
}
</script>

<style module>
	.wrapper {
/*		display: flex;
*/		padding: 5px;
	}
	.wrapper .a svg {
		height: 24px;
		margin: 18px;
		opacity: 0.2;
	}
	.wrapper .b svg {
		height: 60px;
	}
	.wrapper .b {
		flex-grow: 1;
		text-align: center;
	}

</style>
