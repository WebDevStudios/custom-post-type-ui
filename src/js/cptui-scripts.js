/**
 * Add collapseable boxes to our editor screens.
 */

'use strict';

import {getParameterByName } from './partials/utils';

postboxes.add_postbox_toggles(pagenow);

/**
 * The rest of our customizations.
 */
(function($) {

	let original_slug;
	let _custom_media;
	let _orig_send_attachment;
	let nameField = document.querySelector('#name');

	const cptSelectSubmit = document.querySelector('#cptui_select_post_type_submit');
	if (cptSelectSubmit) {
		cptSelectSubmit.style.display = 'none';
	}
	const taxSelectSubmit = document.querySelector('#cptui_select_taxonomy_submit');
	if (taxSelectSubmit) {
		taxSelectSubmit.style.display = 'none';
	}

	if ('edit' === getParameterByName('action')) {
		if ( nameField ) {
			// Store our original slug on page load for edit checking.
			original_slug = nameField.value;
		}
	}



	// NOT DONE
	/*['.cptui-delete-top', '.cptui-delete-bottom'].forEach( (element,index) => {
		let theDialog = document.querySelector('#cptui-content-type-delete');
		let theelement = document.querySelector(element);
		theelement.addEventListener('click', async (e) => {
			e.preventDefault();
			const doPerformAction = await confirm();
			if ( doPerformAction ) {
				let thing = document.querySelector('#cpt_submit_delete');
				console.log(thing);
				thing.click();
				thing.submit();
				theDialog.close();
			} else {
				theDialog.close();
			}
		});
	});

	let closeBtnConfirm = document.querySelector('.cptui-confirm-deny-delete button');
	let closeBtnDeny = document.querySelector('#cptui-content-type-deny-delete');
	function confirm() {
		return new Promise((resolve, reject) => {
			document.querySelector('#cptui-content-type-delete').showModal();
			closeBtnConfirm.focus();

			closeBtnConfirm.addEventListener("click", () => {
				resolve(true);
				document.querySelector('#cptui-content-type-delete').close()
			});
			closeBtnDeny.addEventListener("click", () => {
				resolve(false);
				document.querySelector('#cptui-content-type-delete').close()
			});
		});
	}*/

	// Confirm our deletions
	$('.cptui-delete-top, .cptui-delete-bottom').on('click', function (e) {
		e.preventDefault();
		let msg = '';
		if (typeof cptui_type_data !== 'undefined') {
			msg = cptui_type_data.confirm;
		} else if (typeof cptui_tax_data !== 'undefined') {
			msg = cptui_tax_data.confirm;
		}
		let submit_delete_warning = $('<div class="cptui-submit-delete-dialog">' + msg + '</div>').appendTo('#poststuff').dialog({
			'dialogClass': 'wp-dialog',
			'modal'      : true,
			'autoOpen'   : true,
			'buttons'    : {
				"OK"    : function () {
					$(this).dialog('close');
					$(e.target).off('click').click();
				},
				"Cancel": function () {
					$(this).dialog('close');
				}
			}
		});
	});





	if ( undefined !== wp.media ) {
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
