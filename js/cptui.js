(function($) {

	// Switch to newly selected post type or taxonomy automatically.
	$('#post_type').on('change',function(){
		$('#cptui_select_post_type').submit();
	});

	$('#taxonomy').on('change',function(){
		$( '#cptui_select_taxonomy' ).submit();
	});

	// Confirm our deletions
	$('#cpt_submit_delete').on('click',function() {
		if( confirm( cptui_type_data.confirm ) ) {
			return true;
		}
		return false;
	});

	// Toggles help/support accordions.
	$('#support .question').each(function() {
		var tis = $(this), state = false, answer = tis.next('div').slideUp();
		tis.on('click keydown',function(e) {
			// Helps with accessibility and keyboard navigation.
			if(e.type==='keydown' && e.keyCode!==32 && e.keyCode!==13) {
				return;
			}
			e.preventDefault();
			state = !state;
			answer.slideToggle(state);
			tis.toggleClass('active',state);
			tis.attr('aria-expanded', state.toString() );
			tis.focus();
		});
	});

	// Switch spaces for underscores on our slug fields.
	$('#name').on('keyup',function(e){
		var value = $(this).val();
		value = value.replace(/ /g, "_");
		value = value.toLowerCase();
		value = replaceDiacritics(value);
		$(this).attr('value',value);
	});

	// Replace diacritic characters with latin characters.
	function replaceDiacritics(s) {
		var diacritics = [
			/[\300-\306]/g, /[\340-\346]/g,  // A, a
			/[\310-\313]/g, /[\350-\353]/g,  // E, e
			/[\314-\317]/g, /[\354-\357]/g,  // I, i
			/[\322-\330]/g, /[\362-\370]/g,  // O, o
			/[\331-\334]/g, /[\371-\374]/g,  // U, u
			/[\321]/g, /[\361]/g, // N, n
			/[\307]/g, /[\347]/g, // C, c
		];

		var chars = ['A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u', 'N', 'n', 'C', 'c'];

		for (var i = 0; i < diacritics.length; i++) {
			s = s.replace(diacritics[i], chars[i]);
		}

		return s;
	}

	var _custom_media = true,
		_orig_send_attachment = wp.media.editor.send.attachment;

	$('#cptui_choose_icon').on('click',function(e){
		e.preventDefault();

		var button = $(this);
		var id = jQuery('#menu_icon').attr('id');
		_custom_media = true;
		wp.media.editor.send.attachment = function (props, attachment) {
			if (_custom_media) {
				$("#" + id).val(attachment.url);
			} else {
				return _orig_send_attachment.apply(this, [props, attachment]);
			};
		}

		wp.media.editor.open(button);
		return false;
	});

	$('#togglelabels').on('click',function(e){
		e.preventDefault();
		$('#labels_expand').toggleClass('toggledclosed');
	});
	$('#togglesettings').on('click',function(e) {
		e.preventDefault();
		$('#settings_expand').toggleClass('toggledclosed');
	});
	$('#labels_expand,#settings_expand').on('focus',function(e) {
		if ( $(this).hasClass('toggledclosed') ) {
			$(this).toggleClass('toggledclosed');
		}
	});
	$('#labels_expand legend,#settings_expand legend').on('click',function(e){
		$(this).parent().toggleClass('toggledclosed');
	});
	$('.cptui-help').on('click',function(e){
		e.preventDefault();
	});
})(jQuery);
