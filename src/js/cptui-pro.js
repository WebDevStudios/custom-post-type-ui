/**
 * CPT UI Pro feature tour.
 *
 * Any element with data-cptui-pro-tour="<pillar>" opens the tour dialog on
 * that tab. Vanilla JS, no dependencies.
 *
 * @since 1.19.4
 */

const reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

function getDialog() {
	return document.getElementById( 'cptui-pro-tour' );
}

function pauseVideos( scope ) {
	scope.querySelectorAll( 'video' ).forEach( ( video ) => video.pause() );
}

function selectTab( dialog, key, focus = false ) {
	const tabs = dialog.querySelectorAll( '[data-cptui-pro-tab]' );
	let selected = null;

	tabs.forEach( ( tab ) => {
		const isSelected = tab.dataset.cptuiProTab === key;
		const panel = document.getElementById( tab.getAttribute( 'aria-controls' ) );

		tab.setAttribute( 'aria-selected', isSelected ? 'true' : 'false' );
		tab.tabIndex = isSelected ? 0 : -1;

		if ( ! panel ) {
			return;
		}

		panel.hidden = ! isSelected;

		if ( ! isSelected ) {
			pauseVideos( panel );
			return;
		}

		selected = tab;
		const video = panel.querySelector( 'video[data-cptui-autoplay]' );
		if ( video ) {
			if ( reduceMotion ) {
				video.controls = true;
				video.preload = 'metadata';
			} else {
				video.play().catch( () => {
					video.controls = true;
				} );
			}
		}
	} );

	if ( ! selected && tabs.length ) {
		selectTab( dialog, tabs[ 0 ].dataset.cptuiProTab, focus );
		return;
	}

	if ( focus && selected ) {
		selected.focus();
	}
}

function openTour( key ) {
	const dialog = getDialog();
	if ( ! dialog || typeof dialog.showModal !== 'function' ) {
		return false;
	}

	if ( ! dialog.open ) {
		dialog.showModal();
	}
	selectTab( dialog, key, true );
	return true;
}

document.addEventListener( 'click', ( event ) => {
	const trigger = event.target.closest( '[data-cptui-pro-tour]' );
	if ( trigger ) {
		if ( openTour( trigger.dataset.cptuiProTour ) ) {
			event.preventDefault();
		}
		return;
	}

	const dialog = getDialog();
	if ( ! dialog || ! dialog.open ) {
		return;
	}

	const tab = event.target.closest( '[data-cptui-pro-tab]' );
	if ( tab ) {
		selectTab( dialog, tab.dataset.cptuiProTab );
		return;
	}

	// Close button, or a click on the backdrop (the dialog element itself).
	if ( event.target.closest( '[data-cptui-pro-close]' ) || event.target === dialog ) {
		dialog.close();
	}
} );

document.addEventListener( 'keydown', ( event ) => {
	const tab = event.target.closest && event.target.closest( '[data-cptui-pro-tab]' );
	if ( ! tab ) {
		return;
	}

	const tabs = Array.from( tab.parentElement.querySelectorAll( '[data-cptui-pro-tab]' ) );
	const index = tabs.indexOf( tab );
	let next = null;

	if ( event.key === 'ArrowRight' ) {
		next = tabs[ ( index + 1 ) % tabs.length ];
	} else if ( event.key === 'ArrowLeft' ) {
		next = tabs[ ( index - 1 + tabs.length ) % tabs.length ];
	} else if ( event.key === 'Home' ) {
		next = tabs[ 0 ];
	} else if ( event.key === 'End' ) {
		next = tabs[ tabs.length - 1 ];
	}

	if ( next ) {
		event.preventDefault();
		selectTab( getDialog(), next.dataset.cptuiProTab, true );
	}
} );

document.addEventListener( 'DOMContentLoaded', () => {
	const dialog = getDialog();
	if ( dialog ) {
		dialog.addEventListener( 'close', () => pauseVideos( dialog ) );
	}
} );
