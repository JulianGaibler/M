<template> 
	<div :class="$style.chipbox">
		<div v-if="noSuggestions || (!noSuggestions && suggestions.length > 0)" :class="[$style.content, !stringCase ? $style.case : '']">
				<div v-for="item in items">
					<div v-bind:key="item" :class="$style.chip"><span v-if="noSuggestions">{{item}}</span><span v-else>{{getName(item)}}</span><icon v-on:click.native="deleteChip(item)" :svg="iconclose"></icon></div>
				</div>
			<div v-if="noSuggestions" :class="$style.expander" v-on:click="focusInput()"></div>
			<input v-if="noSuggestions" :class="$style.txtbox" v-model="searchword" v-on:keypress.nextchip="addChip()" ref="txtInput" :placeholder="$t('input.keyword')">
			<autocomplete v-else @inputFinal="addChip" :class="$style.autowrapper" :placeholder="$t('input.keyword')" :suggestions="suggestions" :inputStyle="$style.txtbox" v-model="searchword"></autocomplete>
		</div>
		<div v-else>
			<div :class="$style.content"><loadGlow v-for="index in 7" :key="index" :extStyle="$style.loadChip" :dimension="{min:10, max:30, end: '%'}"></loadGlow></div>
		</div>
		<div v-if="noSuggestions" :class="$style.expander" v-on:click="focusInput()"></div>
	</div>
</template>

<script>
import icon from './../components/icon.vue';
import autocomplete from './../components/autocomplete.vue';
import loadGlow from './../components/loadGlow.vue';

export default {
name: 'mensaselector',
	data () {
		return {
			iconclose: require('./../assets/close.svg'),
			searchword: "",
			noSuggestions: (this.suggestions === undefined)
		}
	},
	props: {
		items: Array,
		stringCase: {
			type: Boolean,
			default: true,
			required: false
		},
		suggestions: {
			type: Array,
			required: false
		}
	},
	methods: {
		focusInput: function() {
			this.$refs.txtInput.focus();
		},
		getName: function(itemID) {
			for (var i = this.suggestions.length - 1; i >= 0; i--) {
				if (this.suggestions[i].id === itemID) return this.suggestions[i].name;
			}
		},
		addChip: function() {
			if (this.items.includes(this.searchword)) {
				this.searchword="";
				return;
			}
			if (this.noSuggestions) {
				if (this.searchword.charAt(this.searchword.length - 1) == ',') {
				  this.searchword = this.searchword.substr(0, this.searchword.length - 1);
				}
				if (this.searchword.length>20 || this.searchword.length<2) return;
			}

			if (!this.stringCase) this.searchword = this.searchword.toLowerCase();
			this.items.push(this.searchword);
			this.searchword="";
			this.$root.$data.storageC.updateStorage();
		},
		deleteChip: function(item) {
			if (!item) {
				if (this.searchword.length == 0) this.items.pop();
			}
			else {
				let index = this.items.indexOf(item);
				if(index >= 0) this.items.splice(index, 1);
			}
			this.$root.$data.storageC.updateStorage();
		}
	},
	components: {
		icon,
		autocomplete,
		loadGlow
	}
}
</script>

<style module>
	.chipbox {
		display: flex;
		flex-direction: column;
		font-size: 14px;
		color: #000;
		padding: 5px;
		align-items: stretch;
	}
	.content {
		text-align: left;
		display: flex;
		flex-wrap: wrap;
		position: relative;
	}
	.expander {
		flex-grow: 1;
		cursor: text;
		min-height: 5px;
	}
	.txtbox {
		flex-grow: 100;
		border: none;
		margin: 0 10px;
		padding: 15px 0;
		outline: none;
		min-width: 100px;
	}
	.chip {
		background-color: #e3e3e3;
		border-radius: 50px;
		display: inline-flex;
		padding: 10px;
		padding-left: 15px;
		margin: 5px 0px 5px 5px;
		-webkit-user-select: none;    
		-moz-user-select: none;
		-ms-user-select: none;
		cursor: default;
	}
	.chip > div {
		height: 14px;
		width: 14px;
		border-radius: 50%;
		background-color: #a8a8a8;
		display: inline-block;
	}
	.chip > span {
		margin-right: 10px;
	}
	.chip > div > svg  {
		height: 10px;
		width: 10px;
		margin: 2px;
		fill: #e3e3e3;
	}

	.chip:hover {
		background-color: #828282;
		color: #fff;
	}
	.chip:hover > div > svg {
		fill: #828282;
	}
	.chip:hover > div {
		background-color: #fff;
		cursor: pointer;
	}
	.case, .case input {
		text-transform: uppercase;
		font-family: 'Roboto Condensed', sans-serif;
		font-weight: 700;
		color: #373737;
	}

	.loadChip {
		height: 36px;
		border-radius: 50px;
		margin: 5px 0px 5px 5px;
	}

	.autowrapper {
		display: flex;
		flex-direction: column;
		flex-grow: 100;
	}
</style>