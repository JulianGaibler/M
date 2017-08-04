<template>
	<div v-bind:class="[specialScreen(currentView) ? $style.primebg : '', $style.mount]">
	<menuhead v-bind:specialScreen="specialScreen(currentView)"></menuhead>
		<div :class="$style.main">
			<component :is="currentView"></component>
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
import b from './../views/b.vue'
import c from './../views/c.vue'
import viewsetup from './../views/setup.vue'
export default {
	data () {
		let startView = 'viewsetup';

		if (this.$root.$data.storageC.hasSettings()) {
			this.$i18n.locale = this.$root.$data.storageC.settings.language;
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
			showfooter: true
		}
	},
	components: {
		menuhead,
		menufooter,
		'viewhighlights':a,
		'viewmensas':b,
		'viewsettings':c,
		'viewsetup':viewsetup
	},
	created: function () {
		bus.$on('changeview', this.changeview);
	},
	methods: {
		'changeview': function (newView) {
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
	@import url('https://fonts.googleapis.com/css?family=Roboto:300,400');
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
		transition: background 0.5s;
	}
	.main {
		flex-grow: 1;
		overflow-y: scroll;
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
	.primebg {
		background: #ff543c;
	}
	hr {
		border-color: #dadada;
		margin: 0 25px;
	}
</style>
