<template>
	<div :class="$style.wrapper">
		<verticalSelect :items="vertical_data" :itemTranslation="[1,0,2]" :activeitem="this.type" v-on:triggered="changeType"></verticalSelect>
		
		<searchBox ref="search" :loading="this.loading" :searchword="searchword" :results="availableItems_computed.length+this.subscribedItems[this.type].length" :placeholder="this.$tc('action.search')" v-on:inputChange="changeSearchword">
			<div :class="whiteboxstyle">
			<draggable :list="subscribedItems[this.type]" @change="dragMoved" :options="{handle:'.handle'}">
					<div v-for="item in this.subscribedItems[this.type]" :key="item._id" :class="[$style.elem, $style.elem_draggable]">
						<div :class="['handle',$style.action]">
							<icon :svg="drag_handle"></icon>
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

				<div :class="$style.elem" v-for="item in availableItems_computed" v-if="item.visible || item.visible==undefined" v-bind:key="item._id">
					<div :class="$style.info">
						<div :class="$style.name"><span>{{ item.nameA }}</span> {{ item.nameB }}</div>
						<div :class="$style.adress">{{ item.location.adress }}</div>
					</div>
					<div v-on:click="toggleSub(item)" :class="$style.action">
						<icon v-ripple :class="['mdc-ripple-surface', $style.checksvg, $style.checksvg_add]" :svg="add_circle_outline"></icon>
					</div>
				</div>
				</div>
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
	props: ['whiteboxstyle'],
	computed: {
		availableItems_computed() {
			return this.searchres(this.availableItems[this.type], 0);
		}
	},
	methods: {
		searchres: function (elements, nr) {
				var parent = this;
				if (elements!=undefined) {
					let filtered = elements.filter(function (s) {
						let str = s.nameA.toLowerCase()+' '+s.nameB.toLowerCase();
						return str.includes(parent.searchword.toLowerCase());
					})
					return filtered;
				}
				else return [];
		},
		changeSearchword: function (string) {
			this.searchword = string;
		},
		changeType: function (nr) {
			//this.searchword = "";
			this.$refs.search.deleteInput();
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
		dragMoved: function (a,b,c) {
			this.$root.$data.storageC.updateStorage();
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
				this.$root.$data.storageC.setMensas(item._id, null, null, this.type, null, true);
				for (var i = this.availableItems[this.type].length - 1; i >= 0; i--) {
					if (this.availableItems[this.type][i]._id === item._id) {
						this.availableItems[this.type][i].visible = true;
					}
				}
			} else {
				if (this.$root.$data.storageC.getPrimaryMensa()===undefined)
					this.$root.$data.storageC.settings.primarytype = this.type;
				this.$root.$data.storageC.setMensas(item._id, item.nameA, item.nameB, item.type, item.hasMenu, false);
				item.visible = false;
			}
		}
	},
	directives: {
		ripple: {
			bind(el, binding, vnode) {
				//MDCRipple.attachTo(el);
			}, update(el, binding, vnode) {
				//MDCRipple.attachTo(el);
			}
		}
	}
}
</script>



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
	.mvelistdown-move {
		transition: transform .4s;
	}
</style>