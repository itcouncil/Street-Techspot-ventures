( function () {
	'use strict';

	document.documentElement.classList.add( 'stv-js' );

	const reveal = () => {
		document.querySelectorAll( '.stv-card, .stv-product-card, .stv-device-card' ).forEach( ( element ) => {
			element.style.willChange = 'transform';
		} );
	};

	if ( 'requestIdleCallback' in window ) {
		window.requestIdleCallback( reveal );
	} else {
		window.setTimeout( reveal, 120 );
	}

	const product = document.querySelector( '.stv-product-card[data-product-id]' );
	if ( product ) {
		const viewed = JSON.parse( window.localStorage.getItem( 'stvRecentlyViewed' ) || '[]' );
		const next = [ product.dataset.productId, ...viewed.filter( ( id ) => id !== product.dataset.productId ) ].slice( 0, 12 );
		window.localStorage.setItem( 'stvRecentlyViewed', JSON.stringify( next ) );
	}

	document.addEventListener( 'click', ( event ) => {
		const wishlistButton = event.target.closest( '[data-stv-wishlist]' );

		if ( ! wishlistButton ) {
			return;
		}

		const productId = wishlistButton.dataset.stvWishlist;
		const productName = wishlistButton.dataset.productName || productId;
		const saved = JSON.parse( window.localStorage.getItem( 'stvWishlist' ) || '[]' );
		const next = [
			{ id: productId, name: productName },
			...saved.filter( ( item ) => String( item.id || item ) !== productId ),
		].slice( 0, 30 );
		window.localStorage.setItem( 'stvWishlist', JSON.stringify( next ) );
		wishlistButton.textContent = 'Saved';
		wishlistButton.setAttribute( 'aria-pressed', 'true' );
	} );
}() );
