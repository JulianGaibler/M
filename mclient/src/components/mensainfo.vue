<template>
<div>
	<div :class="$style.headline">
		<div :class="$style.flexdivide">
			<div>
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
			<div v-if="hasMenu" :class="$style.actions"><ActionButton :class="[$style.directions, extended?$style.rotate180:'']" :info="acBtn_arrow"></ActionButton></div>
		</div>
	</div>
		<div v-if="additionaldata" :class="[$style.extender, extended?$style.extended:'']">
			<div :class="$style.elem">{{additionaldata.location.adress}}</div>
			<div :class="$style.elem"><div v-for="item in additionaldata.location.transfer.split(' oder ')">{{item}}</div></div>
			<div :class="$style.elem">{{additionaldata.location.phone}}</div>
<!-- 			<div :class="$style.actions">
				<ActionButton :class="$style.directions" :info="acBtn_a"></ActionButton>
			</div> -->
		</div>
		</div>
</template>

<script>
import loadGlow from './../components/loadGlow.vue';
import openingTimes from './../components/opening_times.vue';
import ActionButton from './../components/action_button.vue';

export default {  
	name: 'mensaInfo',
	data () {
		let svg_directions = require('./../assets/directions.svg');
		let expand_more = require('./../assets/expand_more.svg');
		return {
			extended: false,
			hasMenu: false,
			acBtn_arrow: { svg: expand_more, function: this.toggleExtender },
			acBtn_a: { svg: svg_directions, large: true, function: this.toggleExtender }
		}
	},
	props: ['basicdata', 'additionaldata'],
	mounted: function() {

	},
	components: {
		loadGlow,
		openingTimes,
		ActionButton
	},
	methods: {
		toggleExtender: function() {
			this.extended = !this.extended;
		}
	},
	watch: {
		basicdata: function (newBasicdata) {
			if (newBasicdata!==undefined) {
				this.basicdata = newBasicdata;
				this.extended = !newBasicdata.hasMenu;
				this.hasMenu = newBasicdata.hasMenu;
			}
		}
	},
};
</script>

<style src="@material/button/dist/mdc.button.min.css"/>

<style module>
	.headline {
		color: #303030;
		font-family: 'Roboto Condensed', sans-serif;
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

	.elem {
		margin-top: 15px;
	}

	.flexdivide {
		display: flex;
		justify-content: center;
	}


	.extender {
		max-height: 0;
		overflow: hidden;
		transition: all .4s;
		will-change: height;
	}
	.extended {
		max-height: 300px;
	}
	.actions {
		text-align: right;
		align-self: flex-end;
		margin-bottom: -10px;
	}
	.report {
		fill: #757575;
	}
	.directions {
		fill: #817575;
		height: 38px;
    	width: 38px;
    	transition: all .5s;
	}
	.directions svg {
		transition: transform .2s;
	}

	.rotate180 svg {
		transform: rotate(180deg);
	}
</style>