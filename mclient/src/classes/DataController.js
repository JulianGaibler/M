//import taffy from 'taffy'

export default class DataController {

	/**
	 * Creates the DataController
	 *
	 * @return nothing
	 */
	constructor() {
		console.log("CONSTRUCTED");
		this.mensas_light = [];
		this.mensas_full = [];
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


	fetchAPI(APIpath) {
		return new Promise((resolve, reject) => {
			let newurl = "//jwels:8000/"+APIpath;

			let that = this;
			$.ajax({
			  method: "GET",
			  url: newurl,
			}).done(function( msg ) {
				resolve(msg);
	  		}).fail(function( jqXHR, textStatus, errorThrown ) {
	  			reject(false);
	  		});
  		})
	}

}