<template>
<div ref="slider" class="mdc-slider mdc-slider--discrete" tabindex="0" role="slider"
		 :aria-valuemin="valuemin" :aria-valuemax="valuemax" :aria-valuenow="valuenow" :data-step="step"
		 aria-label="Select Value">
	<div class="mdc-slider__track-container">
		<div class="mdc-slider__track"></div>
	</div>
	<div class="mdc-slider__thumb-container">
		<div class="mdc-slider__pin">
			<span class="mdc-slider__pin-value-marker"></span>
		</div>
		<svg class="mdc-slider__thumb" width="21" height="21">
			<circle cx="10.5" cy="10.5" r="7.875"></circle>
		</svg>
		<div class="mdc-slider__focus-ring"></div>
	</div>
</div>
</template>

<script>
import {MDCSlider} from '@material/slider';

export default {  
	name: 'slider',
	props: ['valuemin', 'valuemax', 'valuenow', 'step', 'methd'],
	data () {
		return {
			slider: undefined
		}
	},
	mounted: function() {
		this.slider = new MDCSlider(this.$refs.slider);
		this.slider.listen('MDCSlider:change', this.methd);
	},
};
</script>


<style module>
	.selectwb {
		padding: 10px 0;
	}
	.selectcontainer {
		display: flex;
		align-items: center;
		padding: 0 10px;
	}
	.containerMargin {	
		padding: 10px;
	}
	.label {
		padding-left: 10px;
	}
	.label h1 {
		font-size: 16px;

	}
	.label p {
		margin: 5px 0 0 0;
	}
</style>