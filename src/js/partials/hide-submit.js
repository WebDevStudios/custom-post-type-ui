'use strict';

/*
 * This file visually removes the submit button to change content type being edited.
 *
 * If by chance javascript is disabled or somehow breaking, the button would show by default,
 * preventing issues with switching content types.
 */

(() => {
	const cptSelectSubmit = document.querySelector('#cptui_select_post_type_submit');
	if (cptSelectSubmit) {
		cptSelectSubmit.style.display = 'none';
	}
	const taxSelectSubmit = document.querySelector('#cptui_select_taxonomy_submit');
	if (taxSelectSubmit) {
		taxSelectSubmit.style.display = 'none';
	}
})();
