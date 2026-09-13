(function () {
	'use strict';

	var prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );

	document.addEventListener( 'DOMContentLoaded', function () {

		// Mobile nav toggle
		var toggle = document.querySelector( '.mobile-menu-toggle' );
		var nav = document.getElementById( 'site-navigation' );
		if ( toggle && nav ) {
			toggle.setAttribute( 'aria-expanded', 'false' );
			toggle.addEventListener( 'click', function () {
				var isOpen = nav.classList.toggle( 'is-open' );
				toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
			} );
		}

		// Pause decorative background video under reduced motion
		document.querySelectorAll( 'video[autoplay]' ).forEach( function ( video ) {
			if ( prefersReducedMotion.matches ) {
				video.removeAttribute( 'autoplay' );
				video.pause();
			}
		} );

		// Intelligent sticky header: hide on scroll down (past the trust bar),
		// reveal on scroll up, so it doesn't eat screen space while reading.
		var header = document.getElementById( 'masthead' );
		if ( header ) {
			var lastScrollY = window.scrollY;
			var ticking = false;
			window.addEventListener( 'scroll', function () {
				if ( ticking ) {
					return;
				}
				ticking = true;
				requestAnimationFrame( function () {
					var currentY = window.scrollY;
					if ( currentY > lastScrollY && currentY > 120 ) {
						header.classList.add( 'is-hidden' );
					} else {
						header.classList.remove( 'is-hidden' );
					}
					lastScrollY = currentY;
					ticking = false;
				} );
			}, { passive: true } );
		}

		// Section 6 — Product Collection: tappable size pills + Add to Bag.
		// Each card tracks its own selected variation; tapping Add to Bag
		// without a size shows an inline error instead of failing silently.
		document.querySelectorAll( '.collection-card' ).forEach( function ( card ) {
			var pills = card.querySelectorAll( '.size-pill' );
			var addBtn = card.querySelector( '.add-to-bag-btn' );
			var errorEl = card.querySelector( '.size-error' );
			var selectedVariationId = null;

			pills.forEach( function ( pill ) {
				pill.addEventListener( 'click', function () {
					pills.forEach( function ( p ) { p.setAttribute( 'aria-pressed', 'false' ); } );
					pill.setAttribute( 'aria-pressed', 'true' );
					selectedVariationId = pill.getAttribute( 'data-variation-id' );
					if ( errorEl ) {
						errorEl.hidden = true;
					}
				} );
			} );

			if ( addBtn ) {
				addBtn.addEventListener( 'click', function () {
					if ( ! selectedVariationId ) {
						if ( errorEl ) {
							errorEl.hidden = false;
						}
						return;
					}
					if ( ! window.CoreveCart ) {
						return;
					}
					addBtn.disabled = true;
					var originalText = addBtn.textContent;
					addBtn.textContent = 'Adding…';
					window.CoreveCart.addItem( selectedVariationId, 1 )
						.then( function () {
							addBtn.textContent = 'Added ✓';
							addBtn.setAttribute( 'data-state', 'added' );
							setTimeout( function () {
								addBtn.textContent = originalText;
								addBtn.removeAttribute( 'data-state' );
								addBtn.disabled = false;
							}, 1800 );
						} )
						.catch( function () {
							addBtn.textContent = originalText;
							addBtn.disabled = false;
							if ( errorEl ) {
								errorEl.textContent = 'Something went wrong. Please try again.';
								errorEl.hidden = false;
							}
						} );
				} );
			}
		} );
	} );
}());
