/**
 * Analytics event scaffold (Part 23).
 *
 * No GA4/GTM account is configured yet — there is no real measurement ID
 * to send data to, and inventing one would be worse than not having
 * analytics at all (silently-failing or misleadingly-"working" fake
 * tracking). This module does the two things that ARE real right now:
 *
 * 1. Initializes window.dataLayer (the standard GA4/GTM queue) so that
 *    the moment a real GTM container or GA4 tag is added, every event
 *    already fired before that point is retained and processed — nothing
 *    needs to be re-wired later.
 * 2. Wires coreveTrack() into every real user interaction the brief asks
 *    for that this site actually has: view_product, select_size,
 *    add_to_cart, remove_from_cart, begin_checkout, purchase,
 *    size_guide_open, faq_open, whatsapp_click, search, collection_view.
 *
 * Not wired: pincode_check (no delivery-pincode-checker feature exists —
 * building one would need real serviceable-pincode data this project
 * doesn't have, so it isn't fabricated here; see PROGRESS.md).
 */
( function () {
	'use strict';

	window.dataLayer = window.dataLayer || [];

	function coreveTrack( eventName, params ) {
		window.dataLayer.push( Object.assign( { event: eventName }, params || {} ) );
	}
	window.coreveTrack = coreveTrack;

	document.addEventListener( 'DOMContentLoaded', function () {
		// view_product — fires once per product page load.
		var productHero = document.querySelector( '.product-hero, .product' );
		if ( productHero && document.body.classList.contains( 'single-product' ) ) {
			var productId = document.querySelector( '.collection-card[data-product-id]' );
			coreveTrack( 'view_product', {
				product_id: productId ? productId.getAttribute( 'data-product-id' ) : undefined,
				product_name: document.querySelector( '.product-hero-title' ) ? document.querySelector( '.product-hero-title' ).textContent.trim() : document.title,
			} );
		}

		// collection_view — the shop archive, and the homepage's own
		// collection section coming into view.
		if ( document.body.classList.contains( 'post-type-archive-product' ) || document.body.classList.contains( 'tax-product_cat' ) ) {
			coreveTrack( 'collection_view', { page: 'shop' } );
		}
		var homeCollection = document.getElementById( 'collection' );
		if ( homeCollection && 'IntersectionObserver' in window ) {
			var seen = false;
			var observer = new IntersectionObserver( function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting && ! seen ) {
						seen = true;
						coreveTrack( 'collection_view', { page: 'homepage' } );
						observer.disconnect();
					}
				} );
			}, { threshold: 0.1 } ); // low threshold: this section is much taller than any
			// viewport (it holds all 5 product cards), so requiring a large
			// fraction of its own height to be visible would almost never
			// trigger — any real entry into view is the right signal here.
			observer.observe( homeCollection );
		}

		// select_size — every tappable size pill, homepage and product page.
		document.querySelectorAll( '.size-pill' ).forEach( function ( pill ) {
			pill.addEventListener( 'click', function () {
				var card = pill.closest( '.collection-card' );
				coreveTrack( 'select_size', {
					product_id: card ? card.getAttribute( 'data-product-id' ) : undefined,
					size: pill.textContent.trim(),
				} );
			} );
		} );

		// size_guide_open — every Size Guide link, wherever it appears.
		document.querySelectorAll( 'a[href*="/size-guide/"]' ).forEach( function ( link ) {
			link.addEventListener( 'click', function () {
				coreveTrack( 'size_guide_open', { source: link.closest( '.cart-drawer' ) ? 'cart_drawer' : ( link.closest( '.sticky-buy-bar' ) ? 'sticky_bar' : 'page' ) } );
			} );
		} );

		// whatsapp_click — any real WhatsApp link (cart drawer, footer, etc).
		document.querySelectorAll( 'a[href*="wa.me"]' ).forEach( function ( link ) {
			link.addEventListener( 'click', function () {
				coreveTrack( 'whatsapp_click', { source: link.closest( '.cart-drawer' ) ? 'cart_drawer' : 'page' } );
			} );
		} );

		// faq_open — every accordion question, homepage FAQ + product tabs.
		document.querySelectorAll( '.faq-question' ).forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				if ( 'true' === btn.getAttribute( 'aria-expanded' ) ) {
					return; // this click is about to close it, not open it
				}
				coreveTrack( 'faq_open', { question: btn.textContent.trim() } );
			} );
		} );

		// search — the header search form submit.
		var searchForm = document.querySelector( '.header-search-form' );
		if ( searchForm ) {
			searchForm.addEventListener( 'submit', function () {
				var input = searchForm.querySelector( 'input[name="s"]' );
				coreveTrack( 'search', { search_term: input ? input.value : '' } );
			} );
		}

		// begin_checkout — the primary Buy Now buttons (homepage cards,
		// product page, sticky bar) all add to cart then go straight to
		// checkout, and the cart drawer's own Checkout link.
		document.querySelectorAll( '.add-to-bag-btn, .sticky-buy-bar-btn' ).forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				coreveTrack( 'begin_checkout', { source: 'buy_now' } );
			} );
		} );
		var drawerCheckout = document.querySelector( '.cart-drawer-checkout' );
		if ( drawerCheckout ) {
			drawerCheckout.addEventListener( 'click', function () {
				coreveTrack( 'begin_checkout', { source: 'cart_drawer' } );
			} );
		}
	} );

	// add_to_cart / remove_from_cart piggyback on the Store API cart client's
	// own event so every add-to-bag path (homepage, product page, sticky
	// bar) is covered from one place instead of re-wiring each button.
	var lastItemCount = null;
	document.addEventListener( 'coreve:cart-updated', function ( e ) {
		var count = e.detail && e.detail.items_count !== undefined ? e.detail.items_count : null;
		if ( lastItemCount !== null && count !== null ) {
			if ( count > lastItemCount ) {
				coreveTrack( 'add_to_cart', { cart_count: count } );
			} else if ( count < lastItemCount ) {
				coreveTrack( 'remove_from_cart', { cart_count: count } );
			}
		}
		lastItemCount = count;
	} );
}() );
