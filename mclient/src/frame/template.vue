<template>
	<div v-bind:class="[specialScreen(currentView) ? $style.primebg : '', $style.mount]">
		<div :class="$style.main" ref="main">
			<menuhead v-bind:specialScreen="specialScreen(currentView)"></menuhead>
			<component :data="currentData" :class="$style.viewhook" :is="currentView"></component>
		</div>
		<transition name="fadefooter">
			<menufooter v-if="!specialScreen(currentView)" v-bind:currentView="currentView"></menufooter>
		</transition>
	</div>
</template>

<script>
import menuhead from './header.vue'
import menufooter from './footer.vue'

import a from './../views/a.vue'
import mensaoverview from './../views/mensaoverview.vue'
import singlemensa from './../views/singlemensa.vue'
import settings from './../views/settings.vue'
import viewsetup from './../views/setup.vue'
export default {
	data () {
		let startView = 'viewsetup';

		if (this.$root.$data.storageC.hasSettings()) {
			switch (this.$root.$data.storageC.settings.startpage) {
				case 0:
					startView = 'viewmensas';
					break;
				case 1:
					startView = 'viewmensas';
			}
		}

		return {
			currentView: startView,
			showfooter: true,
			currentData: {}
		}
	},
	components: {
		menuhead,
		menufooter,
		'viewhighlights':a,
		'viewmensas':mensaoverview,
		'viewsettings':settings,
		'viewsetup':viewsetup,
		'singlemensa':singlemensa
	},
	created: function () {
		bus.$on('changeview', this.changeview);
	},
	methods: {
		'changeview': function (newView, data={}) {
			this.$refs.main.scrollTop = 0;
			bus.$emit('resetActions');
			this.currentData = data;
			if (!!newView) this.currentView = newView;
			else this.currentView = 'viewmensas';
		}, 'specialScreen': function (view) {
			if (view=='viewsetup') return true;
			else return false;
		}
	}
}
</script>

<style src="./../assets/reset.css" />
<style>
	@import url('https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700|Roboto:300,400');
	body, html {
		height: 100%;
		font-family: 'Roboto', sans-serif;
	}
	.fadefooter-enter-active, .fadefooter-leave-active {
		transition: max-height .5s;
		overflow: hidden;
		max-height: 60px;
	}
	.fadefooter-enter, .fadefooter-leave-to {
		max-height: 0px;
	}
</style>
<style module>

	.mount {
		display: flex;
		flex-direction: column;
		height: 100%;
		background-color: #f4f5f6;
		transition: background 0.5s;
	}
	.main {
		flex: 1;
		overflow-y: scroll;
		display: inline-flex;
		flex-direction: column;
		align-items: stretch;
		height: 100%;
	}
	.main::-webkit-scrollbar {
	  width: 5px;
	}
	 
	.main::-webkit-scrollbar-track {
	  background: none;
	}
	 
	.main::-webkit-scrollbar-thumb {
	  background: rgba(0, 0, 0, 0.2); 
	}
	.viewhook {
		flex: 1;
	}
	.primebg {
		background: #651fff;
		background: linear-gradient(#21064d, #651fff);
	}
	hr {
		border-color: #dadada;
		margin: 0 25px;
	}
</style>

<style>
	.whitebox {
		background-color: white;
		border-radius: 2px;
		text-align: left;
		align-items: center;
		margin-bottom: 20px;
		font-size: 14px;
		color: black;
		overflow: hidden;
		box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.15), 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
	}
	.whitebox_header {
		color: #817575;
		padding: 20px 20px 10px 20px;
		font-size: 14px;
		text-transform: uppercase;
		font-family: 'Roboto Condensed', sans-serif;
	}
	.whitebox_element {
		padding: 15px 20px;
	}
	.whitebox_element.clickable {
		cursor: pointer;
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}
	.whitebox_element_top {
		font-size: 14px;
	}
	.whitebox_element_bottom {
		color: #817575;
		font-size: 12px;
	}

	@keyframes glow {
		0% {opacity: 0}
		50% {opacity: 1}
		100% {opacity: 0}
	}
	.loading {
		animation-name: glow;
		animation-duration: 2s;
		animation-iteration-count: infinite;
		opacity: 0;
	}
	.loading_color {
		background-color: #aa94ec;
	}

	h1 {
		font-size: 25px;
		font-weight: 400;
		margin: 0;
	}
	h2 {
		font-size: 35px;
		font-weight: 700;
		margin: 0 0 15px 0;
	}


	.adaptiveWrap {
		padding: 0 10px 10px 10px;	
	}
	@media screen and (min-width: 600px) {
		.adaptiveWrap {
			width: 500px;
			margin: 0 auto;
		}
	}
</style>
