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

		// Header cart icon opens the drawer instead of navigating to /cart/.
		// The href stays a real link (progressive enhancement: still works
		// with JS disabled or before CoreveCartDrawer has loaded).
		var cartToggle = document.getElementById( 'cart-toggle' );
		if ( cartToggle ) {
			cartToggle.addEventListener( 'click', function ( e ) {
				if ( window.CoreveCartDrawer ) {
					e.preventDefault();
					window.CoreveCartDrawer.open();
				}
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

		// Section 6 / Product page: tappable size pills + Add to Bag / Buy Now.
		// Each card tracks its own selected variation; tapping Add to Bag
		// without a size shows an inline error instead of failing silently.
		document.querySelectorAll( '.collection-card' ).forEach( function ( card ) {
			var pills = card.querySelectorAll( '.size-pill' );
			var addBtn = card.querySelector( '.add-to-bag-btn' );
			var buyNowBtn = card.querySelector( '.buy-now-btn' );
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
					// Keep the sticky buy bar (if present) in sync with the main selector.
					var stickyBar = document.querySelector( '.sticky-buy-bar' );
					if ( stickyBar ) {
						stickyBar.setAttribute( 'data-variation-id', selectedVariationId );
					}
				} );
			} );

			function addToCart( onSuccess ) {
				if ( ! selectedVariationId ) {
					if ( errorEl ) {
						errorEl.hidden = false;
					}
					return;
				}
				if ( ! window.CoreveCart ) {
					return;
				}
				return window.CoreveCart.addItem( selectedVariationId, 1 ).then( onSuccess ).catch( function () {
					if ( errorEl ) {
						errorEl.textContent = 'Something went wrong. Please try again.';
						errorEl.hidden = false;
					}
				} );
			}

			if ( addBtn ) {
				addBtn.addEventListener( 'click', function () {
					addBtn.disabled = true;
					var originalText = addBtn.textContent;
					addBtn.textContent = 'Adding…';
					var result = addToCart( function () {
						addBtn.textContent = 'Added ✓';
						addBtn.setAttribute( 'data-state', 'added' );
						if ( window.CoreveCartDrawer ) {
							window.CoreveCartDrawer.open();
						}
						setTimeout( function () {
							addBtn.textContent = originalText;
							addBtn.removeAttribute( 'data-state' );
							addBtn.disabled = false;
						}, 1800 );
					} );
					// addToCart() returns undefined (not a promise) when no
					// size is selected yet — it already showed the inline
					// error, so just restore the button instead of chaining
					// .finally() onto a non-promise (that would throw and
					// leave the button permanently stuck disabled).
					if ( result ) {
						result.finally( function () {
							if ( addBtn.textContent === 'Adding…' ) {
								addBtn.textContent = originalText;
								addBtn.disabled = false;
							}
						} );
					} else {
						addBtn.textContent = originalText;
						addBtn.disabled = false;
					}
				} );
			}

			if ( buyNowBtn ) {
				buyNowBtn.addEventListener( 'click', function () {
					buyNowBtn.disabled = true;
					var originalText = buyNowBtn.textContent;
					buyNowBtn.textContent = 'Redirecting…';
					var result = addToCart( function () {
						window.location.href = '/checkout/';
					} );
					if ( result ) {
						result.finally( function () {
							if ( buyNowBtn.textContent === 'Redirecting…' ) {
								buyNowBtn.textContent = originalText;
								buyNowBtn.disabled = false;
							}
						} );
					} else {
						buyNowBtn.textContent = originalText;
						buyNowBtn.disabled = false;
					}
				} );
			}
		} );

		// FAQ / product-accordion toggle (shared pattern, used on the
		// homepage FAQ and the product page's below-the-fold sections).
		document.querySelectorAll( '.faq-question' ).forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				var item = btn.closest( '.faq-item' );
				var isOpen = item.classList.toggle( 'is-open' );
				btn.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
			} );
		} );

		// Sticky mobile buy bar: reveal once scrolled past the main product
		// purchase area; hides again near the top. Its own Add to Bag button
		// mirrors whatever size is currently selected above.
		var stickyBar = document.querySelector( '.sticky-buy-bar' );
		var productHero = document.querySelector( '.product-hero' );
		if ( stickyBar && productHero ) {
			// Recompute the hero's position on every scroll rather than
			// caching it once — WooCommerce's product gallery script
			// (zoom/flexslider) collapses a taller, pre-JS stacked-image
			// layout into the final compact gallery sometime after
			// DOMContentLoaded, so a cached height taken too early is stale.
			window.addEventListener( 'scroll', function () {
				var heroBottom = productHero.getBoundingClientRect().bottom + window.scrollY;
				if ( window.scrollY > heroBottom ) {
					stickyBar.hidden = false;
				} else {
					stickyBar.hidden = true;
				}
			}, { passive: true } );

			var stickyBtn = stickyBar.querySelector( '.sticky-buy-bar-btn' );
			if ( stickyBtn ) {
				stickyBtn.addEventListener( 'click', function () {
					var variationId = stickyBar.getAttribute( 'data-variation-id' );
					if ( ! variationId ) {
						document.querySelector( '.product-hero .size-error' ).hidden = false;
						productHero.scrollIntoView( { behavior: prefersReducedMotion.matches ? 'auto' : 'smooth' } );
						return;
					}
					window.CoreveCart.addItem( variationId, 1 ).then( function () {
						stickyBtn.textContent = 'Added ✓';
						if ( window.CoreveCartDrawer ) {
							window.CoreveCartDrawer.open();
						}
						setTimeout( function () { stickyBtn.textContent = 'Add to Bag'; }, 1800 );
					} );
				} );
			}
		}
	} );
}());
