<template>
	<div :class="$style.head">
		<div :class="$style.text">{{text}}</div>
		<div :class="$style.switch">
			<div class="mdc-switch demo-switch--mtheme">
				<input type="checkbox" id="basic-switch" class="mdc-switch__native-control" :checked="checked" v-bind:checked="checked" @change="updateValue"/>
				<div class="mdc-switch__background">
					<div class="mdc-switch__knob"></div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: 'textSwitch',
	data: function() {
    	return {
    		checked: this.value
    	};
	},
	props: ['text', 'value'],
	methods: {
		updateValue: function () {
			this.checked = !this.checked;
			this.$emit('input', this.checked);
		}
	}
}
</script>

<style src="@material/switch/dist/mdc.switch.min.css"/>

<style module>
	.head {
		padding: 20px 20px 20px 20px;
		display: flex;
		align-items: center;
		font-size: 14px;
	}
	.switch {
		display: flex;
		align-items: center;
		padding: 0 10px;
	}
	.switch label {
		padding-right: 10px;
	}
	.text {
		flex: 1;
		font-size: 16px;
	}
</style>
<style>
	.demo-switch--mtheme .mdc-switch__native-control:enabled:checked ~ .mdc-switch__background::before {
		background-color: #651fff;
	}
	.demo-switch--mtheme .mdc-switch__native-control:enabled:checked ~ .mdc-switch__background .mdc-switch__knob {
		background-color: #651fff;
	}
	.demo-switch--mtheme .mdc-switch__native-control:enabled:checked ~ .mdc-switch__background .mdc-switch__knob::before {
		background-color: #651fff;
	}
</style>