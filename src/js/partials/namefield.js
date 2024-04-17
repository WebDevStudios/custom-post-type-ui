'use strict';
import { getParameterByName, replaceDiacritics, transliterate, replaceSpecialCharacters } from './utils'

(() => {
	let nameField = document.querySelector('#name');
	let original_slug;

	if ('edit' === getParameterByName('action')) {
		if (nameField) {
			// Store our original slug on page load for edit checking.
			original_slug = nameField.value;
		}
	}

	if (nameField) {
		// Switch spaces for underscores on our slug fields.
		nameField.addEventListener('keyup', (e) => {
			let value, original_value;

			value = original_value = e.currentTarget.value;
			let keys = ['Tab', 'ArrowLeft', 'ArrowUp', 'ArrowRight', 'ArrowDown'];
			if (!keys.includes(e.code)) {
				value = value.replace(/ /g, "_");
				value = value.toLowerCase();
				value = replaceDiacritics(value);
				value = transliterate(value);
				value = replaceSpecialCharacters(value);
				if (value !== original_value) {
					e.currentTarget.value = value;
				}
			}

			//Displays a message if slug changes.
			if (typeof original_slug !== 'undefined') {
				let slugchanged = document.querySelector('#slugchanged');
				if (value !== original_slug) {
					slugchanged.classList.remove('hidemessage');
				} else {
					slugchanged.classList.add('hidemessage');
				}
			}

			let slugexists = document.querySelector('#slugexists');
			let override = document.querySelector('#override_validation');
			let override_validation = (override) ? override.check : false;
			if (typeof cptui_type_data != 'undefined') {
				if (cptui_type_data.existing_post_types.hasOwnProperty(value) && value !== original_slug && override_validation === false) {
					slugexists.classList.remove('hidemessage');
				} else {
					slugexists.classList.add('hidemessage');
				}
			}
			if (typeof cptui_tax_data != 'undefined') {
				if (cptui_tax_data.existing_taxonomies.hasOwnProperty(value) && value !== original_slug) {
					slugexists.classList.remove('hidemessage');
				} else {
					slugexists.classList.add('hidemessage');
				}
			}
		});
	}
})();
