/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/cptui-scripts.js":
/*!*********************************!*\
  !*** ./src/js/cptui-scripts.js ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _partials_utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./partials/utils */ "./src/js/partials/utils.js");
/**
 * Add collapseable boxes to our editor screens.
 */




postboxes.add_postbox_toggles(pagenow);

/**
 * The rest of our customizations.
 */
(function ($) {
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
  if ('edit' === (0,_partials_utils__WEBPACK_IMPORTED_MODULE_0__.getParameterByName)('action')) {
    if (nameField) {
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
      'modal': true,
      'autoOpen': true,
      'buttons': {
        "OK": function () {
          $(this).dialog('close');
          $(e.target).off('click').click();
        },
        "Cancel": function () {
          $(this).dialog('close');
        }
      }
    });
  });
  if (undefined !== wp.media) {
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

/***/ }),

/***/ "./src/js/dashicons-picker.js":
/*!************************************!*\
  !*** ./src/js/dashicons-picker.js ***!
  \************************************/
/***/ (() => {

/**
 * Dashicons Picker
 *
 * Based on: https://github.com/bradvin/dashicons-picker/
 */

(function ($) {
  'use strict';

  /**
   *
   * @returns {void}
   */
  $.fn.dashiconsPicker = function () {
    /**
     * Dashicons, in CSS order
     *
     * @type Array
     */
    var icons = ['menu', 'admin-site', 'dashboard', 'admin-media', 'admin-page', 'admin-comments', 'admin-appearance', 'admin-plugins', 'admin-users', 'admin-tools', 'admin-settings', 'admin-network', 'admin-generic', 'admin-home', 'admin-collapse', 'filter', 'admin-customizer', 'admin-multisite', 'admin-links', 'format-links', 'admin-post', 'format-standard', 'format-image', 'format-gallery', 'format-audio', 'format-video', 'format-chat', 'format-status', 'format-aside', 'format-quote', 'welcome-write-blog', 'welcome-edit-page', 'welcome-add-page', 'welcome-view-site', 'welcome-widgets-menus', 'welcome-comments', 'welcome-learn-more', 'image-crop', 'image-rotate', 'image-rotate-left', 'image-rotate-right', 'image-flip-vertical', 'image-flip-horizontal', 'image-filter', 'undo', 'redo', 'editor-bold', 'editor-italic', 'editor-ul', 'editor-ol', 'editor-quote', 'editor-alignleft', 'editor-aligncenter', 'editor-alignright', 'editor-insertmore', 'editor-spellcheck', 'editor-distractionfree', 'editor-expand', 'editor-contract', 'editor-kitchensink', 'editor-underline', 'editor-justify', 'editor-textcolor', 'editor-paste-word', 'editor-paste-text', 'editor-removeformatting', 'editor-video', 'editor-customchar', 'editor-outdent', 'editor-indent', 'editor-help', 'editor-strikethrough', 'editor-unlink', 'editor-rtl', 'editor-break', 'editor-code', 'editor-paragraph', 'editor-table', 'align-left', 'align-right', 'align-center', 'align-none', 'lock', 'unlock', 'calendar', 'calendar-alt', 'visibility', 'hidden', 'post-status', 'edit', 'post-trash', 'trash', 'sticky', 'external', 'arrow-up', 'arrow-down', 'arrow-left', 'arrow-right', 'arrow-up-alt', 'arrow-down-alt', 'arrow-left-alt', 'arrow-right-alt', 'arrow-up-alt2', 'arrow-down-alt2', 'arrow-left-alt2', 'arrow-right-alt2', 'leftright', 'sort', 'randomize', 'list-view', 'excerpt-view', 'grid-view', 'hammer', 'art', 'migrate', 'performance', 'universal-access', 'universal-access-alt', 'tickets', 'nametag', 'clipboard', 'heart', 'megaphone', 'schedule', 'wordpress', 'wordpress-alt', 'pressthis', 'update', 'screenoptions', 'cart', 'feedback', 'cloud', 'translation', 'tag', 'category', 'archive', 'tagcloud', 'text', 'media-archive', 'media-audio', 'media-code', 'media-default', 'media-document', 'media-interactive', 'media-spreadsheet', 'media-text', 'media-video', 'playlist-audio', 'playlist-video', 'controls-play', 'controls-pause', 'controls-forward', 'controls-skipforward', 'controls-back', 'controls-skipback', 'controls-repeat', 'controls-volumeon', 'controls-volumeoff', 'yes', 'no', 'no-alt', 'plus', 'plus-alt', 'plus-alt2', 'minus', 'dismiss', 'marker', 'star-filled', 'star-half', 'star-empty', 'flag', 'info', 'warning', 'share', 'share1', 'share-alt', 'share-alt2', 'twitter', 'rss', 'email', 'email-alt', 'facebook', 'facebook-alt', 'networking', 'googleplus', 'location', 'location-alt', 'camera', 'images-alt', 'images-alt2', 'video-alt', 'video-alt2', 'video-alt3', 'vault', 'shield', 'shield-alt', 'sos', 'search', 'slides', 'analytics', 'chart-pie', 'chart-bar', 'chart-line', 'chart-area', 'groups', 'businessman', 'id', 'id-alt', 'products', 'awards', 'forms', 'testimonial', 'portfolio', 'book', 'book-alt', 'download', 'upload', 'backup', 'clock', 'lightbulb', 'microphone', 'desktop', 'tablet', 'smartphone', 'phone', 'smiley', 'index-card', 'carrot', 'building', 'store', 'album', 'palmtree', 'tickets-alt', 'money', 'thumbs-up', 'thumbs-down', 'layout', 'align-pull-left', 'align-pull-right', 'block-default', 'cloud-saved', 'cloud-upload', 'columns', 'cover-image', 'embed-audio', 'embed-generic', 'embed-photo', 'embed-post', 'embed-video', 'exit', 'html', 'info-outline', 'insert-after', 'insert-before', 'insert', 'remove', 'shortcode', 'table-col-after', 'table-col-before', 'table-col-delete', 'table-row-after', 'table-row-before', 'table-row-delete', 'saved', 'amazon', 'google', 'linkedin', 'pinterest', 'podio', 'reddit', 'spotify', 'twitch', 'whatsapp', 'xing', 'youtube', 'database-add', 'database-export', 'database-import', 'database-remove', 'database-view', 'database', 'bell', 'airplane', 'car', 'calculator', 'ames', 'printer', 'beer', 'coffee', 'drumstick', 'food', 'bank', 'hourglass', 'money-alt', 'open-folder', 'pdf', 'pets', 'privacy', 'superhero', 'superhero-alt', 'edit-page', 'fullscreen-alt', 'fullscreen-exit-alt'];
    return this.each(function () {
      var button = $(this),
        offsetTop,
        offsetLeft;
      button.on('click.dashiconsPicker', function (e) {
        offsetTop = $(e.currentTarget).offset().top;
        offsetLeft = $(e.currentTarget).offset().left;
        createPopup(button);
      });
      function createPopup(button) {
        var target = $('#menu_icon'),
          preview = $(button.data('preview')),
          popup = $('<div class="dashicon-picker-container">' + '<div class="dashicon-picker-control"></div>' + '<ul class="dashicon-picker-list"></ul>' + '</div>').css({
            'top': offsetTop,
            'left': offsetLeft
          }),
          list = popup.find('.dashicon-picker-list');
        for (var i in icons) {
          if (icons.hasOwnProperty(i)) {
            list.append('<li data-icon="' + icons[i] + '"><a href="#" title="' + icons[i] + '"><span class="dashicons dashicons-' + icons[i] + '"></span></a></li>');
          }
        }
        $('a', list).on('click', function (e) {
          e.preventDefault();
          var title = $(this).attr('title');
          target.val('dashicons-' + title).change();
          preview.prop('class', 'dashicons').addClass('dashicons-' + title);
          removePopup();
        });
        var control = popup.find('.dashicon-picker-control');
        control.html('<a data-direction="back" href="#">' + '<span class="dashicons dashicons-arrow-left-alt2"></span></a>' + '<input type="text" class="" placeholder="Search" />' + '<a data-direction="forward" href="#"><span class="dashicons dashicons-arrow-right-alt2"></span></a>');
        $('a', control).on('click', function (e) {
          e.preventDefault();
          if ($(this).data('direction') === 'back') {
            $('li:gt(' + (icons.length - 26) + ')', list).prependTo(list);
          } else {
            $('li:lt(25)', list).appendTo(list);
          }
        });
        popup.appendTo('body').show();
        $('input', control).on('keyup', function (e) {
          var search = $(this).val();
          if (search === '') {
            $('li:lt(25)', list).show();
          } else {
            $('li', list).each(function () {
              if ($(this).data('icon').toLowerCase().indexOf(search.toLowerCase()) !== -1) {
                $(this).show();
              } else {
                $(this).hide();
              }
            });
          }
        });
        $(document).on('mouseup.dashicons-picker', function (e) {
          if (!popup.is(e.target) && popup.has(e.target).length === 0) {
            removePopup();
          }
        });
      }
      function removePopup() {
        $('.dashicon-picker-container').remove();
        $(document).off('.dashicons-picker');
      }
    });
  };
  $(function () {
    $('.dashicons-picker').dashiconsPicker();
  });
})(jQuery);

/***/ }),

/***/ "./src/js/partials/autopopulate.js":
/*!*****************************************!*\
  !*** ./src/js/partials/autopopulate.js ***!
  \*****************************************/
/***/ (() => {

"use strict";


(() => {
  let autoPopulate = document.querySelector('#auto-populate');
  if (autoPopulate) {
    ['click', 'tap'].forEach((eventName, index) => {
      autoPopulate.addEventListener(eventName, e => {
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
      });
    });
  }
  let autoClear = document.querySelector('#auto-clear');
  if (autoClear) {
    ['click', 'tap'].forEach((eventName, index) => {
      autoClear.addEventListener(eventName, e => {
        e.preventDefault();
        const fields = document.querySelectorAll('.cptui-labels input[type="text"]');
        Array.from(fields).forEach(field => {
          field.value = '';
        });
      });
    });
  }
})();

/***/ }),

/***/ "./src/js/partials/autoswitch.js":
/*!***************************************!*\
  !*** ./src/js/partials/autoswitch.js ***!
  \***************************************/
/***/ (() => {

"use strict";


(() => {
  // Switch to newly selected post type or taxonomy automatically.
  const postTypeDropdown = document.querySelector('#post_type');
  const taxonomyDropdown = document.querySelector('#taxonomy');
  if (postTypeDropdown) {
    postTypeDropdown.addEventListener('change', () => {
      const postTypeSelectPostType = document.querySelector('#cptui_select_post_type');
      if (postTypeSelectPostType) {
        postTypeSelectPostType.submit();
      }
    });
  }
  if (taxonomyDropdown) {
    taxonomyDropdown.addEventListener('change', () => {
      const taxonomySelectPostType = document.querySelector('#cptui_select_taxonomy');
      if (taxonomySelectPostType) {
        taxonomySelectPostType.submit();
      }
    });
  }
})();

/***/ }),

/***/ "./src/js/partials/back-to-top.js":
/*!****************************************!*\
  !*** ./src/js/partials/back-to-top.js ***!
  \****************************************/
/***/ (() => {

"use strict";


(() => {
  const back_to_top_btn = document.querySelector('.cptui-back-to-top');
  if (back_to_top_btn) {
    document.addEventListener('scroll', () => {
      cptuiDebounce(backToTop, 500);
    });
    back_to_top_btn.addEventListener('click', e => {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: "smooth"
      });
    });
  }
  function backToTop() {
    if (window.scrollY > 300) {
      back_to_top_btn.classList.add('show');
    } else {
      back_to_top_btn.classList.remove('show');
    }
  }
  function cptuiDebounce(method, delay) {
    clearTimeout(method._tId);
    method._tId = setTimeout(function () {
      method();
    }, delay);
  }
})();

/***/ }),

/***/ "./src/js/partials/support-toggles.js":
/*!********************************************!*\
  !*** ./src/js/partials/support-toggles.js ***!
  \********************************************/
/***/ (() => {

"use strict";


(() => {
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
    ['click', 'keydown'].forEach(theEvent => {
      question.addEventListener(theEvent, e => {
        // Helps with accessibility and keyboard navigation.
        let keys = ['Space', 'Enter'];
        if (e.type === 'keydown' && !keys.includes(e.code)) {
          return;
        }
        e.preventDefault();
        state = !state;
        answer.style.display = state ? 'block' : 'none';
        e.currentTarget.classList.toggle('active');
        e.currentTarget.setAttribute('aria-expanded', state.toString());
        e.currentTarget.focus();
      });
    });
  });
})();

/***/ }),

/***/ "./src/js/partials/tax-required-post-type.js":
/*!***************************************************!*\
  !*** ./src/js/partials/tax-required-post-type.js ***!
  \***************************************************/
/***/ (() => {

"use strict";


(() => {
  // Handles checking if a post type has been chosen or not when adding/saving a taxonomy.
  // Post type associations are a required attribute.
  const taxSubmit = document.querySelectorAll('.cptui-taxonomy-submit');
  const taxSubmitSelectCPTDialog = document.querySelector('#cptui-select-post-type-confirm');
  Array.from(taxSubmit).forEach((element, i) => {
    element.addEventListener('click', e => {
      // putting inside event listener to check every time clicked. Defining outside lost re-checking.
      let taxCPTChecked = document.querySelectorAll('#cptui_panel_tax_basic_settings input[type="checkbox"]:checked');
      if (taxCPTChecked.length === 0) {
        e.preventDefault();
        taxSubmitSelectCPTDialog.showModal();
      }
    });
  });
  let taxSubmitSelectCPTConfirmCloseBtn = document.querySelector('#cptui-select-post-type-confirm-close');
  if (taxSubmitSelectCPTConfirmCloseBtn) {
    taxSubmitSelectCPTConfirmCloseBtn.addEventListener('click', e => {
      e.preventDefault();
      taxSubmitSelectCPTDialog.close();
    });
  }
})();

/***/ }),

/***/ "./src/js/partials/toggle-hierarchical.js":
/*!************************************************!*\
  !*** ./src/js/partials/toggle-hierarchical.js ***!
  \************************************************/
/***/ (() => {

"use strict";


(() => {
  // Automatically toggle the "page attributes" checkbox if
  // setting a hierarchical post type.
  const hierarchicalSetting = document.querySelector('#hierarchical');
  if (hierarchicalSetting) {
    hierarchicalSetting.addEventListener('change', e => {
      let pageAttributesCheck = document.querySelector('#page-attributes');
      if (e.currentTarget && e.currentTarget.value === '1') {
        pageAttributesCheck.checked = true;
      } else {
        pageAttributesCheck.checked = false;
      }
    });
  }
})();

/***/ }),

/***/ "./src/js/partials/toggle-panels.js":
/*!******************************************!*\
  !*** ./src/js/partials/toggle-panels.js ***!
  \******************************************/
/***/ (() => {

"use strict";


(() => {
  // Toggle Panels State
  const all_panels = ["#cptui_panel_pt_basic_settings", "#cptui_panel_pt_additional_labels", "#cptui_panel_pt_advanced_settings", "#cptui_panel_tax_basic_settings", "#cptui_panel_tax_additional_labels", "#cptui_panel_tax_advanced_settings"];
  all_panels.forEach((element, index) => {
    const panel_id_item = document.querySelector(element);
    if (panel_id_item) {
      const panel_id = panel_id_item.getAttribute('id');
      const panel = document.querySelector('#' + panel_id);

      // check default state on page load
      if (!localStorage.getItem(panel_id) || localStorage.getItem(panel_id) === null) {
        panel.classList.remove('closed');
      } else {
        panel.classList.add('closed');
      }
      const postbox = panel_id_item.querySelectorAll('.postbox-header');
      Array.from(postbox).forEach((el, i) => {
        el.addEventListener('click', e => {
          if (!localStorage.getItem(panel_id)) {
            localStorage.setItem(panel_id, '1');
          } else {
            localStorage.removeItem(panel_id);
          }
        });
      });
    }
  });
})();

/***/ }),

/***/ "./src/js/partials/utils.js":
/*!**********************************!*\
  !*** ./src/js/partials/utils.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   composePreviewContent: () => (/* binding */ composePreviewContent),
/* harmony export */   cyrillic: () => (/* binding */ cyrillic),
/* harmony export */   getParameterByName: () => (/* binding */ getParameterByName),
/* harmony export */   htmlEncode: () => (/* binding */ htmlEncode),
/* harmony export */   replaceDiacritics: () => (/* binding */ replaceDiacritics),
/* harmony export */   replaceSpecialCharacters: () => (/* binding */ replaceSpecialCharacters),
/* harmony export */   transliterate: () => (/* binding */ transliterate)
/* harmony export */ });


function getParameterByName(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, "\\$&");
  const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
    results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, " "));
}
function transliterate(word) {
  return word.split('').map(function (char) {
    return cyrillic[char] || char;
  }).join("");
}
function htmlEncode(str) {
  return String(str).replace(/[^-\w. ]/gi, function (c) {
    return '&#' + c.charCodeAt(0) + ';';
  });
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

// Replace diacritic characters with latin characters.
function replaceDiacritics(s) {
  const diacritics = [/[\300-\306]/g, /[\340-\346]/g,
  // A, a
  /[\310-\313]/g, /[\350-\353]/g,
  // E, e
  /[\314-\317]/g, /[\354-\357]/g,
  // I, i
  /[\322-\330]/g, /[\362-\370]/g,
  // O, o
  /[\331-\334]/g, /[\371-\374]/g,
  // U, u
  /[\321]/g, /[\361]/g,
  // N, n
  /[\307]/g, /[\347]/g // C, c
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
const cyrillic = {
  "Ё": "YO",
  "Й": "I",
  "Ц": "TS",
  "У": "U",
  "К": "K",
  "Е": "E",
  "Н": "N",
  "Г": "G",
  "Ш": "SH",
  "Щ": "SCH",
  "З": "Z",
  "Х": "H",
  "Ъ": "'",
  "ё": "yo",
  "й": "i",
  "ц": "ts",
  "у": "u",
  "к": "k",
  "е": "e",
  "н": "n",
  "г": "g",
  "ш": "sh",
  "щ": "sch",
  "з": "z",
  "х": "h",
  "ъ": "'",
  "Ф": "F",
  "Ы": "I",
  "В": "V",
  "А": "a",
  "П": "P",
  "Р": "R",
  "О": "O",
  "Л": "L",
  "Д": "D",
  "Ж": "ZH",
  "Э": "E",
  "ф": "f",
  "ы": "i",
  "в": "v",
  "а": "a",
  "п": "p",
  "р": "r",
  "о": "o",
  "л": "l",
  "д": "d",
  "ж": "zh",
  "э": "e",
  "Я": "Ya",
  "Ч": "CH",
  "С": "S",
  "М": "M",
  "И": "I",
  "Т": "T",
  "Ь": "'",
  "Б": "B",
  "Ю": "YU",
  "я": "ya",
  "ч": "ch",
  "с": "s",
  "м": "m",
  "и": "i",
  "т": "t",
  "ь": "'",
  "б": "b",
  "ю": "yu"
};

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!*************************!*\
  !*** ./src/js/cptui.js ***!
  \*************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _partials_utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./partials/utils */ "./src/js/partials/utils.js");
/* harmony import */ var _partials_toggle_hierarchical__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./partials/toggle-hierarchical */ "./src/js/partials/toggle-hierarchical.js");
/* harmony import */ var _partials_toggle_hierarchical__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_partials_toggle_hierarchical__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _partials_autoswitch__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./partials/autoswitch */ "./src/js/partials/autoswitch.js");
/* harmony import */ var _partials_autoswitch__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_partials_autoswitch__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _partials_support_toggles__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./partials/support-toggles */ "./src/js/partials/support-toggles.js");
/* harmony import */ var _partials_support_toggles__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_partials_support_toggles__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _partials_tax_required_post_type__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./partials/tax-required-post-type */ "./src/js/partials/tax-required-post-type.js");
/* harmony import */ var _partials_tax_required_post_type__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_partials_tax_required_post_type__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _partials_autopopulate__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./partials/autopopulate */ "./src/js/partials/autopopulate.js");
/* harmony import */ var _partials_autopopulate__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_partials_autopopulate__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _partials_back_to_top__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./partials/back-to-top */ "./src/js/partials/back-to-top.js");
/* harmony import */ var _partials_back_to_top__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_partials_back_to_top__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _partials_toggle_panels__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./partials/toggle-panels */ "./src/js/partials/toggle-panels.js");
/* harmony import */ var _partials_toggle_panels__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_partials_toggle_panels__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _cptui_scripts__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./cptui-scripts */ "./src/js/cptui-scripts.js");
/* harmony import */ var _dashicons_picker__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./dashicons-picker */ "./src/js/dashicons-picker.js");
/* harmony import */ var _dashicons_picker__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_dashicons_picker__WEBPACK_IMPORTED_MODULE_9__);










})();

/******/ })()
;
//# sourceMappingURL=cptui.js.map