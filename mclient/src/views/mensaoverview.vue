<template>
	<div :class="$style.wrapper">
		 <verticalSelect :items="vertical_data" :itemTranslation="[1,0,2]" :activeitem="this.type" v-on:triggered="changeType"></verticalSelect>
		 <div class="whitebox">
			<div class="whitebox_header">{{ $t('type.my') }} {{ $tc('type.'+this.type,2) }}</div>
			<div v-on:click="this.bus.$emit('changeview', 'singlemensa', {_id: item._id, type: type})" v-for="item in subscribed[this.type]" class="whitebox_element">
				<div class="whitebox_element_top">{{item.nameA}} {{item.nameB}}</div>
				<div class="whitebox_element_bottom">
					<loadGlow v-if="item.location===undefined" :extStyle="$style.loading" :dimension="{min:50, max:200, end: 'px'}"></loadGlow>
					<span v-else><openingTimes :times="item.location.times"></openingTimes></span>
				</div>
			</div>
		 </div>
		 <searchBox :loading="this.loadingall" :searchword="searchword" :results="allMensas[this.type].length" :placeholder="this.$t('type.all')+' '+this.$tc('type.'+this.type,2)" v-on:inputChange="changeSearchword">
			<div v-for="item in allMensas[this.type]" class="whitebox_element">
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
import openingTimes from './../components/opening_times.vue';

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
			vertical_data: [this.$tc('type.1',2),this.$tc('type.0',2),this.$tc('type.2',2)]
		}
	},
	mounted: function() {
		this.type = this.$root.$data.storageC.settings.primarytype;
		this.subscribed = this.$root.$data.storageC.settings.mensas;

		this.loadingall = 1;
		this.$root.$data.dataC.getLightMensas().then((allMensas) => {
			this.allMensas = allMensas;
			this.loadingall = 0;
		},
		(reason) => {
			this.loadingall = 2;
		});

		this.upgradeSubs();

	},
	methods: {
		changeType: function (nr) {
			this.searchword = "";
			this.type = nr;
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
			this.upgradeSubs();
		},
		upgradeSubs: function () {
			if (!this.loadedsubs[this.type]) {
				this.$root.$data.dataC.getSingleMensa(this.$root.$data.storageC.settings.mensas[this.type]).then((result) => {
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
		openingTimes
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
