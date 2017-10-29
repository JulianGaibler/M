<template>
	<div :class="$style.wrapper">
		<span :class="$style.navbtn" v-on:click="scroll(-1)"><icon :svg="arrowLeft"></icon></span>
		<div ref="wrapstrip" :class="$style.selectsection">
			<div ref="strip" :class="$style.striwrapper" :style="{transform: 'translateX('+translateOffset+'px)'}">
				<div v-on:click="changeDate(index, item)" v-for="(item, index) in dates" :class="[$style.elem, selectedIndex===index ? $style.selected : '']">{{ item.name }}</div>
			</div>
		</div>
		<span :class="[$style.navbtn]" v-on:click="scroll(1)"><icon :svg="arrowRight"></icon></span>
	</div>
</template>

<script>
import icon from './../components/icon.vue';
import moment from "moment";
import locale_de from "moment/locale/de";
import locale_en from "moment/locale/en-gb";

export default {  
	name: 'openingTimes',
	data () {
		return {
			now: this.initialDate.startOf('day'),
			translateOffset: 0,
			arrowLeft: require('./../assets/keyboard_arrow_left.svg'),
			arrowRight: require('./../assets/keyboard_arrow_right.svg'),
			dates: [],
			selectedIndex: 0
		}
	},
	props: ['times', 'initialDate', 'loading'],
	components: {
		moment
	},
	mounted: function() {
		switch (this.$root.$data.storageC.settings.language) {
			case 'de':
				moment.updateLocale("de",locale_de);
				break;
			case 'en':
				moment.updateLocale("en-gb",locale_en);
		}
		let mmt = this.now.clone();


		for (var i = 0; i < 10; i++) {
			let name = ""
			if (mmt.diff(this.now, 'days')<7) name = mmt.format("dddd");
			else name = mmt.format('L');
			this.dates.push({
				name: name,
				mmt: mmt.clone(),
				selected: false
			})
			do {
				mmt.add(1, 'day');
				if (this.times[0].hours.length > ((((mmt.day()-1) % 7) + 7) % 7)) break;
			} while(true);
		}
		bus.$emit('changeDate', this.dates[0]);
	},
	components: {
		icon
	},
	methods: {
		changeDate: function (i, e) {
			if (i===this.selectedIndex || this.loading) return;
			this.selectedIndex = i;
			bus.$emit('changeDate', e);
		},
		scroll: function (i) {
			let wrapstrip = this.$refs.wrapstrip.offsetWidth;
			let strip = this.$refs.strip.scrollWidth;

			if (i>0) this.translateOffset -= wrapstrip/2;
			else this.translateOffset += wrapstrip/2;
			if (-this.translateOffset > strip-wrapstrip) this.translateOffset = -(strip-wrapstrip);
			else if (-this.translateOffset <0) this.translateOffset = 0;
		}
	}
};
</script>

<style module>
	.wrapper {
		display: flex;
		align-items: stretch;
		width: 100%;

	}
	.navbtn {
		flex: 0;
		display: flex;
		align-items: center;
		padding: 0 5px;
		background-color: rgba(0, 0, 0, 0.02);
		cursor: pointer;
	}
	.navbtn:hover {
		background-color: rgba(0, 0, 0, 0.04);
	}
	.navbtn svg {
		height: 24px;
		width: 24px;
	}
	.selectsection {
		flex: 1;
		overflow: hidden;
	}
	.striwrapper {
		display: flex;
		transition: transform .2s;
	}
	.elem {
		padding: 15px 20px;
		flex: 0;
		display: inline-block;
		white-space: nowrap;
		cursor: pointer;
	}
	.elem:hover {
		background-color: rgba(0, 0, 0, 0.02);
	}
	.selected {
		box-shadow: inset 0px -2px 0px 0px #651fff;
	}
</style>