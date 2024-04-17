'use strict';

(($) => {
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
})(jQuery);
