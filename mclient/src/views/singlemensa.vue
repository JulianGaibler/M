<template>
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
			<div v-if="menu" class="whitebox" v-for="(category, key) in menu">
				<div class="whitebox_header">{{key}}</div>
				<FoodItem v-for="(item, index) in category" :key="index" :info="item"></FoodItem>
			</div>
		</div>
	</div>
</template>

<script>
import loadGlow from './../components/loadGlow.vue';
import openingTimes from './../components/opening_times.vue';
import icon from './../components/icon.vue';
import FoodItem from './../components/food_item.vue';

export default {
	data () {
		return {
			basicdata: undefined,
			additionaldata: false,
			menu: undefined,
			back: require('./../assets/back.svg')
		}
	},
	props: ['data'],
	mounted: function() {
		bus.$emit('setActions', [[
			{
			svg: this.back,
			function: this.goBack
		}],[]]);

		this.additionaldata = false;
		this.menu = false;

		let res = this.$root.$data.storageC.getMensa(this.data._id, this.data.type);
		this.basicdata = (res!==undefined) ? res : false;

		if (this.data.hasMenu) {
			this.$root.$data.dataC.getMenu(this.data._id).then((result) => {
				this.menu = result;
			},
			(reason) => {

			});
		} else this.menu = [];

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
			bus.$emit('changeview', 'viewmensas');
		}
	},
	components: {
		loadGlow,
		icon,
		FoodItem,
		openingTimes
	}
}
</script>

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
		margin: 0 0 15px 0;
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
		margin: 0 0 15px 0;
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
</style>
