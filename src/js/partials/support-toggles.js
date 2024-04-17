'use strict';

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

		['click', 'keydown'].forEach((theEvent) => {
			question.addEventListener(theEvent, (e) => {
				// Helps with accessibility and keyboard navigation.
				let keys = ['Space', 'Enter'];
				if (e.type === 'keydown' && !keys.includes(e.code)) {
					return
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
})();
