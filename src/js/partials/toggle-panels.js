'use strict';

postboxes.add_postbox_toggles(pagenow);

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
})();


