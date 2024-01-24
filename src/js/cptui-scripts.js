/**
 * Add collapseable boxes to our editor screens.
 */
postboxes.add_postbox_toggles(pagenow);

/**
 * The rest of our customizations.
 */
(function($) {

	let original_slug;
	let _custom_media;
	let _orig_send_attachment;

	const cptSelectSubmit = document.querySelector('#cptui_select_post_type_submit');
	if (cptSelectSubmit) {
		cptSelectSubmit.style.display = 'none';
	}
	const taxSelectSubmit = document.querySelector('#cptui_select_taxonomy_submit');
	if (taxSelectSubmit) {
		taxSelectSubmit.style.display = 'none';
	}

	if ('edit' === getParameterByName('action')) {
		// Store our original slug on page load for edit checking.
		original_slug = document.querySelector('#name').value;
	}

	// Automatically toggle the "page attributes" checkbox if
	// setting a hierarchical post type.
	const hierarchicalSetting = document.querySelector('#hierarchical');
	if ( hierarchicalSetting ) {
		hierarchicalSetting.addEventListener('change', (e) => {
			let pageAttributesCheck = document.querySelector('#page-attributes');
			if (e.currentTarget && e.currentTarget.value === '1') {
				pageAttributesCheck.checked = true;
			} else {
				pageAttributesCheck.checked = false;
			}
		});
	}

	// Switch to newly selected post type or taxonomy automatically.
	const postTypeDropdown = document.querySelector('#post_type');
	const taxonomyDropdown = document.querySelector('#taxonomy');

	if (postTypeDropdown) {
		postTypeDropdown.addEventListener('change', () => {
			const postTypeSelectPostType = document.querySelector('#cptui_select_post_type');
			if (postTypeSelectPostType) {
				postTypeSelectPostType.submit();
			}
		})
	}
	if (taxonomyDropdown) {
		taxonomyDropdown.addEventListener('change', () => {
			const taxonomySelectPostType = document.querySelector('#cptui_select_taxonomy');
			if (taxonomySelectPostType) {
				taxonomySelectPostType.submit();
			}
		})
	}

	// Confirm our deletions
	$('.cptui-delete-top, .cptui-delete-bottom').on('click',function(e) {
		e.preventDefault();
		var msg = '';
		if (typeof cptui_type_data !== 'undefined') {
			msg = cptui_type_data.confirm;
		} else if (typeof cptui_tax_data !== 'undefined') {
			msg = cptui_tax_data.confirm;
		}
		var submit_delete_warning = $('<div class="cptui-submit-delete-dialog">' + msg + '</div>').appendTo('#poststuff').dialog({
			'dialogClass'   : 'wp-dialog',
			'modal'         : true,
			'autoOpen'      : true,
			'buttons'       : {
				"OK": function() {
					var form = $(e.target).closest('form');
					$(e.target).off('click').click();
				},
				"Cancel": function() {
					$(this).dialog('close');
				}
			}
		});
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
		var value, original_value;
		value = original_value = $(this).val();
		if ( e.keyCode !== 9 && e.keyCode !== 37 && e.keyCode !== 38 && e.keyCode !== 39 && e.keyCode !== 40 ) {
			value = value.replace(/ /g, "_");
			value = value.toLowerCase();
			value = replaceDiacritics(value);
			value = transliterate(value);
			value = replaceSpecialCharacters(value);
			if ( value !== original_value ) {
				$(this).prop('value', value);
			}
		}

		//Displays a message if slug changes.
		if(typeof original_slug !== 'undefined') {
			var $slugchanged = $('#slugchanged');
			if(value != original_slug) {
				$slugchanged.removeClass('hidemessage');
			} else {
				$slugchanged.addClass('hidemessage');
			}
		}

		var $slugexists          = $('#slugexists');
		var $override_validation = $('#override_validation').is(":checked");
		if ( typeof cptui_type_data != 'undefined' ) {
			if (cptui_type_data.existing_post_types.hasOwnProperty(value) && value !== original_slug && $override_validation == false ) {
				$slugexists.removeClass('hidemessage');
			} else {
				$slugexists.addClass('hidemessage');
			}
		}
		if ( typeof cptui_tax_data != 'undefined' ) {
			if (cptui_tax_data.existing_taxonomies.hasOwnProperty(value) && value !== original_slug) {
				$slugexists.removeClass('hidemessage');
			} else {
				$slugexists.addClass('hidemessage');
			}
		}
	});

	// Replace diacritic characters with latin characters.
	function replaceDiacritics(s) {
		const diacritics = [
			/[\300-\306]/g, /[\340-\346]/g,  // A, a
			/[\310-\313]/g, /[\350-\353]/g,  // E, e
			/[\314-\317]/g, /[\354-\357]/g,  // I, i
			/[\322-\330]/g, /[\362-\370]/g,  // O, o
			/[\331-\334]/g, /[\371-\374]/g,  // U, u
			/[\321]/g, /[\361]/g, // N, n
			/[\307]/g, /[\347]/g  // C, c
		];

		let chars = ['A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u', 'N', 'n', 'C', 'c'];

		for (let i = 0; i < diacritics.length; i++) {
			s = s.replace(diacritics[i], chars[i]);
		}

		return s;
	}

	function replaceSpecialCharacters(s) {
		s = s.replace(/[^a-z0-9\s-]/gi, '_');
		return s;
	}

	function composePreviewContent(value) {

		const re = /(http|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])?/;
		const isURL = re.test(value);

		if (!value) {
			return '';
		} else if (0 === value.indexOf('dashicons-')) {
			const dashDiv = document.createElement('div');
			dashDiv.classList.add('dashicons-before');
			dashDiv.innerHTML = '<br/>';
			dashDiv.classList.add(htmlEncode(value));
			return dashDiv;
		} else if (isURL) {
			const imgsrc = encodeURI(value);
			const theimg = document.createElement('IMG');
			theimg.src = imgsrc;
			return theimg;
		}
	}

	function htmlEncode(str) {
		return String(str).replace(/[^-\w. ]/gi, function (c) {
			return '&#' + c.charCodeAt(0) + ';';
		});
	}

	const cyrillic = {
		"Ё": "YO", "Й": "I", "Ц": "TS", "У": "U", "К": "K", "Е": "E", "Н": "N", "Г": "G", "Ш": "SH", "Щ": "SCH", "З": "Z", "Х": "H", "Ъ": "'", "ё": "yo", "й": "i", "ц": "ts", "у": "u", "к": "k", "е": "e", "н": "n", "г": "g", "ш": "sh", "щ": "sch", "з": "z", "х": "h", "ъ": "'", "Ф": "F", "Ы": "I", "В": "V", "А": "a", "П": "P", "Р": "R", "О": "O", "Л": "L", "Д": "D", "Ж": "ZH", "Э": "E", "ф": "f", "ы": "i", "в": "v", "а": "a", "п": "p", "р": "r", "о": "o", "л": "l", "д": "d", "ж": "zh", "э": "e", "Я": "Ya", "Ч": "CH", "С": "S", "М": "M", "И": "I", "Т": "T", "Ь": "'", "Б": "B", "Ю": "YU", "я": "ya", "ч": "ch", "с": "s", "м": "m", "и": "i", "т": "t", "ь": "'", "б": "b", "ю": "yu"
	};

	function transliterate(word) {
		return word.split('').map(function (char) {
			return cyrillic[char] || char;
		}).join("");
	}

	if ( undefined !== wp.media ) {
		_custom_media = true;
		_orig_send_attachment = wp.media.editor.send.attachment;
	}

	function getParameterByName(name, url) {
		if (!url) url = window.location.href;
		name = name.replace(/[\[\]]/g, "\\$&");
		const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
			results = regex.exec(url);
		if (!results) return null;
		if (!results[2]) return '';
		return decodeURIComponent(results[2].replace(/\+/g, " "));
	}

	$('#cptui_choose_icon').on('click',function(e){
		e.preventDefault();

		var button = $(this);
		var id = jQuery('#menu_icon').attr('id');
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

	$('#menu_icon').on('change', function () {
		var value = $(this).val();
		value = value.trim();
		$('#menu_icon_preview').html(composePreviewContent(value));
	});

	$('.cptui-help').on('click',function(e){
		e.preventDefault();
	});

	$('.cptui-taxonomy-submit').on('click',function(e){
		if ( $('.cptui-table :checkbox:checked').length == 0 ) {
			e.preventDefault();
			var no_associated_type_warning = $('<div class="cptui-taxonomy-empty-types-dialog">' + cptui_tax_data.no_associated_type + '</div>').appendTo('#poststuff').dialog({
				'dialogClass'   : 'wp-dialog',
				'modal'         : true,
				'autoOpen'      : true,
				'buttons'       : {
					"OK": function() {
						$(this).dialog('close');
					}
				}
			});
		}
	});

	$('#auto-populate').on( 'click tap', function(e){
		e.preventDefault();

		var slug     = $('#name').val();
		var plural   = $('#label').val();
		var singular = $('#singular_label').val();
		var fields   = $('.cptui-labels input[type="text"]');

		if ( '' === slug ) {
			return;
		}
		if ( '' === plural ) {
			plural = slug;
		}
		if ( '' === singular ) {
			singular = slug;
		}

		$(fields).each( function( i, el ) {
			var newval = $( el ).data( 'label' );
			var plurality = $( el ).data( 'plurality' );
			if ( 'undefined' !== newval ) {
				// "slug" is our placeholder from the labels.
				if ( 'plural' === plurality ) {
					newval = newval.replace(/item/gi, plural);
				} else {
					newval = newval.replace(/item/gi, singular);
				}
				if ( $( el ).val() === '' ) {
					$(el).val(newval);
				}
			}
		} );
	});

	$('#auto-clear').on( 'click tap', function(e) {
		e.preventDefault();

		var fields = $('.cptui-labels input[type="text"]');

		$(fields).each( function( i, el ) {
			$(el).val('');
		});
	});

	/**
	 * "Back to top" button functionalioty
	 */
	var back_to_top_btn = $('.cptui-back-to-top');
	$(window).scroll(function() {
		if ($(window).scrollTop() > 300) {
			back_to_top_btn.addClass('show');
		} else {
			back_to_top_btn.removeClass('show');
		}
	});

	back_to_top_btn.on('click', function(e) {
		e.preventDefault();
		$('html, body').animate({scrollTop:0}, '300');
	});

	// Toggle Panels State
	var all_panels = [ "#cptui_panel_pt_basic_settings", "#cptui_panel_pt_additional_labels", "#cptui_panel_pt_advanced_settings", "#cptui_panel_tax_basic_settings", "#cptui_panel_tax_additional_labels", "#cptui_panel_tax_advanced_settings" ];
	$(all_panels).each(function (index, element) {
		var panel_id = $(element).attr('id');

		// check default state on page load
		if ( !localStorage.getItem(panel_id) || localStorage.getItem(panel_id) === null ) {
			$("#" + panel_id).removeClass('closed');
		}
		else{
			$("#" + panel_id).addClass('closed');
		}

		// change state on click/toggle
		$(element).find(".postbox-header").on('click', function (e) {
			if ( !localStorage.getItem(panel_id) ) {
				localStorage.setItem(panel_id, 1);
			}
			else{
				localStorage.removeItem(panel_id);
			}
		});
	});

})(jQuery);
