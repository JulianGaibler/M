export default class LocalStorageController {

	/**
	 * Creates the StorageController
	 *
	 * @return nothing
	 */
	constructor(i18n) {
		const res = this.storageAvailable('localStorage');
		this.settings = {};
		this.i18nhook = i18n;
		if (res) {
			if(!localStorage.getItem('settings')) {
				this.initSettings();
			} else {
				this.settings = JSON.parse(localStorage.getItem('settings'));
				this.i18nhook.locale = this.settings.language;
			}
		}
		else {
			throw res;
		}
	}


	/**
	 * Initializes a new and empty Settings-Object
	 *
	 * @return nothing
	 */
	initSettings() {
		this.settings = {
			"version": 1,
			"profileID":"gfdagf",
			"language": "de",
			"pricetype": 0,
			"primarytype": 0,
			"mensas": [[],[],[]],
			"startpage": 0,
			"highlights": [],
			"sorting": []
		};
		this.updateStorage();
	}


	/**
	 * foo
	 *
	 * @return foo
	 */
	hasSettings() {
		for (var i = this.settings.mensas.length - 1; i >= 0; i--) {
			if (this.settings.mensas[i].length > 0) return true;
		}
		return false;
	}

	/**
	 * Adds, changes oder deletes user-Mensas
	 * 
	 *
	 * @param _id - ID of Mensa
	 * @param name - Name of Mensa
	 * @param remove - if element is to be removed (default: false)
	 * 
	 * @return foo
	 */
	setMensas(_id, nameA, nameB, type, remove=false) {
		for (var m of this.settings.mensas[type]) {
			if (m._id === _id) {
				if (remove) {
					// removes the element
					this.arrayRemove(this.settings.mensas[type], m);
				} else {
					//updates the element
					m = {_id, nameA, nameB};
				}
				this.updateStorage();
				return;
			}
		}
		this.settings.mensas[type].push({_id, nameA, nameB});
		this.updateStorage();
	}

	/**
	 * Gets Primary Mensa
	 * 
	 * @return Mensa-Object or undefined
	 */
	getPrimaryMensa() {
			let nr = this.settings.primarytype;
			if (this.settings.mensas[nr].length < 1) return undefined;
			return this.settings.mensas[nr][0];
	}

	/**
	 * Gets Primary Mensa
	 * 
	 * @param _id - ID of Mensa
	 * 
	 * @return Mensa-Object if it worked or false if failed
	 */
	setPrimaryMensa(_id, type) {
			this.settings.primarytype = type;
			for (var i = this.settings.mensas[type].length - 1; i >= 0; i--) {
				console.log(this.settings.mensas[type][i]._id+" === "+_id);
				if (this.settings.mensas[type][i]._id===_id) {
					let saved = this.settings.mensas[type][i];
					this.settings.mensas[type].splice(i, 1);
					this.settings.mensas[type].unshift(saved);
					this.updateStorage();
					return saved;
				}
			}
		return false;
	}


	/**
	 * Sets the price type
	 * 
	 * @param nr - Number of price type
	 * 
	 */
	setPricetype(nr) {
		this.settings.pricetype = nr;
		this.updateStorage();
	}


	/**
	 * updates Storage
	 */
	updateStorage() {
		localStorage.setItem('settings', JSON.stringify(this.settings));
	}


	/**
	 * Removes element from array
	 */
	arrayRemove(array, element) {
		const index = array.indexOf(element);
		
		if (index !== -1) {
			array.splice(index, 1);
		}
	}


	/**
	 * Tests if storage-type is available
	 *
	 * @return true on success, error on failure
	 */
	storageAvailable(type) {
			try {
			var storage = window[type],
				x = '__storage_test__';
			storage.setItem(x, x);
			storage.removeItem(x);
			return true;
		}
		catch(e) {
			return e instanceof DOMException && (
				// everything except Firefox
				e.code === 22 ||
				// Firefox
				e.code === 1014 ||
				// test name field too, because code might not be present
				// everything except Firefox
				e.name === 'QuotaExceededError' ||
				// Firefox
				e.name === 'NS_ERROR_DOM_QUOTA_REACHED') &&
				// acknowledge QuotaExceededError only if there's something already stored
				storage.length !== 0;
		}
	}

}