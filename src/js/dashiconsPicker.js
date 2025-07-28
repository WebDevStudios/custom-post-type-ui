/**
 * Dashicons Picker
 *
 * Based on: https://github.com/bradvin/dashicons-picker/
 */

import { icons } from "./icons";

(function (window) {
	'use strict';

	console.log('wat'); console.log(icons);

	const pickerBtn = document.querySelector('.dashicons-picker');
	let offsetTop, offsetLeft;
	let popup = document.createElement('div');
	popup.classList.add('dashicon-picker-container');

	if (pickerBtn) {
		pickerBtn.addEventListener('click', handleBtnClick);
	}

	function handleBtnClick(e) {
		const btnPos = e.currentTarget.getBoundingClientRect();
		offsetTop = btnPos.top + window.scrollY;
		offsetLeft = btnPos.left + window.scrollX;
		createPopup(e.currentTarget);
	}

	const createPopup = function (button) {
		let target = document.querySelector('#menu_icon'),
			preview = '';
		popup.classList.add('dashicon-picker-open');

		let popupPickerControl = document.createElement('div');
		popupPickerControl.classList.add('dashicon-picker-control');

		let popupPickerList = document.createElement('ul');
		popupPickerList.classList.add('dashicon-picker-list');

		popup.insertAdjacentElement('beforeend', popupPickerControl);
		popup.insertAdjacentElement('beforeend', popupPickerList);
		popup.style.top = `${Math.floor(offsetTop)}px`;
		popup.style.left = `${Math.floor(offsetLeft)}px`;
		popup.style.display = 'block';
		let list = popup.querySelector('.dashicon-picker-list');

		for (let i in icons) {
			if (icons.hasOwnProperty(i)) {
				let listIcon = document.createElement('li');
				listIcon.setAttribute('data-icon', icons[i]);
				let listIconLink = document.createElement('a');
				listIconLink.setAttribute('href', '#');
				listIconLink.setAttribute('title', icons[i]);
				let listIconSpan = document.createElement('span');
				listIconSpan.classList.add('dashicons', `dashicons-${icons[i]}`);

				listIconLink.insertAdjacentElement('afterbegin', listIconSpan);
				listIcon.insertAdjacentElement('afterbegin', listIconLink);

				list.insertAdjacentElement('beforeend', listIcon);
			}
		}

		// Create our controls at the top of the popup.
		let popupBack = document.createElement('a');
		popupBack.setAttribute('data-direction', 'back');
		popupBack.setAttribute('href', '#');
		let popupBackSpan = document.createElement('span');
		popupBackSpan.classList.add('dashicons', 'dashicons-arrow-left-alt2');
		popupBack.insertAdjacentElement('afterbegin', popupBackSpan);

		let popupSearch = document.createElement('input');
		popupSearch.setAttribute('type', 'text');
		popupSearch.setAttribute('placeholder', cptui_type_data.dashicon_search_text)

		let popupForward = document.createElement('a');
		popupForward.setAttribute('data-direction', 'forward');
		popupForward.setAttribute('href', '#');
		let popupForwardSpan = document.createElement('span');
		popupForwardSpan.classList.add('dashicons', 'dashicons-arrow-right-alt2');
		popupForward.insertAdjacentElement('afterbegin', popupForwardSpan);

		let control = popup.querySelector('.dashicon-picker-control');
		control.insertAdjacentElement('beforeend', popupBack);
		control.insertAdjacentElement('beforeend', popupSearch);
		control.insertAdjacentElement('beforeend', popupForward);

		console.log(list);

		// Paginate list.
		let paginationLinks = control.querySelectorAll('a');
		let iconsCount = icons.length;
		Array.from(paginationLinks).forEach(pagLink => {
			pagLink.addEventListener('click', (e) => {
				e.preventDefault();
				let direction = e.currentTarget.getAttribute('data-direction');
				if (direction === 'back') {
					let listItems = list.querySelectorAll('li');
					let set = listItems.slice(0, 5);
					console.log(set);
					//Select from contexts of `list` variable.
					//list.insertAdjacentElement('afterbegin',listItems);
				} else {
					//.insertAdjacentElement('beforeend',list);
				}
			});
		});

		function moveElementsToEndOfArray(arr, x) {

			let n = arr.length;

			// if x is greater than length
			// of the array
			x = x % n;

			let first_x_elements = arr.slice(0, x);

			let remaining_elements = arr.slice(x, n);

			// Destructuring to create the desired array
			arr = [...remaining_elements, ...first_x_elements];

		}

		// is `list` getting mutated? We have 303 icons and I'm not sure what the starting index is each time.
		/*
	   The :lt() selector selects elements with an index number less than a specified number.
	   The index numbers start at 0.
	   This is mostly used together with another selector to select the first elements in a group (like in the example above).
	   Tip: Use the :gt selector to select elements index numbers greater than the specified number.

	   the entire popup has all icons already. I think the CSS strategically hides all but 25 at a time.
		 */
		/*$('a', control).on('click', function (e) {
			e.preventDefault();
			if ($(this).data('direction') === 'back') {
				// takes the last 25 items in current list, puts at the start.
				$('li:gt(' + (icons.length - 26) + ')', list).prependTo(list);
			} else {
				// takes the first 25 items, removes from the start of the list, adds to the end of the list.
				$('li:lt(25)', list).appendTo(list);
			}
		});*/


		//Append our popup to the body of the page.
		document.body.insertAdjacentElement('beforeend', popup);
	};


	// ATTEMPT TO CLOSE THE POPUP.
	let cptuitable = document.querySelectorAll('.cptui-table');
	if (cptuitable) {
		Array.from(cptuitable).forEach(item => {
			item.addEventListener('click', (e) => {
				let target = document.querySelector('.dashicon-picker-open');
				const within = e.composedPath().includes(target);
				if (target && !within) {
					target.classList.remove('dashicon-picker-open');
				}
			});
		});
	}


	/*$.fn.dashiconsPicker = function () {

		return this.each( function () {

			function createPopup( button ) {

				var preview = $( button.data( 'preview' ) ),

				$( 'a', list ).on( 'click', function ( e ) {
					e.preventDefault();
					var title = $( this ).attr( 'title' );
					target.val( 'dashicons-' + title ).change();
					preview
						.prop('class', 'dashicons')
						.addClass( 'dashicons-' + title );
					removePopup();
				} );

				$( 'a', control ).on( 'click', function ( e ) {
					e.preventDefault();
					if ( $( this ).data( 'direction' ) === 'back' ) {
						$( 'li:gt(' + ( icons.length - 26 ) + ')', list ).prependTo( list );
					} else {
						$( 'li:lt(25)', list ).appendTo( list );
					}
				} );


				$( 'input', control ).on( 'keyup', function ( e ) {
					var search = $( this ).val();
					if ( search === '' ) {
						$( 'li:lt(25)', list ).show();
					} else {
						$( 'li', list ).each( function () {
							if ( $( this ).data( 'icon' ).toLowerCase().indexOf( search.toLowerCase() ) !== -1 ) {
								$( this ).show();
							} else {
								$( this ).hide();
							}
						} );
					}
				} );

				$( document ).on( 'mouseup.dashicons-picker', function ( e ) {
					if ( ! popup.is( e.target ) && popup.has( e.target ).length === 0 ) {
						removePopup();
					}
				} );
			}

		} );
	};*/

}(window));
