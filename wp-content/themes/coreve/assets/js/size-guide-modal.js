/**
 * Size Guide modal — opens in place from any "Size Guide" link sitewide
 * (product cards, product page, sticky buy bar, cart drawer) instead of
 * navigating away. The standalone /size-guide/ page still exists for
 * direct links, search, and SEO — this modal is a convenience layer on
 * top of it, not a replacement.
 */
( function () {
	'use strict';

	var modal = document.getElementById( 'size-guide-modal' );
	if ( ! modal ) {
		return;
	}

	var panel = modal.querySelector( '.size-guide-modal-panel' );
	var closeBtn = modal.querySelector( '.size-guide-modal-close' );
	var backdrop = modal.querySelector( '.size-guide-modal-backdrop' );
	var lastFocusedEl = null;

	function open() {
		lastFocusedEl = document.activeElement;
		modal.hidden = false;
		document.body.classList.add( 'size-guide-modal-open' );
		requestAnimationFrame( function () { modal.classList.add( 'is-active' ); } );
		if ( closeBtn ) {
			closeBtn.focus();
		}
		// size_guide_open is already tracked generically for every
		// a[href*="/size-guide"] click in analytics.js — no need to
		// duplicate that here.
	}

	function close() {
		modal.classList.remove( 'is-active' );
		document.body.classList.remove( 'size-guide-modal-open' );
		setTimeout( function () { modal.hidden = true; }, 300 );
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
		if ( 'Escape' === e.key && ! modal.hidden ) {
			close();
		}
	} );

	// Basic focus trap while open.
	modal.addEventListener( 'keydown', function ( e ) {
		if ( 'Tab' !== e.key || modal.hidden ) {
			return;
		}
		var focusable = panel.querySelectorAll( 'button, a[href]' );
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

	// Intercept every "Size Guide" link sitewide (matches /size-guide/,
	// with or without trailing slash/query) and open the modal instead of
	// navigating away. Progressive enhancement: the real href still works
	// if JS fails to load.
	document.addEventListener( 'DOMContentLoaded', function () {
		document.querySelectorAll( 'a[href*="/size-guide"]' ).forEach( function ( link ) {
			// Don't intercept a link that's inside the modal itself (none
			// currently, but keeps this safe if one is ever added).
			if ( modal.contains( link ) ) {
				return;
			}
			link.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				open();
			} );
		} );
	} );

	window.CoreveSizeGuideModal = { open: open, close: close };
}() );
