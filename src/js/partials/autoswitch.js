'use strict';

/*
 * This file handles automatically switching to a chosen content type when selecting from the
 * dropdown listing.
 */

(() => {
	// Switch to newly selected post type or taxonomy automatically.
	const postTypeDropdown = document.querySelector('#post_type');
	const taxonomyDropdown = document.querySelector('#taxonomy');

	if (postTypeDropdown) {
		postTypeDropdown.addEventListener('change', () => {
			const postTypeSelectPostType = document.querySelector('#cptui_select_post_type');
			if (postTypeSelectPostType) {
				postTypeSelectPostType.submit();
			}
		})
	}
	if (taxonomyDropdown) {
		taxonomyDropdown.addEventListener('change', () => {
			const taxonomySelectPostType = document.querySelector('#cptui_select_taxonomy');
			if (taxonomySelectPostType) {
				taxonomySelectPostType.submit();
			}
		})
	}
})();
