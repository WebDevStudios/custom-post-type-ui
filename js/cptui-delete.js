(function($) {

	// Confirm our deletions from CPTUI Listings
	$('.cptui-listings-delete-bottom').on('click',function(e) {
		e.preventDefault();
		var msg  = '';
		var href = $(this).attr('href');
		if (typeof cptui_type_data !== 'undefined') {
			msg = cptui_type_data.confirm;
		}
		var submit_delete_warning = $('<div class="cptui-submit-delete-dialog">' + msg + '</div>').appendTo('.post-type-listing').dialog({
			'dialogClass'   : 'wp-dialog',
			'modal'         : true,
			'autoOpen'      : true,
			'buttons'       : {
				"OK": function() {
					window.location.href = href;
				},
				"Cancel": function() {
					$(this).dialog('close');
				}
			}
		});
	});


	// Confirm our deletions from CPTUI Listings
	$('.cptui-taxonomy-delete-bottom').on('click',function(e) {
		e.preventDefault();
		var msg  = '';
		var href = $(this).attr('href');
		if (typeof cptui_type_data !== 'undefined') {
			msg = cptui_type_data.confirm_taxonomy;
		}
		var submit_delete_warning = $('<div class="cptui-taxonomy-empty-types-dialog">' + msg + '</div>').appendTo('.taxonomy-listing').dialog({
			'dialogClass'   : 'wp-dialog',
			'modal'         : true,
			'autoOpen'      : true,
			'buttons'       : {
				"OK": function() {
					window.location.href = href;
				},
				"Cancel": function() {
					$(this).dialog('close');
				}
			}
		});
	});

})(jQuery);
