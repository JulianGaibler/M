<template>
	<!-- SETUP: Language -->
	<div :class="$style.setup" v-if="page==0">
		<icon :class="$style.introimg" :svg="intro_img"></icon>
		<div :class="[$style.middle, $style.paragraphcolor]">
		<p>M sortiert die Menüs aller Mensen aus Berlin und stellt sie so dar wie du möchtest! Du hast mehr überblick in einem Bruchteil der Zeit.</p>
		<p><i>M sorts the menus of all the canteens from Berlin and presents them as you wish! You have more overview in a fraction of the time.</i></p>
		</div>
		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="proceedLang('de')" class="mdc-button">German</button>
			<button v-bind:key="page" v-on:click="proceedLang('en')" class="mdc-button">English</button>
		</div>
	</div>

	<!-- SETUP: HowTo -->
	<div :class="$style.setup" v-else-if="page==1">
		<div :class="$style.top">
			<p>HowTo</p>
		</div>

		<div :class="$style.middle">
			<h1>HowTo</h1>
		</div>
		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="goback()" class="mdc-button">{{ $t('action.back') }}</button>
			<button v-bind:key="page" v-on:click="gonext()" class="mdc-button">{{ $t('action.next') }}</button>
		</div>
	</div>

	<!-- SETUP: Select Mensas -->
	<div :class="$style.setup" v-else-if="page==2">
		<div :class="$style.top">
		</div>
		<div :class="$style.middle">
			<mensaselector></mensaselector>
		</div>
		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="goback()" class="mdc-button">{{ $t('action.back') }}</button>
			<button v-bind:key="page" v-on:click="(hasMensas() ? null : gonext())" :disabled="hasMensas()" class="mdc-button">{{ $t('action.next') }}</button>
		</div>
	</div>

	<!-- SETUP: Select Highlights -->
	<div :class="$style.setup" v-else-if="page==3">
		<div :class="$style.top">
			<p>{{ $t('setup.intro_highlights') }}</p>
		</div>

		<div :class="$style.middle">
			<div :class="$style.exampleTags">
				<template v-for="(item, index) in exampleHighlights">
					<div v-on:click="addToHighlights(item,index)" v-bind:key="item" :class="$style.exampleChip"><span>{{item}}</span></div>
				</template>
			</div>
			<chipsfield :items="this.$root.$data.storageC.settings.highlights" :class="[$style.whitebox]"></chipsfield>
		</div>
		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="goback()" class="mdc-button">{{ $t('action.back') }}</button>
			<button v-bind:key="page" v-on:click="gonext()" class="mdc-button">{{ $t('action.next') }}</button>
		</div>
	</div>

	<!-- SETUP: Prices -->
	<div :class="$style.setup" v-else-if="page==4">
		<div :class="$style.top">
			<p>{{ $t('setup.intro_prices') }}</p>
		</div>

		<div :class="$style.middle">
			<priceSelector></priceSelector>
			<p :class="$style.disclaim">{{ $t('prices.more') }}</p>
		</div>
		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="goback()" class="mdc-button">{{ $t('action.back') }}</button>
			<button v-bind:key="page" v-on:click="gonext()" class="mdc-button">{{ $t('action.next') }}</button>
		</div>
	</div>

	<!-- SETUP: So much more -->
	<div :class="$style.setup" v-else-if="page==5">
		<div :class="$style.top">
			<p>Hooray!</p>
		</div>

		<div :class="$style.middle">
			<h1>Hooray!</h1>
		</div>
		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="gonext()" class="mdc-button">{{ $t('action.next') }}</button>
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
			intro_img: require('./../assets/intro.svg'),
			exampleHighlights: [this.$t('food.pizza'),this.$t('food.cake'),this.$t('food.spaetzle'),this.$t('food.risotto'),this.$t('food.brownie')]
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
		}
	}
}
</script>

<style src="@material/button/dist/mdc.button.min.css"/>
<style src="@material/radio/dist/mdc.radio.min.css"/>

<style module>
	.setup {
			text-align: center;
			color: white;
			font-size: 17px;
			flex-direction: column;
			padding: 0 10px;
/*			max-width: 602px;
			align-self: center;*/
	}
	.setup p {
		margin: 10px 10px 20px 10px;
		line-height: 1.8em;
		position: relative;
	}

	.top p {
		margin: 0 20px 20px 20px;
	}
	.middle {
		flex: 1;
		color: white;
		margin-bottom: 70px;
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

	.signature svg {
		height: 50px;
		margin: 5px;
	}
	.introimg {
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		overflow: hidden;
		text-align: right;
	}
	.introimg svg {
		height: 100%;
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
		position: relative;
	}
	.paragraphcolor p {
		background-color: rgba(101, 31, 255, 0.8);
		padding: 10px;
		margin: 0 0 20px 0;
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
		background-color: #651fff;
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
</style>