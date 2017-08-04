export default class LocalStorageController {

	/**
	 * Creates the StorageController
	 *
	 * @return nothing
	 */
	constructor() {
		const res = this.storageAvailable('localStorage');
		this.settings = {};
		if (res) {
			if(!localStorage.getItem('settings')) {
				this.initSettings();
			} else this.settings = JSON.parse(localStorage.getItem('settings'));
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
			"mensas": [],
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
		if (this.settings.mensas.length < 1) return false;
		else return true;
	}

	/**
	 * Adds, changes oder deletes user-Mensas
	 * 
	 * If a new primary is set, the old one is NOT being replaced
	 *
	 * @param id - ID of Mensa
	 * @param name - Name of Mensa
	 * @param primary - if element is primary mensa (default: false)
	 * @param remove - if element is to be removed (default: false)
	 * 
	 * @return foo
	 */
	setMensas(id, name, primary=false, remove=false) {
		for (var m of this.settings.mensas) {
			if (m.id === id) {
				if (remove) {
					// removes the element
					this.arrayRemove(this.settings.mensas, m);
				} else {
					//updates the element
					m.name = name;
					m.primary = primary;
				}
				this.updateStorage();
				return;
			}
		}
		this.settings.mensas.push({id,name,primary});
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