'use strict';

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

		back_to_top_btn.addEventListener('click', (e) => {
			e.preventDefault();
			window.scrollTo({
				top     : 0,
				behavior: "smooth"
			})
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
