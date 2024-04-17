'use strict';

(() => {
	// Handles checking if a post type has been chosen or not when adding/saving a taxonomy.
	// Post type associations are a required attribute.
	const taxSubmit = document.querySelectorAll('.cptui-taxonomy-submit');
	const taxSubmitSelectCPTDialog = document.querySelector('#cptui-select-post-type-confirm');
	Array.from(taxSubmit).forEach((element, i) => {
		element.addEventListener('click', (e) => {
			// putting inside event listener to check every time clicked. Defining outside lost re-checking.
			let taxCPTChecked = document.querySelectorAll('#cptui_panel_tax_basic_settings input[type="checkbox"]:checked');
			if (taxCPTChecked.length === 0) {
				e.preventDefault();
				taxSubmitSelectCPTDialog.showModal();
			}
		});
	});
	let taxSubmitSelectCPTConfirmCloseBtn = document.querySelector('#cptui-select-post-type-confirm-close');
	if (taxSubmitSelectCPTConfirmCloseBtn) {
		taxSubmitSelectCPTConfirmCloseBtn.addEventListener('click', (e) => {
			e.preventDefault();
			taxSubmitSelectCPTDialog.close();
		});
	}
})();
