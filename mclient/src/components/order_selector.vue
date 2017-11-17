<template> 
	<div class="whitebox">
		<div :class="[$style.elem, $style.head]">
			<div :class="$style.text">{{$t('settings.custom_order')}}</div>
			<div :class="$style.switch">
				<div class="mdc-switch demo-switch--mtheme">
					<input type="checkbox" id="basic-switch" class="mdc-switch__native-control" v-model="enabled" />
					<div class="mdc-switch__background">
						<div class="mdc-switch__knob"></div>
					</div>
				</div>
			</div>
		</div>
		<draggable :list="items" @change="dragMoved" :options="{handle:'.handle'}">
			<div v-for="(item, index) in items" :key="index" :class="[$style.elem]">
				<div :class="['handle',$style.action]">
					<icon :class="$style.dragsvg" :svg="drag_handle"></icon>
				</div>
				<div :class="$style.text">{{item.tag}}</div>
				<div :class="$style.switch">
					<div class="mdc-checkbox demo-checkbox--mtheme">
						<input type="checkbox" v-model="item.show" :checked="item.show"
							 class="mdc-checkbox__native-control "/>
						<div class="mdc-checkbox__background">
							<svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24"> <path class="mdc-checkbox__checkmark__path" fill="none" stroke="white" d="M1.73,12.91 8.1,19.28 22.79,4.59"/> </svg>
							<div class="mdc-checkbox__mixedmark"></div>
						</div>
					</div>
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
		let l = this.$root.$data.storageC.settings.sorting.length;
		return {
			drag_handle: require('./../assets/drag_handle.svg'),
			items: this.$root.$data.storageC.settings.sorting,
			enabled: (l > 0),
		}
	},
	methods: {
		convertNames: function(arr) {
			let res = []
			for (var i = 0; i < arr.length; i++) {
				res[i] = {
					id: arr[i].tag,
					show: true,
					tag: (this.$te('menuSection.'+arr[i].tag)) ?
						this.$t('menuSection.'+arr[i].tag) : arr[i].tag
				}
			}
			return res;
		},
		pushStorage: function() {
			this.$root.$data.storageC.settings.sorting = this.items;
			this.$root.$data.storageC.updateStorage();
		},
		dragMoved: function () {
			this.pushStorage();
		}
	},
	watch: {
		enabled: function(val, oldVal) {
			if (val) {
				this.$root.$data.dataC.getMenuSections().then((result) => {
					this.items = this.convertNames(result);
					this.pushStorage();
				},
				(reason) => {

				});
			} else {
				this.items = [];
				this.pushStorage();
			}
		},
		items: {
			handler: function(val, oldVal) {
				this.pushStorage();
			},
			deep: true
		}
	},
	components: {
		icon,
		draggable
	}
}
</script>

<style src="@material/switch/dist/mdc.switch.min.css"/>
<style src="@material/checkbox/dist/mdc.checkbox.min.css"/>

<style module>
	.elem {
		display: flex;
		align-items: center;
		font-size: 14px;
		padding: 5px 5px 5px 20px;
	}
	.head {
		padding: 20px 20px 20px 20px;
	}
	.text {
		flex: 1;
		font-size: 16px;
	}
	.switch {
		display: flex;
		align-items: center;
		padding: 0 10px;
	}
	.switch label {
		padding-right: 10px;
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
	.mdc-checkbox.demo-checkbox--mtheme .mdc-checkbox__native-control:enabled:checked ~ .mdc-checkbox__background, .mdc-checkbox.demo-checkbox--mtheme .mdc-checkbox__native-control:enabled:indeterminate ~ .mdc-checkbox__background {
		border-color: #651fff;
		background-color: #651fff;
	}
	.mdc-checkbox.demo-checkbox--mtheme::before, .mdc-checkbox.demo-checkbox--mtheme::after {
		background-color: rgba(101, 31, 255, 0.14);
		opacity: 0;
	}
	.mdc-checkbox.demo-checkbox--mtheme .mdc-checkbox__background:before {
		background-color: #651fff;
	}
</style>