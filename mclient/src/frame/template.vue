<template>
	<div v-bind:class="[specialScreen(currentView) ? $style.primebg : '', $style.mount]">
		<div :class="[$style.main, 'subtleScrollbar']" ref="main">
			<menuhead v-bind:specialScreen="specialScreen(currentView)"></menuhead>
			<component :data="currentData" :class="$style.viewhook" :is="currentView"></component>
		</div>
		<transition name="fadefooter">
			<menufooter v-if="!specialScreen(currentView)" v-bind:currentView="currentView"></menufooter>
		</transition>
		<Snackbar></Snackbar>
	</div>
</template>

<script>
import menuhead from './header.vue'
import menufooter from './footer.vue'
import Snackbar from './../components/snackbar.vue'

import highlights from './../views/highlights.vue'
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
			currentData: {},
			pagecount: 0,
			oldState: [{view: startView, scroll: 0, data: {}}]
		}
	},
	components: {
		menuhead,
		menufooter,
		Snackbar,
		'viewhighlights':highlights,
		'viewmensas':mensaoverview,
		'viewsettings':settings,
		'viewsetup':viewsetup,
		'singlemensa':singlemensa
	},
	mounted() {
		window.location.hash = 0;
		this.$nextTick(function() {
			window.addEventListener('popstate', this.popstate);
		})
	},
	beforeDestroy() {
		window.removeEventListener('resize', this.popstate);
	},
	created: function () {
		bus.$on('changeview', this.changeview);
		bus.$on('goBack', this.goBack);
	},
	methods: {
		'changeview': function (newView, data={}) {
			window.location.hash = ++this.pagecount;

			this.oldState.push({
				view: this.currentView,
				scroll: this.$refs.main.scrollTop,
				data: this.currentData
			});

			this.$refs.main.scrollTop = 0;
			bus.$emit('resetActions');
			this.currentData = data;
			if (!!newView) this.currentView = newView;
			else this.currentView = 'viewmensas';
		},
		'specialScreen': function (view) {
			if (view=='viewsetup') return true;
			else return false;
		},
		'goBack': function () {
			let hashnr = parseInt(window.location.hash.substr(1));
			if (hashnr == NaN || hashnr >= this.pagecount) return;
			bus.$emit('resetActions');
			let os = this.oldState.pop();
			this.currentView = os.view;
			this.$refs.main.scrollTop = os.scroll;
			this.currentData = os.data;

		},
		'popstate': function (event) {
			// Went back or forward :S
			this.goBack();
		}
	}
}
</script>

<style src="./../assets/reset.css" />

<style lang="scss">
	$mdc-theme-primary: #651fff;
	$mdc-theme-secondary: #651fff;
	$mdc-theme-background: #fff;

	@import "@material/ripple/mdc-ripple.scss";
	@import "@material/button/mdc-button.scss";
	@import "@material/checkbox/mdc-checkbox.scss";
	@import "@material/radio/mdc-radio.scss";
	@import "@material/switch/mdc-switch.scss";
	@import "@material/snackbar/mdc-snackbar.scss";
	@import "@material/slider/mdc-slider.scss";

	.mdc-snackbar__action-button {
		color: #c4aaff;
	}
</style>

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
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		-webkit-tap-highlight-color: rgba(255, 255, 255, 0);
	}
	.main {
		flex: 1;
		overflow-y: scroll;
		-webkit-overflow-scrolling: touch;
		display: inline-flex;
		flex-direction: column;
		align-items: stretch;
		height: 100%;
		will-change: scroll-position;
	}
	.viewhook {
		flex: 1;
	}
	.primebg {
		background: #651fff;
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

	.subtleScrollbar::-webkit-scrollbar {
	  width: 5px;
	}
	 
	.subtleScrollbar::-webkit-scrollbar-track {
	  background: none;
	}
	 
	.subtleScrollbar::-webkit-scrollbar-thumb {
	  background: rgba(0, 0, 0, 0.2); 
	}

	.adaptiveWrap {
		padding: 0 10px 10px 10px;	
	}
	.handle {
		cursor: move;
	}
	.handle svg {
		width: 25px;
		height: 25px;
		margin: 10px 15px 10px -5px;
		fill: #a3a3a3;
	}
	@media screen and (min-width: 600px) {
		.adaptiveWrap {
			width: 500px;
			margin: 0 auto;
		}
	}
	hr {
		border: 0;
		height: 0;
		border-top: 1px solid rgba(0, 0, 0, 0.1);
		border-bottom: 1px solid rgba(255, 255, 255, 0.3);
		margin: 0;
	}

	input::-ms-clear {
		width : 0;
		height: 0;
	}
</style>
