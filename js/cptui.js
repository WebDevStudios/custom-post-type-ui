(function($) {
	//confirm our deletions
	$( '#cpt_submit_delete' ).on( 'click', function() {
		if( confirm( confirmdata.confirm ) ) {
			return true;
		}
		return false;
	});

	$('#support .question').each(function() {
		var tis = $(this), state = false, answer = tis.next('div').slideUp();
		tis.click(function() {
			state = !state;
			answer.slideToggle(state);
			tis.toggleClass('active',state);
		});
	});

	var orig, highlight;
	$('#cptui_accordion h3').hover(function(){
			orig = $(this).css('color');
			highlight = $('.wp-ui-highlight').css('background-color');
			$(this).css({'color':highlight});
		}, function() {
			$(this).css({'color':orig });
		}
	);

	if ( 'cpt-ui_page_cptui_manage_post_types' === window.adminpage ) {
		$('#name').after(cptui_type_data.post_change_name);
	} else if ( 'cpt-ui_page_cptui_manage_taxonomies' === window.adminpage ) {
		$('#name').after(cptui_tax_data.tax_change_name);
	}
	$( '#name' ).on( 'keyup', function( e ) {
		//return early on this for now.
		if ( 'cpt-ui_page_cptui_manage_taxonomies' === window.adminpage ) {
			return;
		}
		var $input = $('.typetax-rename');

		if ( 0 === $(this).val().length ) {
			$input.addClass('cptui-hidden');
		}

		if ( -1 === $.inArray( $(this).val(), cptui_type_data.post_types ) || -1 === $.inArray( $(this).val(), cptui_tax_data.taxonomies ) ) {
			if ( $input.hasClass( 'cptui-hidden' ) ) {
				$input.removeClass('cptui-hidden');
			}
		} else {
			$input.addClass('cptui-hidden');
		}
	});

})(jQuery);
