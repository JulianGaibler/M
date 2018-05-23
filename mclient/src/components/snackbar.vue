<template> 
	<div class="mdc-snackbar"
			aria-live="assertive"
			aria-atomic="true"
			aria-hidden="true">
		<div class="mdc-snackbar__text"></div>
		<div class="mdc-snackbar__action-wrapper">
			<button type="button" class="mdc-snackbar__action-button"></button>
		</div>
	</div>
</template>

<script>
import {MDCSnackbar, MDCSnackbarFoundation} from '@material/snackbar';

export default {
	name: 'Snackbar',
	data () {
		return {
			snackbar: undefined
		}
	},
	created: function () {
		bus.$on('showSnackbar', this.show);
	},
	mounted: function() {
		this.snackbar = new MDCSnackbar(document.querySelector('.mdc-snackbar'))
	},
	methods: {
		show: function (data) {
			if (this.snackbar) this.snackbar.show(data);
		}
	}
}
</script>