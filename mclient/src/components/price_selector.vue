<template>
	<div class="whitebox">
		<div v-for="index in 3" :class="$style.selectcontainer">
			<div class="mdc-radio">
				<input v-model="pricetype" :checked="index==(pricetype+1)" :value="index-1" class="mdc-radio__native-control" type="radio" :id="'radio-'+index" name="radios" checked>
				<div class="mdc-radio__background">
					<div class="mdc-radio__outer-circle"></div>
					<div class="mdc-radio__inner-circle"></div>
				</div>
			</div>
			<label :id="'radio-'+index+'-label'" :for="'radio-'+index" :class="$style.label">
				<h1>{{ $t('prices.category') }} {{index}}</h1>
				<p>{{ $t('prices.desc_'+index) }}</p>
			</label>
		</div>
	</div>
</template>

<script>

export default {  
	name: 'priceSelector',
	data () {
		return {
			pricetype: this.$root.$data.storageC.settings.pricetype
		}
	},
	watch: {
		pricetype: function (nr) {
			this.$root.$data.storageC.setPricetype(nr);
		}
	}
};
</script>

<style src="@material/radio/dist/mdc.radio.min.css"/>
<style module>
	.selectcontainer {
		display: flex;
		align-items: center;
		margin: 20px 10px;
	}
	.label {
		padding-left: 10px;
	}
	.label h1 {
		font-size: 16px;
		margin: 0 0 5px 0;

	}
	.label p {
		margin: 0;
	}
</style>