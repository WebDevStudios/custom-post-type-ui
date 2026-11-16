/**
 * Newsletter signup: submit in place instead of reloading the page.
 *
 * Without JavaScript the form still posts to admin-post.php and redirects back.
 *
 * @since 1.19.4
 */

document.addEventListener( 'submit', ( event ) => {
	const form = event.target.closest( '.cptui-newsletter__form' );
	if ( ! form || ! window.fetch ) {
		return;
	}

	event.preventDefault();

	const wrapper = form.closest( '.cptui-newsletter' );
	const status = wrapper.querySelector( '.cptui-newsletter__status' );
	const button = form.querySelector( 'button[type="submit"]' );
	const label = button.textContent;

	button.disabled = true;
	button.textContent = form.dataset.sending;
	status.className = 'cptui-newsletter__status';
	status.textContent = '';

	fetch( form.dataset.ajaxUrl, {
		method: 'POST',
		credentials: 'same-origin',
		body: new window.FormData( form ),
	} )
		.then( ( response ) => response.json() )
		.then( ( result ) => {
			const data = result && result.data ? result.data : {};
			status.textContent = data.message || form.dataset.error;

			if ( result && result.success ) {
				status.classList.add( 'is-success' );
				form.remove();
				return;
			}

			status.classList.add( 'is-error' );
			button.disabled = false;
			button.textContent = label;
		} )
		.catch( () => {
			status.textContent = form.dataset.error;
			status.classList.add( 'is-error' );
			button.disabled = false;
			button.textContent = label;
		} );
} );
