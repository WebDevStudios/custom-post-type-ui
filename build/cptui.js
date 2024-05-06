/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ 573:
/***/ (() => {



/*
 * This file handles the automatic population as well as the automatic clearing of the label
 * fields, based on the provided singular and plural label values.
 */
(() => {
  let nameField = document.querySelector('#name');
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

/***/ 355:
/***/ (() => {



/*
 * This file handles automatically switching to a chosen content type when selecting from the
 * dropdown listing.
 */
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

/***/ 735:
/***/ (() => {



/*
 * This file handles the back to top functionality as the user scrolls, for quick return to top.
 *
 * This includes some debouncing to prevent excessive scroll event listening.
 */
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

/***/ 737:
/***/ (() => {



/*
 * This file handles confirming the deletion of a content type before continuing.
 *
 * @todo Finish converting away from jQuery.
 */
($ => {
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
})(jQuery);

/***/ }),

/***/ 170:
/***/ (() => {



/*
 * This file visually removes the submit button to change content type being edited.
 *
 * If by chance javascript is disabled or somehow breaking, the button would show by default,
 * preventing issues with switching content types.
 */
(() => {
  const cptSelectSubmit = document.querySelector('#cptui_select_post_type_submit');
  if (cptSelectSubmit) {
    cptSelectSubmit.style.display = 'none';
  }
  const taxSelectSubmit = document.querySelector('#cptui_select_taxonomy_submit');
  if (taxSelectSubmit) {
    taxSelectSubmit.style.display = 'none';
  }
})();

/***/ }),

/***/ 339:
/***/ (() => {



/*
 * This file handles accordian behavior on the Supports page with the various question/answer panels.
 *
 * The functionality includes keyboard and accessibility functionality to help those who need it.
 */
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

/***/ 201:
/***/ (() => {



/*
 * This file provides a dialog box to alert the user that at least one post type must be chosen
 * before they can save a taxonomy.
 *
 * This was added because taxonomies need to have a post type, meanwhile post types do NOT need
 * to have a taxonomy.
 */
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

/***/ 306:
/***/ (() => {



/*
 * This file handles automatically toggling the "Page attributes" option in the "Supports" section
 * when a user chooses to have their post type be hierarchical.
 *
 * The purpose is to help ensure that the "parent" and "template" metabox option shows up by default,
 * but we do not force that to remain checked. The user can still toggle it off after the fact.
 */
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

/***/ 172:
/***/ (() => {



/*
 * This file handles storing the panel state for the post type and taxonomy edit screens.
 *
 * The open/closed state gets stored into localstorage and is remembered on future page refreshes.
 */
postboxes.add_postbox_toggles(pagenow);
(() => {
  // Toggle Panels State.
  // @todo. Localize the list of panel selectors so that we can filter in the CPTUI-Extended panel without hardcoding here.
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
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {

;// CONCATENATED MODULE: ./src/js/partials/utils.js


// Retrieve URL parameters by requested parameter name.
function getParameterByName(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, "\\$&");
  const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
    results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, " "));
}

// Split, translate cyrillic characters, and then re-join the final result.
function transliterate(word) {
  return word.split('').map(function (char) {
    return cyrillic[char] || char;
  }).join("");
}

//Character encode special characters.
function htmlEncode(str) {
  return String(str).replace(/[^-\w. ]/gi, function (c) {
    return '&#' + c.charCodeAt(0) + ';';
  });
}

// Constructs miniture versions of uploaded media for admnin menu icon usage,
// or displays the rendered dashicon.
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

// Converts non-alphanumeric or space characters to an underscore. Should ignore dashes, to allow
// using dashes in slugs.
function replaceSpecialCharacters(s) {
  s = s.replace(/[^a-z0-9\s-]/gi, '_');
  return s;
}

// List of available cyrillic characters and the value to translate to.
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
// EXTERNAL MODULE: ./src/js/partials/hide-submit.js
var hide_submit = __webpack_require__(170);
// EXTERNAL MODULE: ./src/js/partials/toggle-hierarchical.js
var toggle_hierarchical = __webpack_require__(306);
// EXTERNAL MODULE: ./src/js/partials/autoswitch.js
var autoswitch = __webpack_require__(355);
// EXTERNAL MODULE: ./src/js/partials/confirm-delete.js
var confirm_delete = __webpack_require__(737);
// EXTERNAL MODULE: ./src/js/partials/support-toggles.js
var support_toggles = __webpack_require__(339);
;// CONCATENATED MODULE: ./src/js/partials/namefield.js




/*
 * This file handles all of the normalization of the name/slug field for a post type
 * or taxonomy being registered.
 *
 * That way we are only allowing latin characters and dashes/underscores.
 *
 * It also shows a hidden alert if the slug has been changed in some way when editing an existing
 * content type.
 *
 * Lastly it will also show a warning if the attempted slug has already been registered elsewhere,
 * to help avoid clashes. The only exception is if the checkbox is checked indicating that the user
 * is trying to convert TO using CPTUI, and the conflicting slug elsewhere will be removed soon.
 */

(() => {
  let nameField = document.querySelector('#name');
  let original_slug;
  if ('edit' === getParameterByName('action')) {
    if (nameField) {
      // Store our original slug on page load for edit checking.
      original_slug = nameField.value;
    }
  }
  if (nameField) {
    // Switch spaces for underscores on our slug fields.
    nameField.addEventListener('keyup', e => {
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
      let override_validation = override ? override.check : false;
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
  }
})();
;// CONCATENATED MODULE: ./src/js/partials/menu-icon.js




/*
 * This file handles setting the menu icon preview for a given post type.
 *
 * @todo Finish converting away from jQuery.
 */

($ => {
  let _custom_media;
  let _orig_send_attachment;
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
// EXTERNAL MODULE: ./src/js/partials/tax-required-post-type.js
var tax_required_post_type = __webpack_require__(201);
// EXTERNAL MODULE: ./src/js/partials/autopopulate.js
var autopopulate = __webpack_require__(573);
// EXTERNAL MODULE: ./src/js/partials/back-to-top.js
var back_to_top = __webpack_require__(735);
// EXTERNAL MODULE: ./src/js/partials/toggle-panels.js
var toggle_panels = __webpack_require__(172);
;// CONCATENATED MODULE: ./src/js/cptui.js













//import './dashicons-picker';
})();

/******/ })()
;
//# sourceMappingURL=cptui.js.map