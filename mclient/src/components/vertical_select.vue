<template>
	<div :class="$style.types">
		<div v-for="(item, index) in items" 
			 v-on:click="trigger(index)" 
			 :class='[$style.types_option, itemTranslate(index) ? $style.types_option_on : $style.types_option_off]'>{{ item }}</div>
	</div>
</template>

<script>  
export default {  
	name: 'verticalSelect',
	props: ['items', 'activeitem', 'itemTranslation'],
	methods: {
		trigger: function (index) {
			this.$emit('triggered', index);
		},
		itemTranslate: function (index) {
			if (this.itemTranslation!=undefined) {
				return (index===this.itemTranslation[this.activeitem]);
			} else {
				return (index===this.activeitem);
			}
		}
	}
};
</script>

<style module>
	.types {
		display: flex;
		text-align: center;
		margin: 10px 0 20px 0;
	}
	.types_option {
		text-transform: uppercase;
		flex: 1;
		flex-basis: 100%;
		opacity: .6;
		font-size: 16px;
		font-family: 'Roboto Condensed', sans-serif;
		cursor: pointer;
		transition: opacity .1s;
	}
	.types_option_on {
		opacity: 1;
	}
	.types_option_off:hover {
		opacity: 1;
	}
</style>