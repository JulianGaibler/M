<template>
	<!-- SETUP: Language -->
	<div :class="['adaptiveWrap', $style.setup, $style.setupfirst]" v-if="page==0">
	<div :class="$style.top">
			<h1>Helping you through the labyrinth of mensa-menus</h1>
		</div>
		<div :class="[$style.middle]">
			<icon :class="$style.introimg" :svg="intro_img"></icon>
			<p :class="$style.hltxt">By letting you decide about the <span>menu-order</span>, <span>price-categories</span>, <span>allergies</span>, <span>dietary preferences</span>, <span>favorite mensas</span>, food to be <span>highlighted</span> and more</p>
			<a href="//jwels.berlin"><icon :class="$style.signature" :svg="signature_white"></icon></a>
		</div>

		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="proceedLang('de')" class="mdc-button">German</button>
			<button v-bind:key="page" v-on:click="proceedLang('en')" class="mdc-button">English</button>
		</div>
	</div>

	<!-- SETUP: HowTo -->
	<div :class="['adaptiveWrap', $style.setup, $style.setupfirst]" v-else-if="page==1">
		<div :class="$style.top">
			<h1>{{ $t('setup.only_minutes') }}</h1>
		</div>

		<div :class="[$style.middle, $style.paragraphcolor]">
			<icon :class="$style.previewsvg" :svg="add_circle_outline"></icon>
			<p>{{ $t('setup.howto_1') }}</p>
			<icon :class="$style.previewsvg" :svg="star_border"></icon>
			<p>{{ $t('setup.howto_2') }}</p>
			<icon :class="$style.previewsvg" :svg="drag_handle"></icon>
			<p>{{ $t('setup.howto_3') }}</p>
		</div>
		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="gonext()" class="mdc-button">{{ $t('action.got_it') }}</button>
		</div>
	</div>

	<!-- SETUP: Select Mensas -->
	<div :class="['adaptiveWrap', $style.setup]" v-else-if="page==2">
		<div :class="$style.top">
		</div>
		<div :class="$style.middle">
			<mensaselector></mensaselector>
		</div>
		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="goback()" class="mdc-button">{{ $t('action.back') }}</button>
			<button v-bind:key="page" v-on:click="(hasMensas() ? null : gonext())" :disabled="hasMensas()" class="mdc-button">{{ $t('action.done') }}</button>
		</div>
	</div>

	<!-- SETUP: Prices -->
	<div :class="['adaptiveWrap', $style.setup]" v-else-if="page==3">
		<div :class="$style.top">
			<p>{{ $t('setup.intro_prices') }}</p>
		</div>

		<div :class="$style.middle">
			<priceSelector class="whitebox"></priceSelector>
			<p :class="$style.disclaim">{{ $t('prices.more') }}</p>
		</div>
		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="goback()" class="mdc-button">{{ $t('action.back') }}</button>
			<button v-bind:key="page" v-on:click="gonext()" class="mdc-button">{{ $t('action.next') }}</button>
		</div>
	</div>

	<!-- SETUP: Select Highlights -->
	<div :class="['adaptiveWrap', $style.setup]" v-else-if="page==4">
		<div :class="$style.top">
			<p :class="$style.hint">{{ $t('state.optional') }}</p>
			<p>{{ $t('setup.intro_highlights') }}</p>
		</div>

		<div :class="$style.middle">
			<div :class="$style.exampleTags">
				<template v-for="(item, index) in exampleHighlights">
					<div v-on:click="addToHighlights(item,index)" v-bind:key="item" :class="$style.exampleChip"><span>{{item}}</span></div>
				</template>
			</div>
			<chipsfield :stringCase="false" :items="this.$root.$data.storageC.settings.highlights" :class="[$style.whitebox]"></chipsfield>
		</div>
		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="goback()" class="mdc-button">{{ $t('action.back') }}</button>
			<button v-bind:key="page" v-on:click="gonext()" class="mdc-button">{{ $t('action.next') }}</button>
		</div>
	</div>

	<!-- SETUP: So much more -->
	<div :class="['adaptiveWrap', $style.setup]" v-else-if="page==5">
		<div :class="$style.top">
			<h1>You did it!</h1>
		</div>

		<div :class="$style.middle">
			<p>{{ $t('setup.finished') }}</p>
			<icon :class="$style.webapptutorial" :svg="webapp_tutorial"></icon>
		</div>
		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="gonext()" class="mdc-button">{{ $t('action.got_it') }}</button>
		</div>
	</div>
</template>

<script>

import mensaselector from './../components/mensaselector.vue';
import chipsfield from './../components/chipsfield.vue';
import priceSelector from './../components/price_selector.vue';
import icon from './../components/icon.vue';

export default {
	data () {
		return {
			page: 0,
			signature_white: require('./../assets/signature_white.svg'),
			add_circle_outline: require('./../assets/add_circle_outline.svg'),
			webapp_tutorial: require('./../assets/webapp-tutorial.svg'),
			star_border: require('./../assets/star_border.svg'),
			drag_handle: require('./../assets/drag_handle.svg'),
			intro_img: require('./../assets/intro.svg'),
			exampleHighlights: []
		}
	},
	components: {
		mensaselector,
		chipsfield,
		priceSelector,
		icon
	},
	methods: {
		proceedLang: function (lang) {
			this.$i18n.locale = lang;
			this.$root.$data.storageC.settings.language = lang;
			this.exampleHighlights = [this.$t('food.pizza'),this.$t('food.cake'),this.$t('food.spaetzle'),this.$t('food.risotto'),this.$t('food.brownie')];
			this.page++;
		},
		gonext: function () {
			if (this.page<5) this.page++;
			else bus.$emit('changeview', null);
		},
		goback: function () {
			this.page--;
		},
		hasMensas: function () {
			return !this.$root.$data.storageC.hasSettings();
		},
		addToHighlights: function (item, index) {
			this.exampleHighlights.splice(index, 1);
			this.$root.$data.storageC.settings.highlights.push(item);
			this.$root.$data.storageC.updateStorage();
		},
		splitToSpan: function (string) {
			return string.split(' ')
		}
	}
}
</script>

<style module>
	.setup {
			text-align: center;
			color: white;
			font-size: 17px;
			flex-direction: column;
			padding: 0 10px;
			margin-bottom: 60px;
/*			max-width: 602px;
			align-self: center;*/
	}
	.setup p {
		margin: 10px 10px 20px 10px;
		line-height: 1.8em;
		position: relative;
	}

	.setup p.hint {
		text-transform: uppercase;
		font-size: 14px;
		margin: 10px 0px 0px 0px;
		background: white;
		display: inline-block;
		color: #651fff;
		padding: 0 11px;
		border-radius: 20px;
	}

	.setup h1 {
		font-family: 'Roboto Condensed', sans-serif;
		font-weight: 400;
	}

	.top {
		padding: 0 20px 20px 20px;
	}
	.middle {
		flex: 1;
		color: white;
	}
	.bottom {
		position: absolute;
		bottom: 0;
		left: 0;
		right: 0;
		background-color: rgba(96, 29, 243, 0.95);
	}
	.bottom > button {
		color: white;
		margin: 10px;
	}
	/*iPhone X*/
	@media only screen 
	and (device-width : 375px) 
	and (device-height : 812px)
	and (-webkit-device-pixel-ratio : 3) {
		.bottom {
			padding-bottom: 20px;
		}
	}

	.signature svg {
		height: 50px;
		margin: 5px;
	}
	.introimg {
		display: inline-block;
		padding: 0 50px;
		margin: 0 auto;
		max-width: 300px;
	}
	.introimg svg {
		width: 100%;
	}

	.webapptutorial {
		padding: 0 10px;
		margin: 0 auto;
		max-width: 500px;
	}
	.webapptutorial svg {
		width: 100%;
	}

	.setup .whitebox {
		box-shadow: 0px 3px 2px -2px rgba(0, 0, 0, 0.2), 0px 2px 5px 0px rgba(0, 0, 0, 0.14), 0px 0px 12px 1px rgba(0, 0, 0, 0.12);
		background-color: white;
		overflow: hidden;
		flex-grow: 1;
		border-radius: 10px;
		min-height: 25vh;
	}

	.paragraphcolor {
		flex: 1;
		display: flex;
		flex-direction: column;
  		justify-content: center;
  		align-items: center;
	}
	.paragraphcolor p {
		font-size: 14px;
		padding: 10px;
		max-width: 450px;
		padding: 0 10%;
	}

	.paragraphcolor span {
		font-family: 'Roboto Condensed', sans-serif;
		background-color: white;
		color: #651fff;
		padding: 5px 10px;
		font-size: 20px;
		opacity: .9;
	}


	.mensaslct {
		border: 1.5px solid white;
		border-radius: 50%;
		width: 16px;
		height: 16px;
		align-items: center;
		justify-content: center;
		display: inline-flex;
		vertical-align: middle;
	}
	.mensaslct::before {
		content: "";
		display: block;
		border: 1.5px solid white;
		border-radius: 50%;
		width: 6px;
		height: 6px;
	}
	.mensaslct.filled::before {
		background-color: white;

	}
	.exampleTags {
		text-align: left;
		padding: 10px;
		display: flex;
    	justify-content: center;
    	flex-wrap: wrap;
    	font-size: 14px;
	}
	.exampleChip {
		background-color: rgba(255, 255, 255, 0.15);
		color: #fff;
		border-radius: 50px;
		display: inline-flex;
		padding: 10px 15px;
		margin: 5px 0px 5px 5px;
		flex-grow: 0.2;
		-webkit-user-select: none;    
		-moz-user-select: none;
		-ms-user-select: none;
		cursor: default;
	}
	.exampleChip > span {
		margin: auto;
	}

	.exampleChip:hover {
		background-color: #fff;
		color: #651fff;
		cursor: pointer;
	}

	.disclaim {
		font-size: 14px;
		opacity: .8;
	}
	.previewsvg {
		background-color: rgba(255, 255, 255, 0.15);
		border-radius: 50%;
		height: 35px;
		width: 35px;
		padding: 5px;
	}
	.previewsvg svg {
		height: 100%;
		width: 100%;
		fill: white;
	}

	.hltxt {
		color: rgba(255, 255, 255, 0.75);
		padding: 20px;
		margin: 0;
		font-weight: 300;
		line-height: initial;
	}

	.hltxt span {
		color: white;
	}
</style>