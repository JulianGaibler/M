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

		<span v-if="enabled">
			<errorMsg v-if="error"></errorMsg>
			<div v-else-if="items.length<1">
				<div v-for="index in 7" :key="index" :class="[$style.elem]">
					<div :class="[$style.action]">
						<icon :class="['handle',$style.dragsvg]" :svg="drag_handle"></icon>
					</div>
					<div :class="$style.text"><loadGlow :extStyle="$style.loading" :dimension="{min:30, max:60, end: '%'}"></loadGlow></div>
				</div>
			</div>
			<draggable v-else :list="items" @change="dragMoved" :options="{handle:'.handle'}">
				<div v-for="(item, index) in items" :key="index" :class="[$style.elem]">
					<div :class="['handle',$style.action]">
						<icon :class="$style.dragsvg" :svg="drag_handle"></icon>
					</div>
					<div :class="$style.text">{{($te('menuSection.'+item.tag)) ? $t('menuSection.'+item.tag) : item.tag}}</div>
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
		</span>
	</div>
</template>

<script>
import icon from './../components/icon.vue';
import draggable from 'vuedraggable';
import loadGlow from './../components/loadGlow.vue';
import errorMsg from './../components/errormsg.vue';

export default {
	name: 'orderSelector',
	props: ['info'],
	data () {
		let l = this.$root.$data.storageC.settings.sorting.length;
		return {
			drag_handle: require('./../assets/drag_handle.svg'),
			items: this.$root.$data.storageC.settings.sorting,
			enabled: (l > 0),
			error: false
		}
	},
	methods: {
		convertNames: function(arr) {
			let res = []
			for (var i = 0; i < arr.length; i++) {
				res[i] = {
					tag: arr[i].tag,
					show: true
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
			this.error = false;
			if (val) {
				this.$root.$data.dataC.getMenuSections().then((result) => {
					this.items = this.convertNames(result);
					this.pushStorage();
				},
				(reason) => {
					this.error = true;
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
		draggable,
		loadGlow,
		errorMsg
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
	.loading {
		height: 16px;
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