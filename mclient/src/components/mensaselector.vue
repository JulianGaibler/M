<template> 
	<loader v-if="loading"></loader>
	<div v-else :class="$style.selectbox">
			<div :class="$style.search">
				<icon :logocolor='"#ff543c"' :svg="iconsearch"></icon>
				<input v-model="searchword" placeholder="Search">
			</div>
			 <transition-group name="mvelist" :class="$style.scrollbox" tag="div">
				<div v-for="item in searchres(items)" v-on:click="toggleSub(item.id, true, true)" v-ripple :class="['mdc-ripple-surface', $style.element]"  v-bind:key="item.id">
					<div :class="$style.mensaname"><span>{{ item.name }}</span></div>
					<div :class="$style.select"><div :class='[$style.slctbox, item.subscribed==0 ? $style.slctbox_0 : (item.subscribed==1 ? $style.slctbox_1 : $style.slctbox_2)]'><span></span></div></div>
				</div>
			</transition-group>
	</div>
</template>

<script>
import icon from './../components/icon.vue';
import loader from './../components/loader.vue';
import {MDCRipple, MDCRippleFoundation, util} from '@material/ripple';

export default {
name: 'mensaselector',
	data () {
		return {
			iconsearch: require('./../assets/search.svg'),
			searchword: "",
			loading: false,
			actualPrimary: null,
			previousPrimary: null,
			items: {}
		}
	},
	mounted: function() {
		this.loading = true;
		let lang = this.$root.$data.storageC.settings.language;
		this.$root.$data.dataC.getMensas(lang).then((allMensas) => {
			let subMensas = this.$root.$data.storageC.settings.mensas;
			for (let m of allMensas) {
				m.subscribed = 0;
				for (var i = subMensas.length - 1; i >= 0; i--) {
					if (subMensas[i].id === m.id) {
						if (subMensas[i].primary) {
							m.subscribed = 2;
							this.actualPrimary = m;
							this.previousPrimary = m;
						}
						else m.subscribed = 1;
						break;
					}
				}
			}
			this.items = allMensas;
			this.loading = false;
		})
	},
	components: {
		icon,
		loader
	},
	methods: {
		searchres: function (elements) {
			if (typeof elements.filter !== "undefined") { 
				var parent = this;
				return elements.filter(function (s) {
					return s.name.toLowerCase().includes(parent.searchword.toLowerCase());
				})
			}
		},
		toggleSub: function (givenid, upgrade, user) {
			let m;
			if (isNaN(givenid)) m = givenid;
			else {
				for (let item of this.items) {
					if (givenid === item.id) {
						m = item;
						break;
					}
				}
			}
			upgrade ? m.subscribed++ : m.subscribed--;
			if (m.subscribed==3 && !this.previousPrimary) {
				m.subscribed = 2;
				return;
			}
			if (!this.actualPrimary) m.subscribed = 2;
			else {
				if (m.subscribed<0) m.subscribed = 2;
				else if (m.subscribed>2) m.subscribed = 0;
			}

			if (m.subscribed == 0) {
				//Storage: UNSUBSCRIBE
				this.$root.$data.storageC.setMensas(m.id, null, null, true);

				if (this.previousPrimary && this.actualPrimary===m && user) {
					this.previousPrimary.subscribed = 1;
					this.toggleSub(this.previousPrimary,true);
				}
			} else if (m.subscribed == 1) {
				//Storage: Standard
				this.$root.$data.storageC.setMensas(m.id, m.name, false);
				
			} else if (m.subscribed == 2) {
				//Storage: Primary
				this.$root.$data.storageC.setMensas(m.id, m.name, true);

				if (this.actualPrimary && this.actualPrimary!==m && user) this.toggleSub(this.actualPrimary,false);
				this.previousPrimary = this.actualPrimary;
				this.actualPrimary = m;
			}
		}
	},
	directives: {
		ripple: {
			bind(el, binding, vnode) {
				MDCRipple.attachTo(el);
			}, update(el, binding, vnode) {
				MDCRipple.attachTo(el);
			}
		}
	}
}
</script>

<style src="@material/ripple/dist/mdc.ripple.min.css"/>

<style module>
	.selectbox {
		font-weight: 400;
		position: relative;
		font-size: 14px;
	}

	.search {
		display: flex;
		padding: 10px 20px;
	}

	.search > div {
		width: 25px;
		height: 25px;
		margin: 10px 10px 10px 0;
	}
	.search > input {
		flex: 1;
		border: none;
		outline: none;
	}

	.scrollbox {
		overflow-y: scroll;
		height: 35vh;
		-webkit-user-select: none;    
		-moz-user-select: none;
		-ms-user-select: none;
		cursor: default;
	}
	.scrollbox::-webkit-scrollbar {
		width: 5px;
	}
	 
	.scrollbox::-webkit-scrollbar-track {
		background: #ddd;
	}
	 
	.scrollbox::-webkit-scrollbar-thumb {
		background: #666;
	}
	.element {
		max-height: 200px;
		overflow: hidden;
		display: flex;
	}
	.element > .mensaname {
		flex: 1;
		text-align: left;
		padding: 15px;
		display: flex;
		align-items: center;
		line-height: 1.5;
	}
	.element > .select {
		padding: 20px;
	}

	.slctbox {
		border: 1.5px solid #ff543c;
		border-radius: 50%;
		width: 16px;
		height: 16px;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.slctbox > span {
		transition: border 0.1s, height 0.2s, width 0.2s, background 0.2s;
	}
	.slctbox.slctbox_0 > span {
		content: "";
		display: block;
		border: 0px solid #ff543c;
		border-radius: 50%;
		width: 0px;
		height: 0px;
	}
	.slctbox.slctbox_1 > span {
		content: "";
		display: block;
		border: 1.5px solid #ff543c;
		border-radius: 50%;
		width: 6px;
		height: 6px;
	}
	.slctbox.slctbox_2 > span {
		content: "";
		display: block;
		border: 1.5px solid #ff543c;
		background-color: #ff543c;
		border-radius: 50%;
		width: 6px;
		height: 6px;
	}
</style>

<style>
	.mvelist-enter-active, .mvelist-leave-active {
		transition: max-height 0.25s;
		overflow: hidden;
	}
	.mvelist-enter, .mvelist-leave-to {
		max-height: 0px;
	}
</style>