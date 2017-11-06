<template>
	<div :class="$style.wrapper">
		<div :class="$style.a">
			<ActionButton v-for="(action, index) in actions[0]" :info="action" :key="index"></ActionButton>
		</div>
		<icon :class="$style.b" :logocolor='[this.specialScreen ? "#fff" : "#651fff"]' :svg="logo"></icon>
		<div :class="$style.c">
			<ActionButton v-for="(action, index) in actions[1]" :info="action" :key="index"></ActionButton>
		</div>
	</div>
</template>

<script>
import icon from './../components/icon.vue';
import ActionButton from './../components/action_button.vue';

export default {
	data () {
		return {
			logo: require('./../assets/logo.svg'),
			actions: [[],[]]
		}
  	},
  	created: function () {
		bus.$on('resetActions', this.resetActions);
		bus.$on('setActions', this.setActions);
		bus.$on('updateAction', this.updateAction);
	},
	methods: {
		'resetActions': function () {
			this.actions = [[],[]];
		},
		'setActions': function (config) {
			this.actions = config;
		},
		'updateAction': function (i,k,config) {
			this.$set(this.actions[i], k, config)
		}
	},
	components: {
		icon,
		ActionButton
	},
	props: ['specialScreen']
}
</script>

<style module>
	.wrapper {
		display: flex;
		margin: 2px 5px 0 5px;
		height: 60px;
		align-items: center;
		flex-shrink: 0;
	}
	.wrapper .b svg {
		height: 25px;
	}
	.wrapper .b {
		flex: 0;
		text-align: center;
	}
	.wrapper .a {
		flex: 1;
		fill: #651fff;
		display: flex;
		justify-content: flex-start;
	}
	.wrapper .c {
		flex: 1;
		fill: #651fff;
		display: flex;
		justify-content: flex-end;
	}



</style>
