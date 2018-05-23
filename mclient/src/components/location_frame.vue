<template>
	<div v-if="status===0" :class="$style.flex">
		<div class="a">{{requestor}}</div>
		<div>
			<button class="mdc-button" v-on:click="getLocation()">{{$t('action.uselocation')}}</button>
		</div>
	</div>
	<div v-else-if="status===1" :class="$style.flex">
		<div class="a">Locating...</div>
		<div :class="$style.spinner"></div>
	</div>
	<component v-else-if="status===2 && component" :data="data" :location="loc.coords" :is="component"></component>
	<div v-else-if="status===2"><slot :location="loc.coords"></slot></div>
	<div v-else-if="status===-2" :class="$style.flex">
		<div class="a">{{$t('result.error_location_other')}}</div>
		<div>
			<button class="mdc-button" v-on:click="getLocation()">{{$t('action.tryagain')}}</button>
		</div>
	</div>
</template>

<script>

export default {
	// status
	// -2: error
	// -1: disabled
	//  0: ask
	//  1: loading
	//  2: show
	name: 'LocationFrame',
	props: ['requestor', 'component', 'data'],
	data () {
		let pref = this.$root.$data.storageC.settings.location;
		return {
			locationSetting: pref,
			status: (pref===0) ? -1 : (pref===1) ? 0 : 1,
			loc: undefined
		}
	},
	mounted: function() {
		if (this.locationSetting==2) this.getLocation();
	},
	methods: {
		getLocation: function (data) {
			let that = this;
			this.status = 1;
			this.$root.$data.netC.getCurrentPosition()
				.then(result => {
					that.loc = result;
					that.status = 2;
				})
				.catch(error => {
					that.status = -2;
				})
		}
	}
}
</script>



<style module lang="scss">
	.flex {
		display: flex;
		padding: 5px 5px 5px 15px;
		[class="a"] {
			flex: 1;
			color: #817575;
			font-size: 14px;
			text-transform: uppercase;
			font-family: 'Roboto Condensed', sans-serif;
		}
		@media screen and (max-width: 400px) {
			flex-direction: column;
			padding: 15px 10px 5px 10px;
			text-align: center;
		}
	}

.spinner {
	width: 36px;
	height: 36px;
	position: relative;
	margin-right: 5px;
	&::after, &::before {
		opacity: 0;
		position: absolute;
		left: 0; right: 0;
		top: 0; bottom: 0;
		content: '';
		animation: sk-scaleout 1.5s infinite cubic-bezier(.22,.61,.36,1);
		border-radius: 100%;
		background-color: var(--mdc-theme-primary, #651fff);
	}
	&::after {
		animation-delay: .75s;
	}
}

@keyframes sk-scaleout {
	0% { 
		-webkit-transform: scale(0);
		transform: scale(0);
		opacity: 1;
	} 100% {
		-webkit-transform: scale(1.0);
		transform: scale(1.0);
		opacity: 0;
	}
}
</style>