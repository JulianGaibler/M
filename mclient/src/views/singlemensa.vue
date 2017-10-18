<template>
	<div>
		<div :class="$style.headlineContainer">
			<div :class="$style.headline">
				<h1>{{localstorage.nameA}}</h1>
				<h2>{{localstorage.nameB}}</h2>
				<loadGlow v-if="!additionaldata" :extStyle="$style.loading" :dimension="{min:30, max:60, end: '%'}"></loadGlow>
				<span v-else><openingTimes :times="additionaldata.location.times"></openingTimes></span>
			</div>
		</div>
		<div :class="$style.foodList">
			<div v-if="!menu" class="whitebox" v-for="index in 2">
				<div class="whitebox_header">
					{{ $t('state.loading')+'...' }}
				</div>
				<div class="whitebox_element" v-for="index in getRandomInt(2,8)">
					<loadGlow :extStyle="$style.loading" :dimension="{min:30, max:60, end: '%'}"></loadGlow>
				</div>
			</div>
			<div v-if="menu" class="whitebox" v-for="(category, key) in menu">
				<div class="whitebox_header">{{key}}</div>
				<div :class="['whitebox_element', $style.elem]" v-for="item in category">
					<div :class="$style.elemleft">
						<icon :className="[$style.iconstyle, getcolor(item.ampel)]" :svg="theM"></icon>
					</div>
					<div :class="$style.elemmid">
						<div class="whitebox_element_top">{{item.name}}</div>
						<div class="whitebox_element_bottom">{{item.labels}}</div>
					</div>
					<div :class="$style.elemright">
						{{ parseFloat(item.prices[pricetype]).toFixed(2) }} €
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import loadGlow from './../components/loadGlow.vue';
import openingTimes from './../components/opening_times.vue';
import icon from './../components/icon.vue';

export default {
	data () {
	return {
		localstorage: this.$root.$data.storageC.getMensa(this.data._id, this.data.type),
		theM: require('./../assets/theM.svg'),
		additionaldata: false,
		menu: false,
		pricetype: this.$root.$data.storageC.settings.pricetype
	}
	},
	props: ['data'],
	mounted: function() {
		this.$root.$data.dataC.getSingleMensa([{_id: this.data._id}]).then((result) => {
			this.additionaldata = result[0];
		},
		(reason) => {

		});

		this.$root.$data.dataC.getMenu(this.data._id).then((result) => {
			this.menu = result;
			console.log(result);
		},
		(reason) => {

		});
	},
	methods: {
		getRandomInt: function (min, max) {
			return Math.floor(Math.random() * (max - min + 1)) + min;
		},
		getcolor: function (nr) {
			switch (nr) {
				case 0:
					return this.$style.green;
					break;
				case 1:
					return this.$style.yellow;
					break;
				case 2:
					return this.$style.red;
			}
		}
	},
	components: {
		loadGlow,
		icon,
		openingTimes
	}
}
</script>

<style module>
	.headlineContainer {
		display: flex;
		justify-content: center;
		padding: 15px 0 20px 0;
	}
	.headline {
		color: #303030;
		font-family: 'Roboto Condensed', sans-serif;
		margin-right: 20px;
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
	.loadingbig {
		height: 16px;
	}

	.foodList {
		padding: 0 10px 10px 10px;
	}

	.elem {
		display: flex;
	}
	.elemleft {

	}
	.elemmid {
		flex: 1;
		display: flex;
		flex-flow: column;
		justify-content: center;
	}
	.elemright {

	}
	.iconstyle svg {
		height: 30px;
		margin: 5px 10px 5px 0;
	}
	.green {fill: #45c89c;}
	.yellow {fill: #ffc31a;}
	.red {fill: #ff543c;}
</style>
