<template>
	<div :class="[$style.wrapper, 'adaptiveWrap']">
		 <verticalSelect :items="vertical_data" :itemTranslation="[1,0,2]" :activeitem="this.type" v-on:triggered="changeType"></verticalSelect>
		 <div v-if="subscribed[this.type].length > 0" class="whitebox">
			<div class="whitebox_header">{{ $t('type.my') }} {{ $tc('type.'+this.type,2) }}</div>
			<div v-ripple v-on:click="this.bus.$emit('changeview', 'singlemensa', item)" v-for="item in subscribed[this.type]" class="clickable whitebox_element mdc-ripple-surface">
				<div class="whitebox_element_top">{{item.nameA}} {{item.nameB}}</div>
				<div class="whitebox_element_bottom">
					<loadGlow v-if="item.location===undefined" :extStyle="$style.loading" :dimension="{min:50, max:200, end: 'px'}"></loadGlow>
					<span v-else><openingTimes :times="item.location.times"></openingTimes></span>
				</div>
			</div>
		 </div>

		<LocationFrame class="whitebox" :requestor="$t('type.near')" :data="{type: this.type}" :component="nearMe"></LocationFrame>

		 <searchBox :loading="this.loadingall" :searchword="searchword" :results="searchres.length" :placeholder="this.$t('type.all')+' '+this.$tc('type.'+this.type,2)" v-on:inputChange="changeSearchword">
			<div v-ripple v-on:click="this.bus.$emit('changeview', 'singlemensa', item)" v-for="item in searchres" class="clickable whitebox_element mdc-ripple-surface">
				<div class="whitebox_element_top">{{item.nameA}} {{item.nameB}}</div>
				<div class="whitebox_element_bottom">{{ item.location.adress }}</div>
			</div>
		 </searchBox>
	</div>
</template>

<script>
import verticalSelect from './../components/vertical_select.vue';
import searchBox from './../components/search_box.vue';
import loadGlow from './../components/loadGlow.vue';
import nearMe from './../components/near_me.vue';
import openingTimes from './../components/opening_times.vue';
import LocationFrame from './../components/Location_frame.vue';
import {MDCRipple, MDCRippleFoundation, util} from '@material/ripple';

export default {
	data () {
		return {
			iconM: require('./../assets/theM.svg'),
			type: 0,
			searchword: "",
			loadingall: 1,
			subscribed: [[],[],[]],
			allMensas: [[],[],[]],
			loadedsubs: [false, false, false],
			subscribed_loaded_additional: [false,false,false],
			vertical_data: [this.$tc('type.1',2),this.$tc('type.0',2),this.$tc('type.2',2)],
			nearMe: nearMe
		}
	},
	mounted: function() {
		this.type = this.$root.$data.storageC.settings.primarytype;
		this.subscribed = this.$root.$data.storageC.settings.mensas;

		this.loadingall = 1;
		this.$root.$data.netC.getLightMensas().then((allMensas) => {
			this.allMensas = allMensas;
			this.loadingall = 0;
		},
		(reason) => {
			this.loadingall = 2;
		});

		if (this.data!==undefined) {
			if (this.data.type!==undefined) this.type = this.data.type;
		}

		this.upgradeSubs();

	},
	computed: {
		searchres: function () {
				var parent = this;
				if (this.allMensas[this.type].length!=0) {
					let filtered = this.allMensas[this.type].filter(function (s) {
						let str = s.nameA.toLowerCase()+' '+s.nameB.toLowerCase();
						return str.includes(parent.searchword.toLowerCase());
					})
					return filtered;
				}
				else return [];
		}
	},
	props: ['data'],
	methods: {
		changeType: function (nr) {
			this.searchword = "";
			switch(nr) {
				case 0:
					this.type = 1;
					break;
				case 1:
					this.type = 0;
					break;
				case 2:
					this.type = 2;
			}
			this.data.type = this.type;
			this.upgradeSubs();
		},
		upgradeSubs: function () {
			if (!this.loadedsubs[this.type]) {
				this.$root.$data.netC.getSingleMensa(this.$root.$data.storageC.settings.mensas[this.type]).then((result) => {
					this.$set(this.subscribed, this.type, result);
					this.loadedsubs[this.type] = true;
				},
				(reason) => {

				});
			}
		},
		changeSearchword: function (string) {
			this.searchword = string;
		},
	},
	components: {
		verticalSelect,
		searchBox,
		loadGlow,
		LocationFrame,
		openingTimes
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

<style module>
	.wrapper {
		padding: 0 10px 10px 10px;
	}
	.loading {
		height: 14px;
		margin-top: 2px;
	}
</style>
