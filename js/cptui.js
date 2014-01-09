(function($) {
	//Create our accordions
	$( "#cptui_accordion" ).accordion({ collapsible: true, heightStyle: 'fill', active: 2 });

	//confirm our deletions
	$( '#cpt_submit_delete' ).on( 'click', function() {
		if( confirm( 'Are you sure you want to delete this?' ) ) {
			return true;
		}
		return false;
	});
})(jQuery);
