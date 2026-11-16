'use strict';

/*
 * Help page: filter questions as you type, and open a question linked by its #anchor.
 *
 * The questions are native <details> elements, so they open and close without JavaScript.
 */

(() => {
	const faqs = document.querySelector('.cptui-support__faqs');
	if (!faqs) {
		return;
	}

	const items = Array.from(faqs.querySelectorAll('.cptui-faq'));
	const sections = Array.from(faqs.querySelectorAll('.cptui-support__section'));
	const noResults = faqs.querySelector('.cptui-support__no-results');
	const search = document.getElementById('cptui-faq-search');

	const openFromHash = () => {
		if (!window.location.hash) {
			return;
		}
		const target = document.getElementById(window.location.hash.slice(1));
		if (target && target.classList.contains('cptui-faq')) {
			target.open = true;
			target.scrollIntoView({ block: 'start' });
		}
	};

	openFromHash();
	window.addEventListener('hashchange', openFromHash);

	if (!search) {
		return;
	}

	// Remember which questions the user opened, so clearing the search restores them.
	const userOpen = new WeakMap();
	items.forEach((item) => {
		item.addEventListener('toggle', () => {
			if (!search.value.trim()) {
				userOpen.set(item, item.open);
			}
		});
	});

	search.addEventListener('input', () => {
		const terms = search.value.trim().toLowerCase().split(/\s+/).filter(Boolean);
		let matches = 0;

		items.forEach((item) => {
			const text = item.textContent.toLowerCase();
			const match = terms.every((term) => text.includes(term));
			item.hidden = !match;
			if (match) {
				matches++;
			}
			item.open = terms.length ? match : !!userOpen.get(item);
		});

		sections.forEach((section) => {
			const visible = section.querySelector('.cptui-faq:not([hidden])');
			section.hidden = !visible && !section.classList.contains('cptui-support__section--custom');
		});

		if (noResults) {
			noResults.hidden = matches > 0 || !terms.length;
		}
	});
})();
