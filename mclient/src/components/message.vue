<template>
	<div :class="['whitebox', $style.yellowbox]" v-if="open">
		<div class="whitebox_element">
			<h1>{{msg.headline}}</h1>
			<slot></slot>
		</div>
	</div>
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