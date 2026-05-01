/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
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
/************************************************************************/

;// external ["wp","plugins"]
const external_wp_plugins_namespaceObject = window["wp"]["plugins"];
;// external ["wp","editor"]
const external_wp_editor_namespaceObject = window["wp"]["editor"];
;// external ["wp","data"]
const external_wp_data_namespaceObject = window["wp"]["data"];
;// external ["wp","element"]
const external_wp_element_namespaceObject = window["wp"]["element"];
;// external ["wp","components"]
const external_wp_components_namespaceObject = window["wp"]["components"];
;// external ["wp","i18n"]
const external_wp_i18n_namespaceObject = window["wp"]["i18n"];
;// external ["wp","apiFetch"]
const external_wp_apiFetch_namespaceObject = window["wp"]["apiFetch"];
var external_wp_apiFetch_default = /*#__PURE__*/__webpack_require__.n(external_wp_apiFetch_namespaceObject);
;// external "ReactJSXRuntime"
const external_ReactJSXRuntime_namespaceObject = window["ReactJSXRuntime"];
;// ./src/js/cptui-editor.js








const CPTUIProPanel = () => {
  const postType = (0,external_wp_data_namespaceObject.useSelect)(select => select('core/editor').getCurrentPostType(), []);
  const [dismissed, setDismissed] = (0,external_wp_element_namespaceObject.useState)(false);
  const [dismissing, setDismissing] = (0,external_wp_element_namespaceObject.useState)(false);
  const config = window.cptuiProPanel || {};
  const allowedTypes = config.postTypes || [];
  if (dismissed || !postType || !allowedTypes.includes(postType)) {
    return null;
  }
  const handleDismiss = () => {
    setDismissing(true);
    external_wp_apiFetch_default()({
      path: '/cptui/v1/dismiss-pro-upsell',
      method: 'POST'
    }).then(() => {
      setDismissed(true);
    }).catch(() => {
      setDismissing(false);
    });
  };
  return /*#__PURE__*/(0,external_ReactJSXRuntime_namespaceObject.jsxs)(external_wp_editor_namespaceObject.PluginDocumentSettingPanel, {
    name: "cptui-pro-callout",
    title: (0,external_wp_i18n_namespaceObject.__)('Display with CPT UI Pro', 'custom-post-type-ui'),
    className: "cptui-pro-panel",
    children: [/*#__PURE__*/(0,external_ReactJSXRuntime_namespaceObject.jsx)("p", {
      style: {
        marginTop: 0
      },
      children: (0,external_wp_i18n_namespaceObject.__)('CPT UI Pro adds a dedicated Gutenberg block for displaying this content anywhere on your site — pull and render this post type inside any block-editor post or page, no code required.', 'custom-post-type-ui')
    }), /*#__PURE__*/(0,external_ReactJSXRuntime_namespaceObject.jsxs)("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        gap: '12px',
        flexWrap: 'wrap'
      },
      children: [/*#__PURE__*/(0,external_ReactJSXRuntime_namespaceObject.jsx)(external_wp_components_namespaceObject.Button, {
        variant: "primary",
        href: config.proUrl,
        target: "_blank",
        rel: "noopener noreferrer",
        children: (0,external_wp_i18n_namespaceObject.__)('Get CPT UI Pro', 'custom-post-type-ui')
      }), /*#__PURE__*/(0,external_ReactJSXRuntime_namespaceObject.jsx)(external_wp_components_namespaceObject.Button, {
        variant: "link",
        onClick: handleDismiss,
        disabled: dismissing,
        children: (0,external_wp_i18n_namespaceObject.__)('Dismiss', 'custom-post-type-ui')
      })]
    })]
  });
};
(0,external_wp_plugins_namespaceObject.registerPlugin)('cptui-pro-panel', {
  render: CPTUIProPanel
});
/******/ })()
;
//# sourceMappingURL=cptui-editor.js.map