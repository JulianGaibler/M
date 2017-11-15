<template> 
	<div class="whitebox">
		<draggable :list="items" :options="{handle:'.handle'}">
		<div v-for="item in items" :class="[$style.elem]">
			<div :class="['handle',$style.action]">
				<icon :class="$style.dragsvg" :svg="drag_handle"></icon>
			</div>
			<div>{{item}}</div>
			<div>
			<div class="mdc-switch">
  <input type="checkbox" id="basic-switch" class="mdc-switch__native-control" />
  <div class="mdc-switch__background">
    <div class="mdc-switch__knob"></div>
  </div>
</div>
<label for="basic-switch" class="mdc-switch-label">off/on</label>
			</div>
		</div>
		</draggable>
	</div>
</template>

<script>
import icon from './../components/icon.vue';
import draggable from 'vuedraggable'

export default {
	name: 'orderSelector',
	props: ['info'],
	data () {
		let todo = ['appetizers','salads','soups','promotions','maindishes','sidedishes','desserts'];//TODO
		return {
			drag_handle: require('./../assets/drag_handle.svg'),
			items: this.convertNames(todo)
		}
	},
	methods: {
		getRandomArbitrary: function (min, max) {
			return Math.random() * (max - min) + min;
		},
		convertNames: function(arr) {
			for (var i = 0; i < arr.length; i++) {
				if (this.$te('menuSection.'+arr[i]))
					arr[i] = this.$t('menuSection.'+arr[i]);
			}
			return arr;
		}
	},
	components: {
		icon,
		draggable
	}
}
</script>

<style src="@material/ripple/dist/mdc.ripple.min.css"/>

<style module>
	.elem {
		display: flex;
		align-items: center;
		font-size: 14px;
		padding: 5px 5px 5px 20px;
	}
</style>