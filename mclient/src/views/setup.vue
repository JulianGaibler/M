<template>
	<!-- SETUP: Language -->
	<div :class="$style.setup" v-if="page==0">
		<div :class="$style.top">
			<p>M gets Berlins Mensa-Menus and displays them how you want!{{ $t('hello') }}</p>
		</div>
		<div :class="$style.middle">
		</div>
		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="proceedLang('de')" class="mdc-button">German</button>
			<button v-bind:key="page" v-on:click="proceedLang('en')" class="mdc-button">English</button>
		</div>
	</div>
	<!-- SETUP: Select Mensas -->
	<div :class="$style.setup" v-else-if="page==1">
		<div :class="$style.top">
			<p>Select Mensas you <b>visit regularly</b> <span :class="$style.mensaslct"></span> and your <b>primary mensa</b> <span :class="[$style.mensaslct, $style.filled]"></span> by tapping it a second time</p>
		</div>
		<div :class="$style.middle">
			<mensaselector :class="[$style.whitebox, $style.selectbox]"></mensaselector>
		</div>
		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="goback()" class="mdc-button">Back</button>
			<button v-bind:key="page" v-on:click="(hasMensas() ? null : gonext())" :disabled="hasMensas()" class="mdc-button">Next</button>
		</div>
	</div>
	<!-- SETUP: Select Highlights -->
	<div :class="$style.setup" v-else-if="page==2">
		<div :class="$style.top">
			<p>M can hightlight certain food for you by word.</p>
		</div>

		<div :class="$style.middle">
			<div :class="$style.exampleTags">
				<template v-for="item in exampleHighlights">
					<div v-on:click="addToHighlights(item)" v-bind:key="item" :class="$style.exampleChip"><span>{{item}}</span></div>
				</template>
			</div>
			<chipsfield :items="this.$root.$data.storageC.settings.highlights" :class="[$style.whitebox]"></chipsfield>
		</div>
		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="goback()" class="mdc-button">Back</button>
			<button v-bind:key="page" v-on:click="gonext()" class="mdc-button">Next</button>
		</div>
	</div>
	<!-- SETUP: Hooray -->
	<div :class="$style.setup" v-else-if="page==3">
		<div :class="$style.top">
			<p>Hooray!</p>
		</div>

		<div :class="$style.middle">
			<h1>Hooray!</h1>
		</div>
		<div :class="$style.bottom">
			<button v-bind:key="page" v-on:click="gonext()" class="mdc-button">Next</button>
		</div>
	</div>
</template>

<script>

import mensaselector from './../components/mensaselector.vue';
import chipsfield from './../components/chipsfield.vue';
import icon from './../components/icon.vue';
export default {
	data () {
		return {
			page: 0,
			signature_white: require('./../assets/signature_white.svg'),
			exampleHighlights: ["Pizza", "Torte", "Spätzle", "Risotto", "Brownie"]
		}
	},
	components: {
		mensaselector,
		chipsfield,
		icon
	},
	methods: {
		proceedLang: function (lang) {
			this.$i18n.locale = lang;
			this.$root.$data.storageC.settings.language = lang;
			this.page++;
		},
		gonext: function () {
			if (this.page<3) this.page++;
			else bus.$emit('changeview', null);
		},
		goback: function () {
			this.page--;
		},
		hasMensas: function () {
			return (this.$root.$data.storageC.settings.mensas.length > 0) ? false: true;
		},
		addToHighlights: function (item) {
			this.$root.$data.storageC.settings.highlights.push(item);
			this.$root.$data.storageC.updateStorage();
		}
	}
}
</script>

<style src="@material/button/dist/mdc.button.min.css"/>

<style module>
	.setup {
			text-align: center;
			color: white;
			font-size: 17px;
			margin: auto;
			max-width: 602px;
			display: flex;
			height: 100%;
			flex-direction: column;
			padding: 0 20px;
	}
	.setup p {
		margin: 10px 10px 20px 10px;
		line-height: 1.8em;
	}

	.top {

	}
	.middle {
		flex: 1;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-direction: column;
		min-height: 250px;
	}
	.bottom > button {
		color: white;
		margin: 20px;
	}

	.signature svg {
		height: 50px;
		margin: 5px;
	}

	.setup .whitebox {
		box-shadow: 0px 3px 2px -2px rgba(0, 0, 0, 0.2), 0px 2px 5px 0px rgba(0, 0, 0, 0.14), 0px 0px 12px 1px rgba(0, 0, 0, 0.12);
		background-color: white;
		width: 100%;
		overflow: hidden;
		flex-grow: 1;
		border-radius: 10px;
	}

	.setup .selectbox {
		color: #1a1a1a;
		flex-grow: 0;
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
    	display: flex;
    	flex-wrap: wrap;
    	font-size: 14px;
	}
	.exampleChip {
		background-color: #d14531;
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
		color: #d14531;
		cursor: pointer;
	}
</style>