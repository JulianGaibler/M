<template>
	<div class="adaptiveWrap">
		<div :class="$style.headlineContainer">
			<h1>{{ $t('frame.highlights') }}</h1>
			<p>{{ $t('highlights.description') }}</p>
		</div>

		<div v-if="hls.length > 0" class="whitebox" v-for="(inf, i) in info">
			<div :class="['whitebox_header', $style.menuheader]">
				<div :class="$style.headline">{{places[i].nameA}} {{places[i].nameB}}</div>
				<div :class="$style.actions"></div>
			</div>

			<foodItemLoad v-if="inf.status === 0" v-for="idx in getRandomInt(1,4)" :key="idx"></foodItemLoad>
			<FoodItem v-if="inf.status === 1 && inf.menu.length>0" v-for="(item, index) in inf.menu" :key="index" :info="item"></FoodItem>
			<div v-if="inf.status === 1 && inf.menu.length===0" :class="['whitebox_element', $style.notice]">{{$t('result.error_nomatches')}}</div>
			<div v-else-if="inf.status === -1" :class="['whitebox_element', $style.notice]">{{$t('result.loaderror_explain')}}</div>
			<div v-else-if="inf.status === -2" :class="['whitebox_element', $style.notice]">{{$t('result.error_nomenu')}}</div>
		</div>
		<div v-if="hls.length === 0" :class="['whitebox_element', $style.notice]">
			<p>{{$t('result.info_addhighlights')}}</p>
			<button v-on:click="this.bus.$emit('changeview', 'viewsettings')" class="mdc-button mdc-button--raised">{{$t('frame.settings')}}</button>
		</div>

	</div>
</template>

<script>
import moment from "moment";
import foodItemLoad from './../components/foodItemLoad.vue';
import FoodItem from './../components/food_item.vue';

import Helpers from './../classes/Helpers.js';
import Vue from 'vue';

export default {
	data () {
		return {
			places: [],
			info: [],
			hls: this.$root.$data.storageC.settings.highlights
		}
	},
  	mounted: async function() {
		// [Info] We don't want to mutate storageC.settings.mensas
		//        that's why we store some info in a different array
		this.info = [];
		this.setup();

		
	},
	methods: {
		evalMenu: function (menu) {
			// [Info] Don't need to copy menu-array because of the bucket
			let bucket = [];
			for (var m = menu.length - 1; m >= 0; m--) {
				for (var i = menu[m].items.length - 1; i >= 0; i--) {
					for (var h = this.hls.length - 1; h >= 0; h--) {
						if (menu[m].items[i].name.toLowerCase().includes(this.hls[h])) {
							bucket.push(menu[m].items[i]);
							break;
						}
					}
				}
			}
			menu = bucket;

			let diet = this.$root.$data.storageC.settings.diet;
			menu = Helpers.menuDiet(menu, diet);

			let thisAdditives = this.$root.$data.storageC.settings.additives;
			menu = Helpers.menuAdditives(menu, thisAdditives);

			return menu;
		},
		setup: async function() {
			let typeA = this.$root.$data.storageC.settings.primarytype;
			let typeB = (typeA===1)?0:1;
			let subscribed = this.$root.$data.storageC.settings.mensas;
	
			let slicenr = 4 - Math.min(2,subscribed[typeB].length);
			this.places = subscribed[typeA].slice(0, slicenr)
						.concat(subscribed[typeB].slice(0, 4-slicenr));
	
	
			for (var i = 0; i < this.places.length; i++) {
				Vue.set(this.info, i, {status: 0, menu: []})
			}
			
			// Def. info.status
			//	 0: Loading
			//	 1: Success
			//	-1: Failed
			//	-2: No menu
	
			for (var i = 0; i < this.places.length; i++) {
				let infoi = this.info[i];
				if (this.places[i].hasMenu) {
					let x = await this.$root.$data.netC.getMenu(this.places[i]._id, moment(this.places[i].whenOpen))
					.then((result) => {
						infoi.menu = this.evalMenu(result);
						infoi.status = 1;
					},
					(reason) => {
						infoi.status = -1;
					});
				}
				else infoi.status = -2;
			}
		},
		getRandomInt: Helpers.getRandomInt
	},
	components: {
		FoodItem,
		foodItemLoad
	}
}
</script>

<style module lang="scss">
	.headlineContainer {
		text-align: center;
		padding: 20px 0;
		h1 {
			font-size: 40px;
			font-weight: 300;
		}
		p {
			font-size: 17px;
			line-height: 23px;
			max-width: 400px;
			margin: 10px auto;
		}
	}

	.notice {
		text-align: center;
		padding: 30px;
	}

</style>
