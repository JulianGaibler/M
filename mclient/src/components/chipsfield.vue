<template> 
	<div :class="$style.chipbox">
		<div :class="$style.content">
				<template v-for="item in items">
					<div v-bind:key="item" :class="$style.chip"><span>{{item}}</span><icon v-on:click.native="deleteChip(item)" :svg="iconclose"></icon></div>
				</template>
			<div :class="$style.expander" v-on:click="focusInput()"></div>
			<input :class="$style.txtbox" v-model="searchword" v-on:keypress.188="addChip()" v-on:keypress.enter="addChip()" ref="chipInput" placeholder="Keywords">
		</div>
		<div :class="$style.expander" v-on:click="focusInput()"></div>
	</div>
</template>

<script>
import icon from './../components/icon.vue';

export default {
name: 'mensaselector',
	data () {
		return {
			iconclose: require('./../assets/close.svg'),
			searchword: ""
		}
	},
	props: ['items'],
	methods: {
		focusInput: function() {
			this.$refs.chipInput.focus();
		},
		addChip: function() {
			if (this.searchword.charAt(this.searchword.length - 1) == ',') {
			  this.searchword = this.searchword.substr(0, this.searchword.length - 1);
			}
			if (this.searchword.length>20 || this.searchword.length<4) return;
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
	}
}
</script>

<style module>
	.chipbox {
		display: flex;
		flex-direction: column;
		font-size: 14px;
		overflow-y: scroll;
		color: #000;
		align-items: stretch;
	}
	.chipbox::-webkit-scrollbar {
		width: 5px;
	}
	 
	.chipbox::-webkit-scrollbar-track {
		background: #ddd;
	}
	 
	.chipbox::-webkit-scrollbar-thumb {
		background: #666;
	}
	.content {
		text-align: left;
		padding: 5px;
		padding-bottom: 0;
		display: flex;
		flex-wrap: wrap;
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
</style>