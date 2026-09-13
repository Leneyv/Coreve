/**
 * Minimal WooCommerce Store API cart client.
 *
 * Wraps /wp-json/wc/store/v1/cart so "Add to Bag" never reloads the page.
 * Handles Store API's rotating Nonce header (a fresh one comes back on
 * every request and must be used on the next one).
 *
 * Usage: window.CoreveCart.addItem(productId, quantity, variationAttrs)
 * Listens for the result via the "coreve:cart-updated" custom event on
 * document, carrying the full Store API cart object in event.detail.
 */
( function () {
	'use strict';

	var API_BASE = '/wp-json/wc/store/v1';
	var nonce = null;

	function withNonceHeaders( extra ) {
		var headers = Object.assign( { 'Content-Type': 'application/json' }, extra || {} );
		if ( nonce ) {
			headers.Nonce = nonce;
		}
		return headers;
	}

	function captureNonce( response ) {
		var fresh = response.headers.get( 'Nonce' );
		if ( fresh ) {
			nonce = fresh;
		}
		return response;
	}

	function handleResponse( response ) {
		captureNonce( response );
		return response.json().then( function ( data ) {
			if ( ! response.ok ) {
				var error = new Error( data.message || 'Cart request failed' );
				error.code = data.code;
				error.data = data.data;
				throw error;
			}
			document.dispatchEvent( new CustomEvent( 'coreve:cart-updated', { detail: data } ) );
			return data;
		} );
	}

	function ensureNonce() {
		if ( nonce ) {
			return Promise.resolve( nonce );
		}
		return fetch( API_BASE + '/cart', { credentials: 'same-origin' } )
			.then( function ( response ) {
				captureNonce( response );
				return nonce;
			} );
	}

	function getCart() {
		return fetch( API_BASE + '/cart', { credentials: 'same-origin' } ).then( handleResponse );
	}

	/**
	 * @param {number} productId Parent product ID (variation's parent for variable products).
	 * @param {number} quantity
	 * @param {Object} [variation] e.g. { pa_size: '38' } — attribute taxonomy name (with pa_ prefix) => term slug/name.
	 */
	function addItem( productId, quantity, variation ) {
		var body = { id: productId, quantity: quantity || 1 };
		if ( variation && Object.keys( variation ).length ) {
			body.variation = Object.keys( variation ).map( function ( attribute ) {
				return { attribute: attribute, value: variation[ attribute ] };
			} );
		}
		return ensureNonce().then( function () {
			return fetch( API_BASE + '/cart/add-item', {
				method: 'POST',
				credentials: 'same-origin',
				headers: withNonceHeaders(),
				body: JSON.stringify( body ),
			} ).then( handleResponse );
		} );
	}

	function updateItem( key, quantity ) {
		return ensureNonce().then( function () {
			return fetch( API_BASE + '/cart/update-item', {
				method: 'POST',
				credentials: 'same-origin',
				headers: withNonceHeaders(),
				body: JSON.stringify( { key: key, quantity: quantity } ),
			} ).then( handleResponse );
		} );
	}

	function removeItem( key ) {
		return ensureNonce().then( function () {
			return fetch( API_BASE + '/cart/remove-item', {
				method: 'POST',
				credentials: 'same-origin',
				headers: withNonceHeaders(),
				body: JSON.stringify( { key: key } ),
			} ).then( handleResponse );
		} );
	}

	window.CoreveCart = {
		getCart: getCart,
		addItem: addItem,
		updateItem: updateItem,
		removeItem: removeItem,
	};

	// Keep the header cart-count badge in sync wherever the cart changes.
	document.addEventListener( 'coreve:cart-updated', function ( e ) {
		var countEls = document.querySelectorAll( '.cart-count' );
		var count = e.detail && e.detail.items_count !== undefined
			? e.detail.items_count
			: ( e.detail.items ? e.detail.items.reduce( function ( sum, i ) { return sum + i.quantity; }, 0 ) : 0 );
		countEls.forEach( function ( el ) {
			el.textContent = count;
		} );
	} );

	// Sync the header count on initial load too (covers cart state from a
	// previous visit/session that the server-rendered count doesn't reflect
	// if the page was cached).
	document.addEventListener( 'DOMContentLoaded', function () {
		getCart().catch( function () { /* non-fatal: keep server-rendered count */ } );
	} );
}() );
