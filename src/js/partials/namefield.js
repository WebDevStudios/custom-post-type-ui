'use strict';
import { getParameterByName, replaceDiacritics, transliterate, replaceSpecialCharacters } from './utils'

/*
 * This file handles all of the normalization of the name/slug field for a post type
 * or taxonomy being registered.
 *
 * That way we are only allowing latin characters and dashes/underscores.
 *
 * It also shows a hidden alert if the slug has been changed in some way when editing an existing
 * content type.
 *
 * Lastly it will also show a warning if the attempted slug has already been registered elsewhere,
 * to help avoid clashes. The only exception is if the checkbox is checked indicating that the user
 * is trying to convert TO using CPTUI, and the conflicting slug elsewhere will be removed soon.
 */

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
		// Use the `input` event so we catch paste, autofill, drag-drop, and
		// any programmatic value change — not just keystrokes. The previous
		// `keyup` listener missed paste/autofill, which is how invalid
		// uppercase slugs ("People") could slip past client-side normalization.
		nameField.addEventListener('input', (e) => {
			let value, original_value;

			value = original_value = e.currentTarget.value;
			value = value.replace(/ /g, "_");
			value = value.toLowerCase();
			value = replaceDiacritics(value);
			value = transliterate(value);
			value = replaceSpecialCharacters(value);
			if (value !== original_value) {
				e.currentTarget.value = value;
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
			let override_validation = (override) ? override.checked : false;
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
