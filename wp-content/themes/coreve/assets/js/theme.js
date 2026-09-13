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
	} );
}());
