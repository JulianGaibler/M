<template>
	<div>
		<verticalSelect :items="vertical_data" :itemTranslation="[1,0,2]" :activeitem="this.type" v-on:triggered="changeType"></verticalSelect>
		
		<searchBox :loading="this.loading" :searchword="searchword" :results="availableItems_computed.length+subscribedItems_computed.length" :placeholder="this.$tc('action.search')" v-on:inputChange="changeSearchword">
			<draggable :list="subscribedItems[this.type]" :options="{handle:'.handle'}">
					<div :class="[$style.elem, $style.elem_draggable]" v-for="item in subscribedItems_computed" :key="item._id">
						<div :class="['handle',$style.action]">
							<icon :class="$style.dragsvg" :svg="drag_handle"></icon>
						</div>
						<div :class="$style.info">
							<div :class="$style.name"><span>{{ item.nameA }}</span> {{ item.nameB }}</div>
							<div :class="$style.adress">{{ $t('state.added') }}</div>
						</div>
						<div :class="$style.action">
							<icon v-if="isprimary(item)" :class="$style.starsvg" :svg="star_filled"></icon>
							<span v-else v-on:click="makePrimary(item)"><icon v-ripple :class="['mdc-ripple-surface', $style.starsvg]" :svg="star_border"></icon></span>
							<span v-on:click="toggleSub(item, true)"><icon v-ripple :class="['mdc-ripple-surface', $style.checksvg, $style.checksvg_rem]" :svg="remove_circle_outline"></icon></span>
						</div>
					</div>
				</draggable>

				<transition-group name="mvelistup" tag="div">
				<div :class="$style.elem" v-for="item in availableItems_computed" v-if="item.visible || item.visible==undefined" v-bind:key="item._id">
					<div :class="$style.info">
						<div :class="$style.name"><span>{{ item.nameA }}</span> {{ item.nameB }}</div>
						<div :class="$style.adress">{{ item.location.adress }}</div>
					</div>
					<div v-on:click="toggleSub(item)" :class="$style.action">
						<icon v-ripple :class="['mdc-ripple-surface', $style.checksvg, $style.checksvg_add]" :svg="add_circle_outline"></icon>
					</div>
				</div>
			</transition-group>
		</searchBox>
	</div>
</template>

<script>
import icon from './../components/icon.vue';
import verticalSelect from './../components/vertical_select.vue';
import searchBox from './../components/search_box.vue';
import loader from './../components/loader.vue';
import draggable from 'vuedraggable'
import {MDCRipple, MDCRippleFoundation, util} from '@material/ripple';

export default {
	name: 'mensaselector',
	data () {
		return {
			add_circle_outline: require('./../assets/add_circle_outline.svg'),
			remove_circle_outline: require('./../assets/remove_circle_outline.svg'),
			star_border: require('./../assets/star_border.svg'),
			star_filled: require('./../assets/star_filled.svg'),
			drag_handle: require('./../assets/drag_handle.svg'),
			searchword: "",
			loading: 0,
			type: 0,
			actualPrimary: null,
			previousPrimary: null,
			availableItems: [],
			results_count: [1,1],
			subscribedItems: this.$root.$data.storageC.settings.mensas,
			vertical_data: [this.$tc('type.1',2),this.$tc('type.0',2),this.$tc('type.2',2)]
		}
	},
	mounted: function() {
		this.loading = 1;
		this.$root.$data.dataC.getLightMensas().then((allMensas) => {
			for (var a = this.subscribedItems.length - 1; a >= 0; a--) {
				for (var b = this.subscribedItems[a].length - 1; b >= 0; b--) {
					for (var x = allMensas.length - 1; x >= 0; x--) {
						for (var y = allMensas[x].length - 1; y >= 0; y--) {
							if (allMensas[x][y]._id === this.subscribedItems[a][b]._id) allMensas[x][y].visible = false;
						}
					}
				}
			}
			this.availableItems = allMensas;
			this.loading = false;
		},
		(reason) => {
			this.loading = 2;
		})
	},
	components: {
		icon,
		draggable,
		loader,
		verticalSelect,
		searchBox
	},
	computed: {
		subscribedItems_computed() {
			return this.searchres(this.subscribedItems[this.type], 1)
		},
		availableItems_computed() {
			return this.searchres(this.availableItems[this.type], 0);
		}
	},
	methods: {
		searchres: function (elements, nr) {
				var parent = this;
				if (elements!=undefined) {
					let filtered = elements.filter(function (s) {
						return s.nameB.toLowerCase().includes(parent.searchword.toLowerCase());
					})
					return filtered;
				}
				else return [];
		},
		changeSearchword: function (string) {
			this.searchword = string;
		},
		changeType: function (nr) {
			this.searchword = "";
			this.type = nr;
			switch(nr) {
			    case 0:
			        this.type = 1;
			        break;
			    case 1:
			        this.type = 0;
			        break;
			    case 2:
			        this.type = 2;
			}
		},
		isprimary: function (item) {
			let primary = this.$root.$data.storageC.getPrimaryMensa();
			if (primary===undefined) return false;
			if (primary._id===item._id) return true;
			else return false;
		},
		makePrimary: function (item) {
			this.$root.$data.storageC.setPrimaryMensa(item._id, this.type);
		},
		toggleSub: function (item, unsub=false) {
			if (unsub) {
				this.$root.$data.storageC.setMensas(item._id, item.nameA, item.nameB, this.type, true);
				for (var i = this.availableItems[this.type].length - 1; i >= 0; i--) {
					if (this.availableItems[this.type][i]._id === item._id) {
						this.availableItems[this.type][i].visible = true;
					}
				}
			} else {
				if (this.$root.$data.storageC.getPrimaryMensa()===undefined)
					this.$root.$data.storageC.settings.primarytype = this.type;
				this.$root.$data.storageC.setMensas(item._id, item.nameA, item.nameB, item.type, false);
				item.visible = false;
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

	.elem {
		padding: 15px 10px 15px 20px;
		display: flex;
	}
	.elem_draggable {
		background-color: white;
	}
	.elem_placeholder .name {
		background-color: grey;
		width: 50px;
	}
	.elem_placeholder .name::before, .elem_placeholder .adress::before {
		content: "-";
	}

	.info {
		flex: 1;
		display: flex;
		align-items: stretch;
		flex-flow: column;
		justify-content: center;

	}
	.action {
		flex: 0;
		display: flex;
	}

	.name {}
	.adress {
		color: #a3a3a3;
		font-size: 14px;
	}
	.checksvg {
		width: 25px;
		height: 25px;
		padding: 5px;
		border-radius: 50%;
		cursor: pointer;
	}
	.checksvg_add {
		fill: #45c89c;
	}
	.checksvg_rem {
		fill: #ff543c;
	}

	.starsvg {
		width: 25px;
		height: 25px;
		padding: 5px;
		border-radius: 50%;
		cursor: pointer;
		fill: #a3a3a3;
	}
	.dragsvg {
		width: 25px;
		height: 25px;
		margin: 10px 15px 10px -5px;
		fill: #a3a3a3;
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
	
</style>

<style>
	.mvelistup-enter-active, .mvelistup-leave-active, .mvelistdown-enter-active, .mvelistdown-leave-active {
		transition: all .4s;
		max-height: 85px;
		-webkit-transform: translateZ(0);
		-moz-transform: translateZ(0);
		-ms-transform: translateZ(0);
		-o-transform: translateZ(0);
		transform: translateZ(0);
	}
	.mvelistdown-move {
		transition: transform .4s;
	}
	.mvelistup-enter, .mvelistup-leave-to {
		opacity: 0;
  		transform: rotateX(90deg);
	}
	.mvelistdown-enter, .mvelistdown-leave-to {
		opacity: 0;
  		transform: rotateX(90deg);
	}


	.handle {
		cursor: move;
	}

</style>