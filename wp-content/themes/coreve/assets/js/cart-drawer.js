/**
 * Cart drawer (Part 10) — slides in on Add to Bag, no page reload.
 * Renders from the WooCommerce Store API cart object (see
 * store-api-cart.js) and keeps itself in sync via the "coreve:cart-updated"
 * event, but only auto-*opens* when explicitly told to (initial page-load
 * cart sync should not pop the drawer open unprompted).
 */
( function () {
	'use strict';

	var drawer = document.getElementById( 'cart-drawer' );
	if ( ! drawer ) {
		return;
	}

	var panel = drawer.querySelector( '.cart-drawer-panel' );
	var itemsEl = drawer.querySelector( '.cart-drawer-items' );
	var subtotalEl = drawer.querySelector( '.cart-drawer-subtotal-value' );
	var closeBtn = drawer.querySelector( '.cart-drawer-close' );
	var backdrop = drawer.querySelector( '.cart-drawer-backdrop' );
	var lastFocusedEl = null;

	function formatMoney( minorUnitsString, minorUnit, symbol ) {
		var value = parseInt( minorUnitsString, 10 ) / Math.pow( 10, minorUnit );
		return symbol + value.toFixed( minorUnit ).replace( /\B(?=(\d{3})+(?!\d))/g, ',' );
	}

	function renderItem( item ) {
		var image = item.images && item.images[ 0 ] ? item.images[ 0 ].thumbnail : '';
		var sizeText = '';
		if ( item.variation && item.variation.length ) {
			sizeText = item.variation.map( function ( v ) { return v.attribute + ' ' + v.value; } ).join( ', ' );
		}
		var price = formatMoney( item.prices.price, item.prices.currency_minor_unit, item.prices.currency_symbol );

		var row = document.createElement( 'div' );
		row.className = 'cart-drawer-item';
		row.innerHTML =
			'<img src="' + image + '" alt="" class="cart-drawer-item-image">' +
			'<div class="cart-drawer-item-info">' +
				'<span class="cart-drawer-item-name">' + item.name + '</span>' +
				( sizeText ? '<span class="cart-drawer-item-size">' + sizeText + '</span>' : '' ) +
				'<div class="cart-drawer-item-qty">' +
					'<button type="button" class="qty-btn qty-decrease" aria-label="Decrease quantity">&minus;</button>' +
					'<span class="qty-value">' + item.quantity + '</span>' +
					'<button type="button" class="qty-btn qty-increase" aria-label="Increase quantity">+</button>' +
					'<button type="button" class="cart-drawer-item-remove" aria-label="Remove item">Remove</button>' +
				'</div>' +
			'</div>' +
			'<span class="cart-drawer-item-price">' + price + '</span>';

		row.querySelector( '.qty-decrease' ).addEventListener( 'click', function () {
			var newQty = item.quantity - 1;
			if ( newQty < 1 ) {
				window.CoreveCart.removeItem( item.key );
			} else {
				window.CoreveCart.updateItem( item.key, newQty );
			}
		} );
		row.querySelector( '.qty-increase' ).addEventListener( 'click', function () {
			window.CoreveCart.updateItem( item.key, item.quantity + 1 );
		} );
		row.querySelector( '.cart-drawer-item-remove' ).addEventListener( 'click', function () {
			window.CoreveCart.removeItem( item.key );
		} );

		return row;
	}

	function render( cart ) {
		itemsEl.innerHTML = '';
		if ( ! cart.items || ! cart.items.length ) {
			itemsEl.innerHTML = '<p class="cart-drawer-empty">Your bag is empty.</p>';
		} else {
			cart.items.forEach( function ( item ) {
				itemsEl.appendChild( renderItem( item ) );
			} );
		}
		if ( cart.totals ) {
			subtotalEl.textContent = formatMoney( cart.totals.total_price, cart.totals.currency_minor_unit, cart.totals.currency_symbol );
		}
	}

	function open() {
		lastFocusedEl = document.activeElement;
		drawer.hidden = false;
		document.body.classList.add( 'cart-drawer-open' );
		requestAnimationFrame( function () { drawer.classList.add( 'is-active' ); } );
		if ( closeBtn ) {
			closeBtn.focus();
		}
	}

	function close() {
		drawer.classList.remove( 'is-active' );
		document.body.classList.remove( 'cart-drawer-open' );
		setTimeout( function () { drawer.hidden = true; }, 300 );
		if ( lastFocusedEl ) {
			lastFocusedEl.focus();
		}
	}

	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', close );
	}
	if ( backdrop ) {
		backdrop.addEventListener( 'click', close );
	}
	document.addEventListener( 'keydown', function ( e ) {
		if ( 'Escape' === e.key && ! drawer.hidden ) {
			close();
		}
	} );

	// Basic focus trap while open.
	drawer.addEventListener( 'keydown', function ( e ) {
		if ( 'Tab' !== e.key || drawer.hidden ) {
			return;
		}
		var focusable = panel.querySelectorAll( 'button, a[href], input' );
		if ( ! focusable.length ) {
			return;
		}
		var first = focusable[ 0 ];
		var last = focusable[ focusable.length - 1 ];
		if ( e.shiftKey && document.activeElement === first ) {
			e.preventDefault();
			last.focus();
		} else if ( ! e.shiftKey && document.activeElement === last ) {
			e.preventDefault();
			first.focus();
		}
	} );

	document.addEventListener( 'coreve:cart-updated', function ( e ) {
		render( e.detail );
	} );

	window.CoreveCartDrawer = { open: open, close: close };
}() );
