'use strict';

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
