/**
 * Dashicons Picker
 *
 * Based on: https://github.com/bradvin/dashicons-picker/
 */

(function (window) {
	'use strict';

	const icons = [
		'menu',
		'admin-site',
		'dashboard',
		'admin-media',
		'admin-page',
		'admin-comments',
		'admin-appearance',
		'admin-plugins',
		'admin-users',
		'admin-tools',
		'admin-settings',
		'admin-network',
		'admin-generic',
		'admin-home',
		'admin-collapse',
		'filter',
		'admin-customizer',
		'admin-multisite',
		'admin-links',
		'format-links',
		'admin-post',
		'format-standard',
		'format-image',
		'format-gallery',
		'format-audio',
		'format-video',
		'format-chat',
		'format-status',
		'format-aside',
		'format-quote',
		'welcome-write-blog',
		'welcome-edit-page',
		'welcome-add-page',
		'welcome-view-site',
		'welcome-widgets-menus',
		'welcome-comments',
		'welcome-learn-more',
		'image-crop',
		'image-rotate',
		'image-rotate-left',
		'image-rotate-right',
		'image-flip-vertical',
		'image-flip-horizontal',
		'image-filter',
		'undo',
		'redo',
		'editor-bold',
		'editor-italic',
		'editor-ul',
		'editor-ol',
		'editor-quote',
		'editor-alignleft',
		'editor-aligncenter',
		'editor-alignright',
		'editor-insertmore',
		'editor-spellcheck',
		'editor-distractionfree',
		'editor-expand',
		'editor-contract',
		'editor-kitchensink',
		'editor-underline',
		'editor-justify',
		'editor-textcolor',
		'editor-paste-word',
		'editor-paste-text',
		'editor-removeformatting',
		'editor-video',
		'editor-customchar',
		'editor-outdent',
		'editor-indent',
		'editor-help',
		'editor-strikethrough',
		'editor-unlink',
		'editor-rtl',
		'editor-break',
		'editor-code',
		'editor-paragraph',
		'editor-table',
		'align-left',
		'align-right',
		'align-center',
		'align-none',
		'lock',
		'unlock',
		'calendar',
		'calendar-alt',
		'visibility',
		'hidden',
		'post-status',
		'edit',
		'post-trash',
		'trash',
		'sticky',
		'external',
		'arrow-up',
		'arrow-down',
		'arrow-left',
		'arrow-right',
		'arrow-up-alt',
		'arrow-down-alt',
		'arrow-left-alt',
		'arrow-right-alt',
		'arrow-up-alt2',
		'arrow-down-alt2',
		'arrow-left-alt2',
		'arrow-right-alt2',
		'leftright',
		'sort',
		'randomize',
		'list-view',
		'excerpt-view',
		'grid-view',
		'hammer',
		'art',
		'migrate',
		'performance',
		'universal-access',
		'universal-access-alt',
		'tickets',
		'nametag',
		'clipboard',
		'heart',
		'megaphone',
		'schedule',
		'wordpress',
		'wordpress-alt',
		'pressthis',
		'update',
		'screenoptions',
		'cart',
		'feedback',
		'cloud',
		'translation',
		'tag',
		'category',
		'archive',
		'tagcloud',
		'text',
		'media-archive',
		'media-audio',
		'media-code',
		'media-default',
		'media-document',
		'media-interactive',
		'media-spreadsheet',
		'media-text',
		'media-video',
		'playlist-audio',
		'playlist-video',
		'controls-play',
		'controls-pause',
		'controls-forward',
		'controls-skipforward',
		'controls-back',
		'controls-skipback',
		'controls-repeat',
		'controls-volumeon',
		'controls-volumeoff',
		'yes',
		'no',
		'no-alt',
		'plus',
		'plus-alt',
		'plus-alt2',
		'minus',
		'dismiss',
		'marker',
		'star-filled',
		'star-half',
		'star-empty',
		'flag',
		'info',
		'warning',
		'share',
		'share1',
		'share-alt',
		'share-alt2',
		'twitter',
		'rss',
		'email',
		'email-alt',
		'facebook',
		'facebook-alt',
		'networking',
		'googleplus',
		'location',
		'location-alt',
		'camera',
		'images-alt',
		'images-alt2',
		'video-alt',
		'video-alt2',
		'video-alt3',
		'vault',
		'shield',
		'shield-alt',
		'sos',
		'search',
		'slides',
		'analytics',
		'chart-pie',
		'chart-bar',
		'chart-line',
		'chart-area',
		'groups',
		'businessman',
		'id',
		'id-alt',
		'products',
		'awards',
		'forms',
		'testimonial',
		'portfolio',
		'book',
		'book-alt',
		'download',
		'upload',
		'backup',
		'clock',
		'lightbulb',
		'microphone',
		'desktop',
		'tablet',
		'smartphone',
		'phone',
		'smiley',
		'index-card',
		'carrot',
		'building',
		'store',
		'album',
		'palmtree',
		'tickets-alt',
		'money',
		'thumbs-up',
		'thumbs-down',
		'layout',
		'align-pull-left',
		'align-pull-right',
		'block-default',
		'cloud-saved',
		'cloud-upload',
		'columns',
		'cover-image',
		'embed-audio',
		'embed-generic',
		'embed-photo',
		'embed-post',
		'embed-video',
		'exit',
		'html',
		'info-outline',
		'insert-after',
		'insert-before',
		'insert',
		'remove',
		'shortcode',
		'table-col-after',
		'table-col-before',
		'table-col-delete',
		'table-row-after',
		'table-row-before',
		'table-row-delete',
		'saved',
		'amazon',
		'google',
		'linkedin',
		'pinterest',
		'podio',
		'reddit',
		'spotify',
		'twitch',
		'whatsapp',
		'xing',
		'youtube',
		'database-add',
		'database-export',
		'database-import',
		'database-remove',
		'database-view',
		'database',
		'bell',
		'airplane',
		'car',
		'calculator',
		'ames',
		'printer',
		'beer',
		'coffee',
		'drumstick',
		'food',
		'bank',
		'hourglass',
		'money-alt',
		'open-folder',
		'pdf',
		'pets',
		'privacy',
		'superhero',
		'superhero-alt',
		'edit-page',
		'fullscreen-alt',
		'fullscreen-exit-alt'
	];
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
