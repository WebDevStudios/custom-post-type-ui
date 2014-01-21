(function($) {
	//Create our accordions
	$( "#cptui_accordion" ).accordion({ collapsible: true, heightStyle: 'fill', active: 2 });

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

})(jQuery);
