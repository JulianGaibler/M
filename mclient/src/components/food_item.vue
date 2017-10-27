<template> 
	<div :class="[$style.outelem, moreInfo ? $style.elemOpen : '']">
		<div v-on:click="toggle(false)" :class="[$style.elem, 'mdc-ripple-surface']">
			<div :class="$style.elemleft">
				<icon :className="[$style.iconstyle, getcolor(info.ampel)]" :svg="theM"></icon>
			</div>
			<div :class="$style.elemmid">
				<div class="whitebox_element_top">{{info.name}}</div>
				<div class="whitebox_element_bottom">{{pictogramString(info.labels)}} {{info.additives.length +' '+$t('labels.additives') }}</div>
			</div>
			<div :class="$style.elemright">
				{{ parseFloat(info.prices[pricetype]).toFixed(2) }} €
			</div>
		</div>
		<div :class="$style.elembottom">
			<h3>{{$t('labels.additives')}}</h3>
			<div v-if="moreInfoThere">
				<p v-for="elem in additiveTranslation">{{elem}}</p>
			</div>
		</div>
	</div>
</template>

<script>
import icon from './../components/icon.vue';
import {MDCRipple, MDCRippleFoundation, util} from '@material/ripple';

export default {
	name: 'FoodItem',
	props: ['info'],
	data () {
		return {
			theM: require('./../assets/theM.svg'),
			pricetype: this.$root.$data.storageC.settings.pricetype,
			additivesList: [],
			moreInfo: false,
			moreInfoThere: false
		}
	},
	created: function () {
		bus.$on('closeFoodPanels', this.toggle);
	},
	methods: {
		toggle: function (close=true) {
			if (!this.moreInfo && !close) bus.$emit('closeFoodPanels');
			this.moreInfo = close ? false : !this.moreInfo;
			if (this.moreInfo) this.moreInfoThere = true;
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
		},
		pictogramString: function (arr) {
			let str = "";
			let lngth = arr.length;
			if (lngth<1) return "";
			for (var i = 0; i < lngth; i++) {
				str += this.$t('labels.'+arr[i]);
				if (i<lngth-1) str += ", ";
			}
			return str+" | ";
		}
	},
	computed: {
		additiveTranslation: function () {
			if (this.additivesList === null) return;
			if (this.info.additives.length < 1) return [this.$t("result.none")];
			if (this.additivesList.length < 1) {
				this.additivesList = null;
				this.$root.$data.dataC.getAdditives().then((result) => {
					this.additivesList = result;
				},
				(reason) => {

				});
			} else {
				let bucket = [];
				let userlang = this.$root.$data.storageC.settings.language;
				for (var k = 0; k < this.info.additives.length; k++) {
					for (var i = this.additivesList.length - 1; i >= 0; i--) {
						if (this.additivesList[i].id === this.info.additives[k]) {
							console.log(this.additivesList[i].id+" === "+this.info.additives[k]);
							switch (userlang) {
								case 'de':
									bucket.push(this.additivesList[i].name_de);
									break;
								case 'en':
									bucket.push(this.additivesList[i].name_en);
							}
							break;
						}
					}
				}
				return bucket;
			}
		}
	},
	components: {
		icon
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

<style src="@material/ripple/dist/mdc.ripple.min.css"/>

<style module>
	.elem {
		display: flex;
		cursor: pointer;
		padding: 15px 20px;
	}
	.outelem {
		transition: all .3s;
		background-color: white;
		will-change: height;
		margin: 0;
	}
	.elemOpen {
		box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.15), 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
		margin: 5px -10px;
		z-index: 2;
    	position: relative;
	}
	.elemmid {
		flex: 1;
		display: flex;
		flex-flow: column;
		justify-content: center;
	}
	.iconstyle svg {
		height: 30px;
		margin: 5px 10px 5px 0;
	}
	.elembottom {
		overflow: hidden;
		max-height: 0;
		transition: max-height .3s;
	}
	.elemOpen .elembottom {
		overflow: hidden;
		max-height: 300px;
		padding: 0 20px 15px 20px;
	}
	.elemright {
		padding-left: 10px;
	}

	.elembottom h3 {
		color: #817575;
		padding: 20px 0px 10px 5px;
		margin: 0;
		font-size: 14px;
		font-weight: 400;
		text-transform: uppercase;
		font-family: 'Roboto Condensed', sans-serif;
	}

	.elembottom p {
		list-style-type: none;
		display: inline-block;
		padding: 5px;
		margin: 0 10px 10px 0;
		background-color: #f4f5f6;
	}

	.green {fill: #45c89c;}
	.yellow {fill: #ffc31a;}
	.red {fill: #ff543c;}
</style>