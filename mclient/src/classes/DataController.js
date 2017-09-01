//import taffy from 'taffy'

export default class DataController {

	/**
	 * Creates the DataController
	 *
	 * @return nothing
	 */
	constructor() {
		//this.menus = taffy();
		this.mensas = [];
	}

	/**
	 * Gets Mensas from local array or fetches them from the server
	 *
	 * @param callback - The callback that handles the response.
	 */
	getMensas(lang) {
		return new Promise((resolve, reject) => {
			if (this.mensas.length > 0) {
				resolve(this.mensas);
				return true;
			}

			$.ajax({
			  method: "GET",
			  url: "http://localhost:8000/api/"+lang+"/mensas",
			}).done(function( msg ) {
				this.mensas = msg;
				resolve(this.mensas);
	  		}).fail(function( jqXHR, textStatus, errorThrown ) {
	  			reject(errorThrown);
	  		});
  		})
	}

}