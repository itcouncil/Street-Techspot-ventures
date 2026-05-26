( function () {
	'use strict';

	const normalizePhone = ( value ) => {
		const digits = value.replace( /\D/g, '' );

		if ( /^07\d{8}$/.test( digits ) ) {
			return `254${ digits.slice( 1 ) }`;
		}

		if ( /^2547\d{8}$/.test( digits ) ) {
			return digits;
		}

		return '';
	};

	const debounce = ( callback, wait ) => {
		let timer;

		return ( ...args ) => {
			window.clearTimeout( timer );
			timer = window.setTimeout( () => callback( ...args ), wait );
		};
	};

	const fetchWithRetry = async ( url, options, attempts = 2 ) => {
		try {
			return await fetch( url, options );
		} catch ( error ) {
			if ( attempts <= 1 ) {
				throw error;
			}

			await new Promise( ( resolve ) => window.setTimeout( resolve, 450 ) );
			return fetchWithRetry( url, options, attempts - 1 );
		}
	};

	const startStk = async ( phone, productId, status ) => {
		const form = new FormData();
		form.append( 'action', 'stv_quick_checkout' );
		form.append( 'nonce', window.stvAjax.nonce );
		form.append( 'phone', phone );
		form.append( 'product_id', productId );

		status.textContent = 'Sending M-Pesa prompt';

		const response = await fetchWithRetry( window.stvAjax.url, {
			method: 'POST',
			credentials: 'same-origin',
			body: form,
		}, 3 );
		const result = await response.json();

		status.textContent = result.success ? 'Check your phone' : result.data.message;

		if ( result.success && result.data.checkout_request_id ) {
			pollStatus( result.data.checkout_request_id, status );
		}
	};

	const pollStatus = async ( checkoutId, status, attempts = 24 ) => {
		if ( attempts <= 0 ) {
			status.textContent = 'Payment timed out. Try again.';
			return;
		}

		await new Promise( ( resolve ) => window.setTimeout( resolve, 2500 ) );

		const url = new URL( window.stvAjax.url );
		url.searchParams.set( 'action', 'stv_mpesa_status' );
		url.searchParams.set( 'nonce', window.stvAjax.nonce );
		url.searchParams.set( 'checkout_request_id', checkoutId );

		const response = await fetchWithRetry( url, { credentials: 'same-origin' } );
		const result = await response.json();

		if ( result.success && result.data.status === 'paid' ) {
			status.textContent = 'Payment confirmed';
			return;
		}

		if ( result.success && result.data.status === 'failed' ) {
			status.textContent = 'Payment failed or cancelled';
			return;
		}

		status.textContent = 'Waiting for confirmation';
		pollStatus( checkoutId, status, attempts - 1 );
	};

	document.addEventListener( 'click', ( event ) => {
		const shortcut = event.target.closest( '.stv-mpesa-shortcut' );

		if ( ! shortcut ) {
			return;
		}

		const phone = window.prompt( 'Safaricom phone number' );
		const normalized = normalizePhone( phone || '' );

		if ( ! normalized ) {
			return;
		}

		const status = document.createElement( 'p' );
		status.className = 'mt-2 text-xs text-[#00FFD1]';
		shortcut.parentElement.appendChild( status );
		startStk( normalized, shortcut.dataset.productId, status );
	} );

	document.querySelectorAll( '[data-stv-mpesa-phone]' ).forEach( ( input ) => {
		const status = document.querySelector( input.dataset.stvMpesaStatus );
		const productId = input.dataset.productId || '';
		const handler = debounce( () => {
			const phone = normalizePhone( input.value );

			if ( phone && status && productId ) {
				startStk( phone, productId, status );
			}
		}, 500 );

		input.addEventListener( 'input', handler );
	} );
}() );
