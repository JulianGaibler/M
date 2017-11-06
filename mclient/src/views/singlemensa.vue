<template>
<div>
	<div :class="[$style.upperextend, upperextend===1?$style.extendopen:'']">
		<datePicker v-if="additionaldata" :loading="((this.menu===false) ? true : false)" :initialDate="this.showDate.mmt" :times="additionaldata.location.times"></datePicker>
	</div>
	<div class="adaptiveWrap">
		<div :class="$style.headlineContainer">
			<div :class="$style.headline">
				<span v-if="basicdata">
				<h1>{{basicdata.nameA}}</h1>
				<h2>{{basicdata.nameB}}</h2>
				</span>
				<span v-if="basicdata===false">
					<loadGlow :extStyle="[$style.loading, $style.loadh1]" :dimension="{min:40, max:50, end: '%'}"></loadGlow>
					<loadGlow :extStyle="[$style.loading, $style.loadh2]" :dimension="{min:60, max:80, end: '%'}"></loadGlow>
				</span>
				<loadGlow v-if="!additionaldata" :extStyle="$style.loading" :dimension="{min:30, max:60, end: '%'}"></loadGlow>
				<span v-else><openingTimes :times="additionaldata.location.times"></openingTimes></span>
			</div>
		</div>
		<div>
			<div v-if="menu===false" class="whitebox" v-for="index in 2">
				<div class="whitebox_header">
					{{ $t('state.loading')+'...' }}
				</div>
				<div class="whitebox_element" v-for="index in getRandomInt(2,8)">
					<loadGlow :extStyle="$style.loading" :dimension="{min:30, max:60, end: '%'}"></loadGlow>
				</div>
			</div>
			<div v-if="menu" class="whitebox" v-for="category in menu">
				<div class="whitebox_header">{{category.displayName}}</div>
				<FoodItem v-for="(item, index) in category.items" :key="index" :info="item"></FoodItem>
			</div>
		</div>
	</div>
</div>
</template>

<script>
import moment from "moment";
import loadGlow from './../components/loadGlow.vue';
import openingTimes from './../components/opening_times.vue';
import datePicker from './../components/date_picker.vue';
import icon from './../components/icon.vue';
import FoodItem from './../components/food_item.vue';

export default {
	data () {
		return {
			basicdata: undefined,
			additionaldata: false,
			menu: undefined,
			showDate: {name: false, mmt: moment(this.data.whenOpen)},
			back: require('./../assets/back.svg'),
			daterange: require('./../assets/date_range.svg'),
			sort: require('./../assets/sort.svg'),
			upperextend: 0
		}
	},
	props: ['data'],
	created: function () {
		bus.$on('changeDate', this.changeDate);
	},
	mounted: function() {
		bus.$emit('setActions', [[
			{
			svg: this.back,
			function: this.goBack
		}],[
		{
			svg: this.daterange,
			function: ()=>{
				this.upperextend = (this.upperextend==1)?0:1;
			}
		},
		{
			svg: this.sort,
			function: ()=>{}
		}]]);

		this.additionaldata = false;
		this.getMenu();

		let res = this.$root.$data.storageC.getMensa(this.data._id, this.data.type);
		this.basicdata = (res!==undefined) ? res : false;

		this.$root.$data.dataC.getSingleMensa([{_id: this.data._id}]).then((result) => {
			this.additionaldata = result[0];
			if (!this.basicdata) {
				this.basicdata = {
					nameA: result[0].nameA,
					nameB: result[0].nameB
				};
			}
		},
		(reason) => {

		});
	},
	methods: {
		getRandomInt: function (min, max) {
			return Math.floor(Math.random() * (max - min + 1)) + min;
		},
		goBack: function () {
			window.history.back();
		},
		changeDate: function (newDate, update=true) {
			this.showDate = newDate;

			bus.$emit('updateAction', 1, 0, {
				text: this.showDate.name,
				function: ()=>{
					this.upperextend = (this.upperextend==1)?0:1;
				}
			});

			if (update) this.getMenu();
		},
		getMenu: function() {
			if (this.menu===false) return;
			this.menu = false;
			if (this.data.hasMenu) {
				this.$root.$data.dataC.getMenu(this.data._id, this.showDate.mmt).then((result) => {
					this.menu = this.evalMenu(result);
				},
				(reason) => {

				});
			} else this.menu = [];
		},
		evalMenu: function(menu) {

			for (var i = 0; i < menu.length; i++) {
				if (this.$te('menuSection.'+menu[i].name))
					menu[i].displayName = this.$t('menuSection.'+menu[i].name);
				else
					menu[i].displayName = menu[i].name;
					menu[i].highlightCount = 0;
			}
			let hls = this.$root.$data.storageC.settings.highlights;
			for (var h = hls.length - 1; h >= 0; h--) { // hls[h]
				for (var m = menu.length - 1; m >= 0; m--) { // menu[m]
					for (var i = menu[m].items.length - 1; i >= 0; i--) { // menu[m].items[i]
						if (menu[m].items[i].name.toLowerCase().includes(hls[h])) {
							menu[m].items[i].highlight = true;
							menu[m].highlightCount++;
						}
					}
				}
			}
			return menu;
		}
	},
	components: {
		loadGlow,
		icon,
		FoodItem,
		openingTimes,
		datePicker,
		moment
	}
}
</script>

<style src="@material/ripple/dist/mdc.ripple.min.css"/>

<style module>
	.headlineContainer {
		display: flex;
		justify-content: center;
		padding: 15px 20px 40px 20px;
	}
	.headline {
		color: #303030;
		font-family: 'Roboto Condensed', sans-serif;
		margin-right: 20px;
		min-width: 50%;
	}
	.headline h1 {
		font-size: 25px;
		font-weight: 400;
		margin: 0;
	}
	.headline h2 {
		font-size: 35px;
		font-weight: 700;
		margin: 0px 0px 10px 0;
	}
	.loading {
		height: 14px;
		margin-top: 2px;
	}
	.loadh1 {
		height: 25px;
	}
	.loadh2 {
		height: 35px;
	}
	.loadh3 {
		height: 16px;
		margin: 2px 0 15px 0;
	}
	.loadingbig {
		height: 16px;
	}

	.elem {
		display: flex;
	}
	.elemmid {
		flex: 1;
		display: flex;
		flex-flow: column;
		justify-content: center;
	}

	.upperextend {
		background-color: #ebebeb;
		display: flex;
		margin-bottom: 25px;
		max-height: 0;
		overflow: hidden;
		transition: max-height .3s;
	}
	.extendopen {
		max-height: 100px;
	}
	.dateel {
		padding: 20px;
	}
</style>
