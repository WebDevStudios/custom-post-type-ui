'use strict';

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
