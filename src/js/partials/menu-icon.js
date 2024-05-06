'use strict';

import {composePreviewContent} from './utils';

/*
 * This file handles setting the menu icon preview for a given post type.
 *
 * @todo Finish converting away from jQuery.
 */

(($) => {
	let _custom_media;
	let _orig_send_attachment;

	if (undefined !== wp.media) {
		_custom_media = true;
		_orig_send_attachment = wp.media.editor.send.attachment;
	}

	$('#cptui_choose_icon').on('click', function (e) {
		e.preventDefault();

		let button = $(this);
		let id = jQuery('#menu_icon').attr('id');
		_custom_media = true;
		wp.media.editor.send.attachment = function (props, attachment) {
			if (_custom_media) {
				$("#" + id).val(attachment.url).change();
			} else {
				return _orig_send_attachment.apply(this, [props, attachment]);
			}
		};

		wp.media.editor.open(button);
		return false;
	});

	// NOT DONE
	/*const menuIcon = document.querySelector('#menu_icon');
	if (menuIcon) {
		menuIcon.addEventListener('input', (e) => {
			let value = e.currentTarget.value.trim();
			console.log(value);
			let menuIconPreview = document.querySelector('#menu_icon_preview');
			console.log(menuIconPreview);
			if (menuIconPreview) {
				console.log(composePreviewContent(value));
				menuIconPreview.innerHTML = composePreviewContent(value);
			}
		});
	}*/
	$('#menu_icon').on('change', function () {
		var value = $(this).val();
		value = value.trim();
		$('#menu_icon_preview').html(composePreviewContent(value));
	});
})(jQuery);
