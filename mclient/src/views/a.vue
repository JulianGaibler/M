<template>
	<div class="adaptiveWrap">
		<div :class="$style.stl" class="whitebox">
			<img :class="$style.image" :src="gramps"> 
			<p>Not ready yet. Sorry!</p>
		</div>
	</div>
</template>

<script>

export default {
  data () {
	return {
		gramps: require('./../assets/slowdown.png'),
	}
  }
}
</script>

<style module>
	.stl {
		text-align: center;
		padding: 20px;
	}
	.stl img {
		margin: 0 auto;
		width: 100%;
		max-width: 200px;
		display: inline-block;
	}
</style>
