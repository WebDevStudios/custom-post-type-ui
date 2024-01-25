/**
 * Add collapseable boxes to our editor screens.
 */

'use strict';

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
		// Store our original slug on page load for edit checking.
		original_slug = nameField.value;
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
	// LEAVE AS JQUERY FOR THE MOMENT. "OK" CONFERMIATION NOT WORKING WITH CONVERTED VERSION.
	$('.cptui-delete-top, .cptui-delete-bottom').on('click', function (e) {
		e.preventDefault();
		let msg = '';
		if (typeof cptui_type_data !== 'undefined') {
			msg = cptui_type_data.confirm;
		} else if (typeof cptui_tax_data !== 'undefined') {
			msg = cptui_tax_data.confirm;
		}
		var submit_delete_warning = $('<div class="cptui-submit-delete-dialog">' + msg + '</div>').appendTo('#poststuff').dialog({
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

	// Toggles help/support accordions.
	const supportQuestions = document.querySelectorAll('#support .question');
	Array.from(supportQuestions).forEach(function (question, index) {
		let next = function (elem, selector) {
			let nextElem = elem.nextElementSibling;

			if (!selector) {
				return nextElem;
			}

			if (nextElem && nextElem.matches(selector)) {
				return nextElem;
			}

			return null;
		};

		let state = false;
		let answer = next(question, 'div');
		answer.style.display = 'none';

		['click', 'keydown'].forEach((theEvent) => {
			question.addEventListener(theEvent, (e) => {
				// Helps with accessibility and keyboard navigation.
				if (e.type === 'keydown' && e.keyCode !== 32 && e.keyCode !== 13) {
					return;
				}
				e.preventDefault();
				state = !state;
				answer.style.display = state ? 'block' : 'none';
				e.currentTarget.classList.toggle('active')
				e.currentTarget.setAttribute('aria-expanded', state.toString());
				e.currentTarget.focus();
			});
		});
	});

	// Switch spaces for underscores on our slug fields.
	nameField.addEventListener('keyup', (e) => {
		let value, original_value;

		value = original_value = e.currentTarget.value;
		let keys = ['Tab', 'ArrowLeft', 'ArrowUp', 'ArrowRight', 'ArrowDown'];
		if (!keys.includes(e.code)) {
			value = value.replace(/ /g, "_");
			value = value.toLowerCase();
			value = replaceDiacritics(value);
			value = transliterate(value);
			value = replaceSpecialCharacters(value);
			if (value !== original_value) {
				e.currentTarget.value = value;
			}
		}

		//Displays a message if slug changes.
		if (typeof original_slug !== 'undefined') {
			let slugchanged = document.querySelector('#slugchanged');
			if (value !== original_slug) {
				slugchanged.classList.remove('hidemessage');
			} else {
				slugchanged.classList.add('hidemessage');
			}
		}

		let slugexists = document.querySelector('#slugexists');
		let override = document.querySelector('#override_validation');
		let override_validation = (override) ? override.check : false;
		if (typeof cptui_type_data != 'undefined') {
			if (cptui_type_data.existing_post_types.hasOwnProperty(value) && value !== original_slug && override_validation === false) {
				slugexists.classList.remove('hidemessage');
			} else {
				slugexists.classList.add('hidemessage');
			}
		}
		if (typeof cptui_tax_data != 'undefined') {
			if (cptui_tax_data.existing_taxonomies.hasOwnProperty(value) && value !== original_slug) {
				slugexists.classList.remove('hidemessage');
			} else {
				slugexists.classList.add('hidemessage');
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

	// Handles checking if a post type has been chosen or not when adding/saving a taxonomy.
	// Post type associations are a required attribute.
	const taxSubmit = document.querySelectorAll('.cptui-taxonomy-submit');
	Array.from(taxSubmit).forEach( (element,i) => {
		element.addEventListener('click', (e) => {
			// putting inside event listener to check every time clicked. Defining outside lost re-checking.
			let taxCPTChecked = document.querySelectorAll('#cptui_panel_tax_basic_settings input[type="checkbox"]:checked');
			if ( taxCPTChecked.length === 0 ) {
				e.preventDefault();

				let postStuff = document.querySelector('#poststuff');
				let no_cpt_chosen_warning = document.createElement('div');
				no_cpt_chosen_warning.classList.add('cptui-taxonomy-empty-types-dialog');
				no_cpt_chosen_warning.innerHTML = cptui_tax_data.no_associated_type;
				postStuff.append( no_cpt_chosen_warning );

				$(no_cpt_chosen_warning).dialog({
					'dialogClass': 'wp-dialog',
					'modal'      : true,
					'autoOpen'   : true,
					'buttons'    : {
						"OK": function () {
							$(this).dialog('close');
						}
					}
				});
			}
		});
	} );

	let autoPopulate = document.querySelector('#auto-populate');
	if (autoPopulate) {
		['click', 'tap'].forEach((eventName, index) => {
			autoPopulate.addEventListener(eventName, (e) => {
				e.preventDefault();

				let slug = nameField.value;
				let plural = document.querySelector('#label').value;
				let singular = document.querySelector('#singular_label').value;
				let fields = document.querySelectorAll('.cptui-labels input[type="text"]');

				if ('' === slug) {
					return;
				}

				if ('' === plural) {
					plural = slug;
				}

				if ('' === singular) {
					singular = slug;
				}

				Array.from(fields).forEach(field => {
					let newval = field.getAttribute('data-label');
					let plurality = field.getAttribute('data-plurality');
					if (typeof newval !== 'undefined') {
						// "slug" is our placeholder from the labels.
						if ('plural' === plurality) {
							newval = newval.replace(/item/gi, plural);
						} else {
							// using an else statement because we do not
							// want to mutate the original string by default.
							newval = newval.replace(/item/gi, singular);
						}
						if (field.value === '') {
							field.value = newval;
						}
					}
				});
			})
		});
	}

	let autoClear = document.querySelector('#auto-clear');
	if (autoClear) {
		['click', 'tap'].forEach((eventName, index) => {
			autoClear.addEventListener(eventName, (e) => {
				e.preventDefault();

				const fields = document.querySelectorAll('.cptui-labels input[type="text"]');
				Array.from(fields).forEach(field => {
					field.value = '';
				});
			})
		});
	}

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
	const all_panels = ["#cptui_panel_pt_basic_settings", "#cptui_panel_pt_additional_labels", "#cptui_panel_pt_advanced_settings", "#cptui_panel_tax_basic_settings", "#cptui_panel_tax_additional_labels", "#cptui_panel_tax_advanced_settings"];
	all_panels.forEach((element, index) => {
		let panel_id_item = document.querySelector(element);
		let panel_id;
		if (panel_id_item) {
			panel_id = panel_id_item.getAttribute('id');
			let panel = document.querySelector('#' + panel_id);

			// check default state on page load
			if (!localStorage.getItem(panel_id) || localStorage.getItem(panel_id) === null) {
				panel.classList.remove('closed');
			} else {
				panel.classList.add('closed');
			}

			let postbox = panel_id_item.querySelectorAll('.postbox-header');
			Array.from(postbox).forEach((el, i) => {
				el.addEventListener('click', (e) => {
					if (!localStorage.getItem(panel_id)) {
						localStorage.setItem(panel_id, '1');
					} else {
						localStorage.removeItem(panel_id);
					}
				})
			});
		}
	});

})(jQuery);
