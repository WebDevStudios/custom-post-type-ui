'use strict';

/*
 * This file handles automatically toggling the "Page attributes" option in the "Supports" section
 * when a user chooses to have their post type be hierarchical.
 *
 * The purpose is to help ensure that the "parent" and "template" metabox option shows up by default,
 * but we do not force that to remain checked. The user can still toggle it off after the fact.
 */

(() => {
	// Automatically toggle the "page attributes" checkbox if
	// setting a hierarchical post type.
	const hierarchicalSetting = document.querySelector('#hierarchical');
	if (hierarchicalSetting) {
		hierarchicalSetting.addEventListener('change', (e) => {
			let pageAttributesCheck = document.querySelector('#page-attributes');
			if (e.currentTarget && e.currentTarget.value === '1') {
				pageAttributesCheck.checked = true;
			} else {
				pageAttributesCheck.checked = false;
			}
		});
	}
})();
