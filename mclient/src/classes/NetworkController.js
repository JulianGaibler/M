import moment from "moment";
import { timeout, TimeoutError } from 'promise-timeout';

export default class NetworkController {

	/**
	 * Creates the NetworkController
	 *
	 * @return nothing
	 */
	constructor(i18n) {
		this.mensas_light = [];
		this.mensas_full = [];
		this.additives = [];
		this.storageC;
		this.i18nhook = i18n;

		this.config = {
			locationOptions: {
				enableHighAccuracy: false,
				maximumAge: 30000,
				timeout: 15000
			}
		}
	}

	getCurrentPosition(tryagain=true) {
		return new Promise((resolve, reject) => {
			this._getCurrentPosition().then(result => {
				resolve(result);
			})
			.catch(error => {
				let obj = {};
				obj.timeout = 5000;
				obj.multiline = true;
				obj.actionOnBottom = true;
				switch(error.code) {
					case 0:
						this.storageC.setLocation(0);
						obj.message = this.i18nhook.t('result.error_location_support'); break;
					case 1:
						this.storageC.setLocation(0);
						obj.message = this.i18nhook.t('result.error_location_denied'); break;
					case 2:
						obj.message = this.i18nhook.t('result.error_location_unavailable'); break;
					case 3:
						obj.message = this.i18nhook.t('result.error_location_timeout'); break;
					default:
						obj.message = this.i18nhook.t('result.error_location_other');
				}

				error.localizedMessage = obj.message;

				if (error.code === 1) {
					obj.actionText = this.i18nhook.t('frame.settings');
					obj.actionHandler = () => {bus.$emit('changeview', 'viewsettings')};
					reject(error);
				} else if (error.code > 1 && tryagain) {
					obj.actionText = this.i18nhook.t('action.tryagain');
					obj.actionHandler = () => {
						timeout(this.getCurrentPosition(false), 5500)
							.then((result) => resolve(result))
							.catch((err) => reject(error));
					}
				} else reject(error);
				bus.$emit('showSnackbar', obj);
			});
		});
	}

	/**
	 * [private]
	 * Gets Location from device
	 *
	 * @return Promise of location data with no error handling
	 */
	_getCurrentPosition() {
		if (navigator.geolocation) {
			return new Promise(
				(resolve, reject) => navigator.geolocation.getCurrentPosition(resolve, reject, this.config.locationOptions)
			)
		} else {
			return new Promise(
				(resolve, reject) => reject({
					code: 0
				})
			)
		}
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
				this.fetchAPI("/mensas?nolocation").then((result) => {
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
	 * Gets Mensas from longitude, latitude and radius in kilometers
	 *
	 * @param type is optional
	 * @return Promise of those Mensas in [[],[],[]]-Format
	 */
	getNearMensas(lng,lat,r,type) {
		return new Promise((resolve, reject) => {
				let obj = {lng,lat,r};
				if (type!==undefined) obj.type = type;
				let get = this.generateGET(obj);
				this.fetchAPI("/mensas/near?"+get).then((result) => {
					resolve(result);
				},
				(reason) => {
					reject(reason);
				});
		});
	}

	/**
	 * --
	 *
	 * @return --
	 */
	getMenuSections() {
		return new Promise((resolve, reject) => {
			this.fetchAPI("/sections").then((result) => {
				resolve(result);
			},
			(reason) => {
				reject(reason);
			});
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
				this.fetchAPI("/additives").then((result) => {
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
			let url = "/menu/"+mensa_id+"?lang="+this.storageC.settings.language;

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
				let url = "/mensas/";
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
			let url = "/user/create";
			this.fetchAPI(url,'PUT').then((result) => {
				resolve(result._id);
			},
			(reason) => {
				reject(reason);
			});
		});
	}

	updateUser(id) {
		return new Promise((resolve, reject) => {
			let url = "/user/update";
			this.fetchAPI(url, 'POST', id).then((result) => {
				
			},
			(reason) => {
				
			});
		});
	}

	setStorageC(reference) {
		this.storageC = reference;
	}

	fetchAPI(APIpath, type='GET', bodymsg=undefined) {
		return new Promise((resolve, reject) => {
			if (!APIpath) reject(false);

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
			request.send(bodymsg);

		});
	}

	/**
	 * --
	 *
	 * @return --
	 */
	generateGET(object) {
		let str = "";
		for (let key in object) {
			if (str != "") str += "&";
			str += key + "=" + encodeURIComponent(object[key]);
		}
		return str;
	}

}