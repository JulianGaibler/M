<template>
	<div >
		<div :class="[wrap, $style.wrap, time.statusstyle]" v-for="time in openingInfo">
			<span :class="[badge, $style.badge]" v-if="time.name">{{time.name}}</span> <span>{{time.text}}</span>
		</div>
	</div>
</template>

<script>  
import moment from "moment";
import locale_de from "moment/locale/de";
import locale_en from "moment/locale/en-gb";

export default {  
	name: 'openingTimes',
	data () {
		return {
			now: moment()
		}
	},
	props: ['times', 'wrap', 'badge'],
	created: function() {
		setInterval(() => this.now = moment(), 60*1000);
	},
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
		this.now = moment();
	},
	computed: {
		openingInfo: function () {
			let mmt = this.now;
			let weekday = ((((mmt.day()-1) % 7) + 7) % 7);
			let mmtMidnight = mmt.clone().startOf('day');
			let diffMinutes = mmt.diff(mmtMidnight, 'minutes');

			let bucket = [];
			for (var i = 0; i < this.times.length; i++) {
				let mmtclone = mmtMidnight.clone();
				let wd = weekday;

				while (true) {
					if (this.times[i].hours[wd]!==undefined) {
						if (wd===weekday) {
							if (diffMinutes-55 < this.times[i].hours[wd].close) {
								break;
							}
						} else break;
					}
					wd = (wd+1)%7;
					mmtclone.add(1, 'd');
				}
				let hours = this.times[i].hours[wd];

				let name = false;
				if (this.times[i].name_de!==undefined) {
					switch (this.$root.$data.storageC.settings.language) {
						case 'de':
							name = this.times[i].name_de;
							break;
						case 'en':
							name = this.times[i].name_en;
					}
				}

				let start = (this.times[i].type==0) ? this.$t('times.opens') : this.$t('times.starts');
				let ongoing = (this.times[i].type==0) ? this.$t('times.closes') : this.$t('times.ends');
				let ended = (this.times[i].type==0) ? this.$t('times.closed') : this.$t('times.ended');

				if ((diffMinutes < hours.open && wd===weekday) || (wd!==weekday)) {
					//TO BE OPENED
					let t = mmtclone.add(hours.open, 'm');
					if (hours.open-10 < diffMinutes && wd===weekday) {
						bucket.push({
							statusstyle: this.$style.s3,
							name: name,
							text: start+" "+t.fromNow()
						});
						continue;
					}
					else {
						bucket.push({
							statusstyle: '',
							name: name,
							text: start+" "+t.calendar()
						});
						continue;
					}
				} else if (diffMinutes >= hours.open && diffMinutes <= hours.close) {
					//OPEN
					let t = mmtclone.add(hours.close, 'm');
					if (hours.close-60 < diffMinutes) {
						bucket.push({
							statusstyle: this.$style.s1,
							name: name,
							text: ongoing+" "+t.fromNow()
						});
					continue;
					}
					else {
						bucket.push({
							statusstyle: '',
							name: name,
							text: ongoing+" "+t.calendar()
						});
					continue;
					}
				} else {
					//CLOSED RECENTLY
					bucket.push({
						statusstyle: this.$style.s2,
						name: name,
						text: ended+" "+mmtclone.add(hours.close, 'm').fromNow()
					});
					continue;
				}
			}
			return bucket;
		}
	}
};
</script>

<style module>
	.wrap {
		margin: 5px 5px 5px 0;
	}
	.wrap:first-child:first-letter {
		text-transform: uppercase;
	}

	.s1 {
		color: #FF9800;
	}
	.s2 {
		color: #F44336;
	}
	.s3 {
		color: #45c89c;
	}
</style>