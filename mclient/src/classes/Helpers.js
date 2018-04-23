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
			menu = bucket.concat(menu);
		}
	}

	static menuDiet(menu, diet) {
		if (diet > 0) {
			for (var k = menu.length - 1; k >= 0; k--) {
				for (var i = menu[k].items.length - 1; i >= 0; i--) {
					if (diet==1) {
						if (!menu[k].items[i].labels.includes("vegetarian") &&
							!menu[k].items[i].labels.includes("vegan")) {
							menu[k].items.splice(i, 1);
						}
					} else {
						if (!menu[k].items[i].labels.includes("vegan")) {
							menu[k].items.splice(i, 1);
						}
					}
				}
				if (menu.length < 1) menu.splice(k, 1);
			}
		}
	}

	static menuAdditives(menu, thisAdditives) {
		if (thisAdditives.length > 0) {
			for (var k = menu.length - 1; k >= 0; k--) {
				for (var l = menu[k].items.length - 1; l >= 0; l--) {
					for (var i = menu[k].items[l].additives.length - 1; i >= 0; i--) {
						if (thisAdditives.includes(menu[k].items[l].additives[i])) {
							menu[k].items.splice(l, 1);
							break;
						}
					}
				}
				if (menu[k].items.length < 1) menu.splice(k, 1);
			}
		}
	}

	static menuSort(menu, sorting) {
		
	}

	static menuSort(menu, sorting) {
		
	}

}