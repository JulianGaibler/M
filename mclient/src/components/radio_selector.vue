<template>
	<div :class="$style.selectwb">
		<div v-for="(item, index) in data" :key="index" :class="[$style.selectcontainer, item.desc?$style.containerMargin:'']">
			<div class="mdc-radio">
				<input v-model="selectedIndexLocal" :checked="index==selectedIndexLocal" :value="index" class="mdc-radio__native-control" type="radio" :id="randID+'-radio-'+index" :name="randID" checked>
				<div class="mdc-radio__background">
					<div class="mdc-radio__outer-circle"></div>
					<div class="mdc-radio__inner-circle"></div>
				</div>
			</div>
			<label :id="randID+'-radio-'+index+'-label'" :for="randID+'-radio-'+index" :class="$style.label">
				<h1>{{ item.head }}</h1>
				<p v-if="item.desc">{{ item.desc }}</p>
			</label>
		</div>
	</div>
</template>

<script>

export default {  
	name: 'radioSelector',
	props: ['data', 'selectedIndex', 'methd'],
	data () {
		return {
			randID: Math.random().toString(36).substring(7),
			selectedIndexLocal: this.selectedIndex
		}
	},
	watch: {
		selectedIndexLocal: function (nr) {
			this.methd(nr);
		}
	}
};
</script>

<style src="@material/radio/dist/mdc.radio.min.css"/>
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