export default class Helpers {

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

}