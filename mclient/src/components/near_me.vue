<template>
	<div>
		<div :class="['whitebox_header', $style.menuheader]">
			<div :class="$style.headline">{{$t('type.near')}}</div>
			<div :class="$style.actions">
				<span>km</span><slider :methd="slided" :valuemin="1" :valuemax="20" :valuenow="15" :step="5"></slider>
			</div>
		</div>
		<div v-if="status===0"><itemLoad class="whitebox_element" v-for="index in 3" :key="index"></itemLoad></div>
		<errorMsg v-else-if="status===1"></errorMsg>
		<errorMsg v-else-if="status===2 && results.length < 1" :type="1"></errorMsg>
		<div v-else-if="status===2">
			<div v-ripple v-on:click="this.bus.$emit('changeview', 'singlemensa', item)" v-for="item in results" class="clickable whitebox_element mdc-ripple-surface">
				<div class="whitebox_element_top">{{item.nameA}} {{item.nameB}}</div>
				<div class="whitebox_element_bottom">{{ $tc('state.km_away', item.distance, { count: item.distance }) }} | {{ item.location.adress }}</div>
			</div>
		</div>
	</div>
</template>

<script>
import {MDCRipple, MDCRippleFoundation, util} from '@material/ripple';
import Helpers from './../classes/Helpers.js'
import debounce from 'debounce'
import Vue from 'vue'

import slider from './../components/slider.vue'
import errorMsg from './../components/errormsg.vue'
import itemLoad from './../components/itemLoad.vue'

export default {
	name: 'nearMe',
	props: ['location', 'data'],
	// Status
	// 0: loading
	// 1: error
	// 2: done
	data () {
		return {
			valuenow: 15,
			status: 0,
			results: []
		}
	},
	mounted: function() {
		this.fetchServer();
	},
	components: {
		slider,
		errorMsg,
		itemLoad
	},
	watch: {
		data: {
			handler: function(val, oldVal) {
				this.fetchServer();
			},
			deep: true
		}
	},
	methods: {
		slided: debounce(function (event) {
			if (this.valuenow === event.detail.value) return;
			this.valuenow = event.detail.value;
			this.fetchServer();
		}, 300),
		fetchServer: function () {
			this.$root.$data.netC.getNearMensas(this.location.longitude, this.location.latitude, this.valuenow, this.data.type)
				.then(result => {
					this.populateArray(result)
				})
				.catch(error => {
					console.error(error); //Todo
				})
		},
		populateArray: function (results) {
			this.$root.$data.netC.getLightMensas()
				.then(mensas => {
					let bucket = [];
					for (var i = 0; i < results.length; i++) {
						let res = mensas[this.data.type].find((e)=>e._id===results[i]._id)
						if (!res) continue;
						bucket.push(Object.assign(results[i], res, {distance: +results[i].distance.toFixed(1)}));
					}
					this.results = bucket;
					this.status = 2;

				})
				.catch(error => {
					console.error(error);
				})
		}
	},
	directives: {
		ripple: {
			bind(el, binding, vnode) {
				MDCRipple.attachTo(el);
			}, update(el, binding, vnode) {
				MDCRipple.attachTo(el);
			}
		}
	}
}
</script>



<style module lang="scss">
	.menuheader {
		display: flex;
		align-items: center;
		padding: 0;
		.headline {
			flex: 1;
			display: inline-block;
			padding: 0 20px;
		}
		.actions {
			display: flex;
			align-items: center;
			width: 70px;
			margin-right: 15px;
			& > span {
				margin-right: 15px;
			}
			& > div {
				margin-bottom: 5px;
			}
		}
	}
</style>