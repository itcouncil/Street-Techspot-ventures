( function () {
	'use strict';

	const post = async ( action, data ) => {
		const form = new FormData();
		form.append( 'action', action );
		form.append( 'nonce', window.stvAjax.nonce );

		Object.entries( data ).forEach( ( [ key, value ] ) => form.append( key, value ) );

		const response = await fetch( window.stvAjax.url, {
			method: 'POST',
			credentials: 'same-origin',
			body: form,
		} );

		return response.json();
	};

	document.addEventListener( 'click', async ( event ) => {
		const button = event.target.closest( '.stv-add-to-cart' );

		if ( ! button || ! window.stvAjax ) {
			return;
		}

		button.disabled = true;
		button.dataset.originalText = button.textContent;
		button.textContent = 'Adding';

		try {
			const result = await post( 'stv_add_to_cart', {
				product_id: button.dataset.productId,
				quantity: 1,
			} );

			button.textContent = result.success ? 'Added' : 'Retry';
		} catch ( error ) {
			button.textContent = 'Retry';
		} finally {
			window.setTimeout( () => {
				button.disabled = false;
				button.textContent = button.dataset.originalText || 'Add';
			}, 1400 );
		}
	} );
}() );
