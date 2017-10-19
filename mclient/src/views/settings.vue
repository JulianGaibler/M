<template>
	<div class="adaptiveWrap">
		<div :class="$style.headline">
			<h1>{{ $t('frame.settings') }}</h1>
		</div>
		<div :class="$style.section">{{ $t('prices.category') }}</div>
		<priceSelector></priceSelector>

		<div :class="$style.section">{{ $t('settings.search_and_hightlight') }}</div>
		<chipsfield :items="this.$root.$data.storageC.settings.highlights" :class="[$style.chipsfield, 'whitebox']"></chipsfield>

		<div :class="$style.section">{{ $t('settings.mensa_selector') }}</div>
		<mensaselector></mensaselector>
	</div>
</template>

<script>
import priceSelector from './../components/price_selector.vue';
import chipsfield from './../components/chipsfield.vue';
import mensaselector from './../components/mensaselector.vue';

export default {
	data () {
	return {}
	},
	components: {
		priceSelector,
		chipsfield,
		mensaselector
	}
}
</script>

<style module>
	.chipsfield {
		overflow: hidden;
		flex-grow: 1;
		min-height: 25vh;
	}
	.headline {
		color: #303030;
		font-family: 'Roboto Condensed', sans-serif;
		text-align: center;
	}
	.section {
		font-family: 'Roboto Condensed', sans-serif;
		text-transform: uppercase;
		padding-top: 10px;
		padding-bottom: 10px;
		text-align: center;
	}
</style>
