import moment from "moment";

export default class DataController {

	/**
	 * Creates the DataController
	 *
	 * @return nothing
	 */
	constructor(i18n) {
		this.mensas_light = [];
		this.mensas_full = [];
		this.additives = [];
		this.storageC;
		this.i18nhook = i18n;
	}

	/**
	 * Gets Mensas from local array or fetches them from the server
	 *
	 * @return Promise of all Mensas in [[],[],[]]-Format
	 */
	getLightMensas() {
		return new Promise((resolve, reject) => {
			if (this.mensas_light.length > 0) resolve(this.mensas_light);
			else {
				this.fetchAPI("api/mensas?nolocation").then((result) => {
					this.mensas_light = result;
					resolve(result);
				},
				(reason) => {
					reject(reason);
				});
			}
		});
	}

	/**
	 * --
	 *
	 * @return --
	 */
	getAdditives() {
		return new Promise((resolve, reject) => {
			if (this.additives.length > 0) resolve(this.additives);
			else {
				this.fetchAPI("api/additives").then((result) => {
					this.additives = result;
					resolve(result);
				},
				(reason) => {
					reject(reason);
				});
			}
		});
	}

	/**
	 * --
	 *
	 * @return --
	 */
	getMenu(mensa_id, mmtDate) {
		return new Promise((resolve, reject) => {
			let url = "api/menu/"+mensa_id+"?lang="+this.storageC.settings.language;

			url += "&date="+mmtDate.format("YYYY-MM-DD");
			this.fetchAPI(url).then((result) => {
				resolve(result);
			},
			(reason) => {
				reject(reason);
			});
		});
	}

	/**
	 * Gets Mensas from local array or fetches them from the server
	 *
	 * @param _ids - Array of _id's
	 *
	 * @return Promise of all requested Mensas in [[],[],[]]-Format
	 */
	getSingleMensa(_ids) {
		return new Promise((resolve, reject) => {
			let _ids_clone = _ids.slice(0);
			for (let j = _ids.length - 1; j >= 0; j--) {
				for (let i = this.mensas_full.length - 1; i >= 0; i--) {
					if (_ids[j]._id === this.mensas_full[i]._id) {
						_ids_clone.splice(j, 1);
						break;
					}
				}
			}
			if (_ids_clone.length !== 0) {
				let url = "api/mensas/";
				for (var i = _ids_clone.length - 1; i >= 0; i--) {
					url += _ids_clone[i]._id+";";
				}
				this.fetchAPI(url).then((result) => {
						for (var j = result.length - 1; j >= 0; j--) {
							this.mensas_full.push(result[j]);
						}
					let mensas = this.fillLocalMensas(_ids);
					resolve(mensas);
				},
				(reason) => {
					reject(reason);
				});
			} else resolve(this.fillLocalMensas(_ids));
		});
	}


	fillLocalMensas(_ids) {
		let bucket = [];
		for (var j = 0; j < _ids.length; j++) {
			for (var i = this.mensas_full.length - 1; i >= 0; i--) {
				if (this.mensas_full[i]._id === _ids[j]._id) {
					bucket.push(this.mensas_full[i]);
					break;
				}
			}
		}
		return bucket;
	}

	newUserID() {
		return new Promise((resolve, reject) => {
			let url = "api/user/create";
			this.fetchAPI(url,'PUT').then((result) => {
				resolve(result._id);
			},
			(reason) => {
				reject(reason);
			});
		});
	}

	setStorageC(reference) {
		this.storageC = reference;
	}

	fetchAPI(APIpath, type='GET') {
		return new Promise((resolve, reject) => {
			let newurl = API_URL+APIpath;

			let request = new XMLHttpRequest();
			request.open(type, newurl, true);

			request.onload = function() {
			  if (request.status >= 200 && request.status < 400) {
			    resolve(JSON.parse(request.responseText));
			  } else {
			    reject(false);
			  }
			};
			request.onerror = function() {
				reject(false);
			};
			request.send();

  		});
	}

}