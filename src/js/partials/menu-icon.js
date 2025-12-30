'use strict';

/*
 * This file handles setting the menu icon preview for a given post type.
 */

(() => {
	let _custom_media;
	let _orig_send_attachment;

	if (undefined !== wp.media) {
		_custom_media = true;
		_orig_send_attachment = wp.media.editor.send.attachment;
	}

	// Trigger the modal and load our icons.
	const icons = cptuiIconPicker.iconsJSON;
	const iconPicker = new IconPicker('#cptui_choose_dashicon', {
		theme        : 'default',
		iconSource   : [{
			key   : 'dashicons',
			prefix: 'dashicons-',
			url   : icons
		}],
		closeOnSelect: true,
	});

	const menuIconField = document.querySelector('#menu_icon');
	const menuIconPreview = document.querySelector('#menu_icon_preview');
	const regIcon = document.querySelector('#cptui_choose_icon');
	const dashIcon = document.querySelector('#cptui_choose_dashicon');
	const origText = dashIcon.value;
	iconPicker.on('select', (icon) => {
		menuIconField.value = icon.value;
		menuIconPreview.innerHTML = '';

		let div = document.createElement('div');
		div.classList.add('dashicons', icon.value);
		menuIconPreview.insertAdjacentElement('afterbegin', div);
	});
	iconPicker.on('hide', () => {
		dashIcon.value = origText;
	})

	if (regIcon) {
		regIcon.addEventListener('click', (e) => {
			e.preventDefault();

			let button = e.currentTarget;
			_custom_media = true;
			wp.media.editor.send.attachment = function (props, attachment) {
				if (_custom_media) {
					menuIconField.value = attachment.url;
					menuIconPreview.innerHTML = '';
					let img = document.createElement('img');
					img.src = attachment.url;
					menuIconPreview.insertAdjacentElement('afterbegin', img);
				} else {
					return _orig_send_attachment.apply(this, [props, attachment]);
				}
			};

			wp.media.editor.open(button);
			return false;
		});
	}
})();
