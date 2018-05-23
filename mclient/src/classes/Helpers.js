export default class Helpers {

	static cloneArray(arr) {
		return arr.slice(0);
	}

	static getRandomInt(min, max) {
			return Math.floor(Math.random() * (max - min + 1)) + min;
	}

	static translateAdditives(all, ids, lang) {
		let newArr = [];
		if (ids!==null) {
			for (var j = 0; j < ids.length; j++) {
				for (var i = all.length - 1; i >= 0; i--) {
					if (ids[j]===all[i].id) {
						newArr.push({
							id: all[i].id,
							name: all[i]['name_'+lang]
						});
						break;
					}
				}
			}
		} else {
			for (var i = all.length - 1; i >= 0; i--) {
					newArr.push({
						id: all[i].id,
						name: all[i]['name_'+lang]
					})
			}
		}
		return newArr;
	}

	static menuSort(menu, sort) {
		if (sort.length > 0) {
			let bucket = [];
			for (var s = 0; s < sort.length; s++) {
				for (var i = 0; i < menu.length; i++) {
					if (menu[i].name === sort[s].tag) {
						menu[i].extended = sort[s].show;
						bucket.push(menu[i]);
						menu.splice(i, 1);
						break;
					}
				}
			}
			return bucket.concat(menu);
		}
		return menu;
	}

	static multiMenuDiet(multiMenu, diet) {
		if (diet > 0) {
			for (var k = multiMenu.length - 1; k >= 0; k--) {
				multiMenu[k].items = this.menuDiet(multiMenu[k].items, diet);
				if (multiMenu.length < 1) multiMenu.splice(k, 1);
			}
		}
		return multiMenu;
	}

	static menuDiet(menu, diet) {
		if (diet > 0) {
			for (var i = menu.length - 1; i >= 0; i--) {
				if (diet==1) {
					if (!menu[i].labels.includes("vegetarian") &&
						!menu[i].labels.includes("vegan")) {
						menu.splice(i, 1);
					}
				} else {
					if (!menu[i].labels.includes("vegan")) {
						menu.splice(i, 1);
					}
				}
			}
		}
		return menu;
	}

	static multiMenuAdditives(multiMenu, thisAdditives) {
		if (thisAdditives.length > 0) {
			for (var k = multiMenu.length - 1; k >= 0; k--) {
				multiMenu[k].items = this.menuAdditives(multiMenu[k].items, thisAdditives);
				if (multiMenu[k].items.length < 1) multiMenu.splice(k, 1);
			}
		}
		return multiMenu;
	}

	static menuAdditives(menu, thisAdditives) {
		if (thisAdditives.length > 0) {
			for (var l = menu.length - 1; l >= 0; l--) {
				for (var i = menu[l].additives.length - 1; i >= 0; i--) {
					if (thisAdditives.includes(menu[l].additives[i])) {
						menu.splice(l, 1);
						break;
					}
				}
			}
		}
		return menu;
	}

	static filterByID(ids, thisMensas) {
		return thisMensas.filter( (m)=>(ids.indexOf(m._id) >= 0) );
	}

}