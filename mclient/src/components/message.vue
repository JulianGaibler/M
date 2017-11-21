<template>
	<div :class="['whitebox', $style.yellowbox]" v-if="open"><slot></slot></div>
</template>

<script>
export default {
	name: 'message',
	data: function() {
    	return {
    		open: true
    	};
	},
	props: ['msg'],
	methods: {
		close: function () {
			this.open = false;
		}
	}
}
</script>

<style module>
	.yellowbox {
		
	}
</style>