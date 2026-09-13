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

		// Stagger-reveal grids on scroll (skipped entirely under reduced motion)
		if ( prefersReducedMotion.matches || typeof gsap === 'undefined' ) {
			return;
		}

		var grids = document.querySelectorAll( '.product-grid, .testimonial-grid, .feature-triptych, .process-grid' );
		if ( ! grids.length ) {
			return;
		}

		grids.forEach( function ( grid ) {
			var items = grid.children;
			if ( ! items.length ) {
				return;
			}
			gsap.set( items, { opacity: 0, y: 16, scale: 0.92 } );
		} );

		var observer = new IntersectionObserver( function ( entries, obs ) {
			entries.forEach( function ( entry ) {
				if ( ! entry.isIntersecting ) {
					return;
				}
				gsap.to( entry.target.children, {
					opacity: 1,
					y: 0,
					scale: 1,
					duration: 0.4,
					stagger: { each: 0.06, from: 'start', grid: 'auto' },
					ease: 'back.out(1.4)',
				} );
				obs.unobserve( entry.target );
			} );
		}, { threshold: 0.15 } );

		grids.forEach( function ( grid ) {
			observer.observe( grid );
		} );
	} );
}());
