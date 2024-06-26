'use strict';

/*
 * This file handles the automatic population as well as the automatic clearing of the label
 * fields, based on the provided singular and plural label values.
 */

(() => {
	let nameField = document.querySelector('#name');
	let autoPopulate = document.querySelector('#auto-populate');

	const autoLabels = document.querySelector('#autolabels');
	if (autoLabels) {
		autoLabels.style.display = 'table-row';
	}

	if (autoPopulate) {
		['click', 'tap'].forEach((eventName, index) => {
			autoPopulate.addEventListener(eventName, (e) => {
				e.preventDefault();

				let slug = nameField.value;
				let plural = document.querySelector('#label').value;
				let singular = document.querySelector('#singular_label').value;
				let fields = document.querySelectorAll('.cptui-labels input[type="text"]');

				if ('' === slug) {
					return;
				}

				if ('' === plural) {
					plural = slug;
				}

				if ('' === singular) {
					singular = slug;
				}

				Array.from(fields).forEach(field => {
					let newval = field.getAttribute('data-label');
					let plurality = field.getAttribute('data-plurality');
					if (typeof newval !== 'undefined') {
						// "slug" is our placeholder from the labels.
						if ('plural' === plurality) {
							newval = newval.replace(/item/gi, plural);
						} else {
							// using an else statement because we do not
							// want to mutate the original string by default.
							newval = newval.replace(/item/gi, singular);
						}
						if (field.value === '') {
							field.value = newval;
						}
					}
				});
			})
		});
	}

	let autoClear = document.querySelector('#auto-clear');
	if (autoClear) {
		['click', 'tap'].forEach((eventName, index) => {
			autoClear.addEventListener(eventName, (e) => {
				e.preventDefault();

				const fields = document.querySelectorAll('.cptui-labels input[type="text"]');
				Array.from(fields).forEach(field => {
					field.value = '';
				});
			})
		});
	}
})();
