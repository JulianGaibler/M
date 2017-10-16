<template>
	<div :class="$style.wrapper">
		<div :class="$style.box">
			<div :class="$style.progress" :style="{ fill: '#651fff'}" v-html="svg"></div>
			<p>It should be set to 'W' for Wumbo.</p>
		</div>
	</div>
</template>

<script>  
export default {  
	name: 'loader',
	data () {
		return {
			svg: require('./../assets/mLoader.svg')
		}
	}
};
</script>

<style module>

.wrapper {
	display: flex;
	align-items: center;
	justify-content: center;
}
.box {
	max-width: 200px;
}
.box > p {
	text-transform: uppercase;
	color: #651fff;
	font-weight: 400;
	font-size: 17px;
	line-height: initial;
}

.progress svg {
	height: 70px;
}

	.progress path {
		-webkit-animation: rotate 4s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
			    animation: rotate 4s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
			    transform-origin: 95px 95px;
	}

	@-webkit-keyframes dash {
		50% {stroke-dashoffset: 0;}
		100% {stroke-dashoffset: -550;}
	}
	@keyframes dash {
		50% {stroke-dashoffset: 0;}
		100% {stroke-dashoffset: -550;}
	}

	@-webkit-keyframes rotate {
		50% {-webkit-transform: rotate(180deg);
		transform: rotate(180deg);}
		100% {-webkit-transform: rotate(360deg);
		transform: rotate(360deg);}
	}
	@keyframes rotate {
		50% {-webkit-transform: rotate(180deg);
		transform: rotate(180deg);}
		100% {-webkit-transform: rotate(360deg);
		transform: rotate(360deg);}
	}
</style>