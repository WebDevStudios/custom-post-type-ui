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

	//Used in our support tab.
	$("#qa .answer").each(function(){
		if(!$(this).hasClass( 'active' )) {
			$(this).hide();
		}
	});
	$("#questions li").on( 'click', function(e){
		if( $(this).hasClass( 'active' ) ) {
			e.preventDefault();
		}
		var choice = $(this).attr('class');
		$('#qa .active').fadeOut().removeClass('active');
		$('#qa .'+choice).delay(500).fadeIn().addClass('active');
		$('#questions .active').removeClass('active');
		$(this).addClass('active');
	});

	$('#questions .question').each(function() {
		var tis = $(this), state = false, answer = tis.next('div').slideUp();
		tis.click(function() {
			state = !state;
			answer.slideToggle(state);
			tis.toggleClass('active',state);
		});
	});

})(jQuery);
