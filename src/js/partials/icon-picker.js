'use strict';
/*
 * This file handles the icon picker instantiation
 */

(() => {
	const icons = cptuiIconPicker.iconsJSON;
	const iconPicker = new IconPicker('#icon-picker', {
		theme     : 'default',
		iconSource: [{
			key   : 'dashicons',
			prefix: 'dashicons-',
			url   : icons
		}],
		closeOnSelect: true,
	});
})();
