( function () {
	'use strict';

	document.addEventListener( 'click', ( event ) => {
		if ( ! event.target.closest( '.stv-wishlist-open' ) ) {
			return;
		}

		const saved = JSON.parse( window.localStorage.getItem( 'stvWishlist' ) || '[]' );
		const existing = document.querySelector( '.stv-saved-panel' );

		if ( existing ) {
			existing.remove();
			return;
		}

		const panel = document.createElement( 'div' );
		const title = document.createElement( 'strong' );
		const body = document.createElement( 'div' );
		const action = document.createElement( 'a' );

		panel.className = 'stv-saved-panel';
		title.textContent = 'Saved products';
		body.className = 'stv-saved-panel-body';

		if ( saved.length ) {
			saved.slice( 0, 6 ).forEach( ( item ) => {
				const line = document.createElement( 'span' );
				line.textContent = item.name || item.id || item;
				body.appendChild( line );
			} );
		} else {
			const empty = document.createElement( 'span' );
			empty.textContent = 'No saved products yet';
			body.appendChild( empty );
		}

		action.href = window.stvAjax && window.stvAjax.shop ? window.stvAjax.shop : '/shop/';
		action.textContent = saved.length ? 'Keep shopping' : 'Browse products';
		panel.append( title, body, action );
		document.body.appendChild( panel );
	} );
}() );
