<template>
	<div>
		<div v-on:click="focusInput()" :class="$style.areawrap">
			<textarea ref="txtArea" v-model="message" :placeholder="$t('settings.transport_info')" class="subtleScrollbar"></textarea>
		</div>
		<hr>
		<div :class="$style.buttonwrap">
			<div :class="$style.left">
				{{resultmsg}}
			</div>
			<div :class="$style.right">
				<button v-on:click="codeimport()" class="mdc-button">{{ $t('action.import') }}</button>
			<button v-on:click="codeexport()" class="mdc-button">{{ $t('action.export') }}</button>
			</div>
		</div>
	</div>
</template>

<script>
import radioSelector from './../components/radio_selector.vue';
import chipsfield from './../components/chipsfield.vue';
import textSwitch from './../components/text_switch.vue';
import errorMsg from './../components/errormsg.vue';

import Helpers from './../classes/Helpers.js'

export default {  
	name: 'settingsTransport',
	data () {
		let l = this.$root.$data.storageC.settings.additives.length;
		return {
			selectedIndex: this.$root.$data.storageC.settings.diet,
			suggestions: [],
			error: false,
			enabled: (l > 0),
			message: "",
			resultmsg: ""
		}
	},
	mounted() {
		if (this.enabled) this.toggleData(true);
	},
	methods: {
		focusInput: function() {
			this.$refs.txtArea.select();
		},
		codeimport: function (nr) {
			let todecode;
			try {todecode = window.atob(this.message);}
			catch(err) {this.resultmsg = this.$t('result.error_json_parse'); return;}

			try {todecode = JSON.parse(todecode);}
			catch(err) {this.resultmsg = this.$t('result.error_base64_decoding'); return;}

			this.resultmsg = this.$t('state.successful');
			this.$root.$data.storageC.settings = todecode;
			location.reload();
		},
		codeexport: function (nr) {
			this.message = window.btoa(localStorage.getItem('settings'));
			this.resultmsg = this.$t('state.successful');
		}
	}
};
</script>

<style src="@material/button/dist/mdc.button.min.css"/>

<style module>
	.areawrap {
		display: flex;
		padding: 10px;
	}
	.buttonwrap {
		padding: 5px;
		display: flex;
		align-items: center;
	}
	.buttonwrap .left {
		text-align: left;
		padding-left: 5px;
		flex: 1;
	}
	.buttonwrap .right {
		text-align: right;
	}
	.areawrap textarea {
		flex-grow: 1;
		resize: none;
		border: none;
		height: 75px;
		outline: none;
		font-family: "Menlo", "Courier New", Courier, monospace;
		font-size: 11px;
	}
</style>