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
				<h3 v-if="showDate.name!==false">{{showDate.name}}</h3>
				<loadGlow v-else :extStyle="[$style.loading, $style.loadh3]" :dimension="{min:20, max:40, end: '%'}"></loadGlow>
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
			<div v-if="menu" class="whitebox" v-for="(category, key) in menu">
				<div class="whitebox_header">{{key}}</div>
				<FoodItem v-for="(item, index) in category" :key="index" :info="item"></FoodItem>
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
		changeDate: function (newDate) {
			this.showDate = newDate;
			this.getMenu();
		},
		getMenu: function() {
			if (this.menu===false) return;
			this.menu = false;
			if (this.data.hasMenu) {
				this.$root.$data.dataC.getMenu(this.data._id, this.showDate.mmt).then((result) => {
					this.menu = result;
				},
				(reason) => {

				});
			} else this.menu = [];
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
		margin: 0;
	}
	.headline h3 {
		font-size: 14px;
		font-weight: 700;
		margin: 0px 0px 10px 0;
		color: #a2a2a2;
		text-align: right;
		text-transform: uppercase;
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
		margin: 2px 0 15px auto;
		text-align: right;
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
