<template>
	<div :class="$style.wrapper">
		 <verticalSelect :items="vertical_data" :itemTranslation="[1,0,2]" :activeitem="this.type" v-on:triggered="changeType"></verticalSelect>
		 <div class="whitebox">
			<div class="whitebox_header">{{ $t('type.my') }} {{ $tc('type.'+this.type,2) }}</div>
			<div v-for="item in subscribed[this.type]" class="whitebox_element">
				<div class="whitebox_element_top">{{item.nameA}} {{item.nameB}}</div>
				<div class="whitebox_element_bottom">
					<div v-if="item.location===undefined" :class="[$style.loading, 'loading', 'loading_color']" :style="{ width: getRandomArbitrary(50,200)+'px', animationDelay: getRandomArbitrary(0.2,1)+'s' }"></div>
					<span v-else>{{ openingInfo(item.location.times) }}</span>
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
import icon from './../components/icon.vue';
import verticalSelect from './../components/vertical_select.vue';
import searchBox from './../components/search_box.vue';
import moment from "moment";
import locale_de from "moment/locale/de";
import locale_en from "moment/locale/en-gb";

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
		switch (this.$root.$data.storageC.settings.language) {
			case 'de':
				moment.updateLocale("de",locale_de);
				break;
			case 'en':
				moment.updateLocale("en-gb",locale_en);
		}
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
					this.$set(this.subscribed, [this.type], result);
					this.loadedsubs[this.type] = true;
				},
				(reason) => {

				});
			}
		},
		openingInfo: function (times) {
			let mmt = moment();
			let weekday = mmt.day()-1;
			let mmtMidnight = mmt.clone().startOf('day');
			let diffMinutes = mmt.diff(mmtMidnight, 'minutes');

			for (var i = 0; i < times.length; i++) {
				let wd = weekday;
				while (true) {
					if (times[i].hours[wd]!==undefined) {
						if (wd===weekday) {
							if (diffMinutes-100 < times[i].hours[wd].close) {
								break;
							}
						} else break;
					}
					wd = (wd+1)%7;
					mmtMidnight.add(1, 'd');
				}
				let hours = times[i].hours[wd];

				if ((diffMinutes < hours.open && wd===weekday) || (diffMinutes-100 >= hours.close && wd!==weekday)) {
					//TO BE OPENED
					let t = mmtMidnight.add(hours.open, 'm');
					if (hours.open-60 < diffMinutes && wd===weekday) return this.$t('times.opens')+" "+t.fromNow();
					return this.$t('times.opens')+" "+t.calendar();
				} else if (diffMinutes >= hours.open && diffMinutes <= hours.close) {
					//OPEN
					let t = mmtMidnight.add(hours.close, 'm');
					if (hours.close-60 < diffMinutes) return this.$t('times.closes')+" "+t.fromNow();
					return this.$t('times.closes')+" "+t.calendar();
				} else {
					//CLOSED RECENTLY
					return this.$t('times.closed')+" "+mmtMidnight.add(hours.close, 'm').fromNow();
				}
			}
		},
		getRandomArbitrary: function (min, max) {
			return Math.random() * (max - min) + min;
		},
		changeSearchword: function (string) {
			this.searchword = string;
		},
	},
	components: {
		verticalSelect,
		searchBox,
		moment,
		icon
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
