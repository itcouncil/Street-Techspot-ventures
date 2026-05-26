( function () {
	'use strict';

	const idle = window.requestIdleCallback || ( ( callback ) => window.setTimeout( callback, 1 ) );
	const debounce = ( callback, wait ) => {
		let timer;
		return ( ...args ) => {
			window.clearTimeout( timer );
			timer = window.setTimeout( () => callback( ...args ), wait );
		};
	};

	const liveInput = document.querySelector( '#stv-live-search' );
	const results = document.querySelector( '.stv-live-results' );

	if ( liveInput && results && window.stvAjax ) {
		liveInput.addEventListener( 'input', debounce( async () => {
			const term = liveInput.value.trim();

			if ( term.length < 2 ) {
				results.classList.add( 'hidden' );
				return;
			}

			const url = new URL( window.stvAjax.url );
			url.searchParams.set( 'action', 'stv_live_search' );
			url.searchParams.set( 'nonce', window.stvAjax.nonce );
			url.searchParams.set( 'term', term );

			const response = await fetch( url, { credentials: 'same-origin' } );
			const payload = await response.json();
			const items = payload.success ? payload.data.results : [];

			results.textContent = '';
			items.forEach( ( item ) => {
				const link = document.createElement( 'a' );
				const media = document.createElement( 'img' );
				const body = document.createElement( 'span' );
				const name = document.createElement( 'span' );
				const meta = document.createElement( 'span' );

				link.className = 'grid grid-cols-[40px_1fr] gap-3 rounded-xl px-3 py-2 text-sm text-white hover:bg-white/5';
				link.href = item.url;
				media.className = 'h-10 w-10 rounded-lg object-cover';
				media.alt = '';
				media.loading = 'lazy';
				media.src = item.image || '';
				body.className = 'min-w-0';
				name.className = 'block font-semibold';
				name.textContent = item.name;
				meta.className = 'block text-xs text-[#00FFD1]';
				meta.textContent = `${ item.price } | ${ item.stock }`;
				body.append( name, meta );
				link.append( media, body );
				results.appendChild( link );
			} );
			results.classList.toggle( 'hidden', ! items.length );
		}, 250 ) );
	}

	document.addEventListener( 'click', ( event ) => {
		const trigger = event.target.closest( '.stv-spec-trigger' );
		const card = event.target.closest( '.stv-product-card' );

		if ( ! trigger || ! card || card.dataset.specsLoaded || ! window.stvAjax ) {
			return;
		}

		idle( async () => {
			const url = new URL( window.stvAjax.url );
			url.searchParams.set( 'action', 'stv_product_specs' );
			url.searchParams.set( 'nonce', window.stvAjax.nonce );
			url.searchParams.set( 'product_id', card.dataset.productId );

			const response = await fetch( url, { credentials: 'same-origin' } );
			const result = await response.json();
			const specs = card.querySelector( '.stv-spec-drawer' );
			specs.textContent = result.success && result.data.specs.length ? result.data.specs.join( ' | ' ) : 'Specs loading soon';
			specs.classList.remove( 'hidden' );
			trigger.setAttribute( 'aria-expanded', 'true' );
			card.dataset.specsLoaded = 'true';
		} );
	} );
}() );
