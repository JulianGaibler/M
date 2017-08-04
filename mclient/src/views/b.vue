<template>
	<div>
		<div>Vorspeisen</div>
		<div :class="$style.foodbox">
			<div :class="$style.trafficm"><icon :logocolor='"#ff543c"' :svg="iconM"></icon></div>
			<div :class="$style.text">
				<div>Tomate mit Mozzarella und frischem Basilikum</div>
				<div>vegan | 3 Zusatzstoffe</div>
			</div>
			<div :class="$style.price">
				<div>€ 2,45</div>
			</div>
		</div>

		<div>Aktionen</div>
	</div>
</template>

<script>
import icon from './../components/icon.vue';

export default {
  data () {
	return {
		iconM: require('./../assets/theM.svg'),
	}
  },
  components: {
		icon
	}
}
</script>

<style module>
	.foodbox {
		display: flex;
	}
	.foodbox > div {
		padding: 15px;
	}
	.trafficm svg {
		width: 30px;
		height: 30px;
	}
	.text {
		flex-grow: 1;
	}
</style>
