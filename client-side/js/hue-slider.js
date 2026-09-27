( function () {
	'use strict';

	function toBoolean( value ) {
		return '1' === value || 'true' === value;
	}

	function initSlider( root ) {
		var slides = Array.prototype.slice.call( root.querySelectorAll( '.hue-slide-item' ) );
		var dots = Array.prototype.slice.call( root.querySelectorAll( '.dot' ) );
		var previous = root.querySelector( '.prev' );
		var next = root.querySelector( '.next' );
		var counter = root.querySelector( '.counter' );
		var autoplay = toBoolean( root.getAttribute( 'data-autoplay' ) );
		var pauseOnInteraction = toBoolean( root.getAttribute( 'data-pause' ) );
		var interval = parseInt( root.getAttribute( 'data-interval' ), 10 ) || 4000;
		var reducedMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
		var currentIndex = 0;
		var timer = null;

		if ( ! slides.length ) {
			return;
		}

		function normalizeIndex( index ) {
			if ( index < 0 ) {
				return slides.length - 1;
			}

			if ( index >= slides.length ) {
				return 0;
			}

			return index;
		}

		function render() {
			slides.forEach( function ( slide, index ) {
				var active = index === currentIndex;
				slide.hidden = ! active;
				slide.setAttribute( 'aria-hidden', active ? 'false' : 'true' );
			} );

			dots.forEach( function ( dot, index ) {
				var active = index === currentIndex;
				dot.classList.toggle( 'active', active );
				dot.setAttribute( 'aria-current', active ? 'true' : 'false' );
			} );

			if ( counter ) {
				counter.textContent = ( currentIndex + 1 ) + ' / ' + slides.length;
			}
		}

		function stopTimer() {
			if ( timer ) {
				window.clearTimeout( timer );
				timer = null;
			}
		}

		function schedule() {
			stopTimer();

			if ( ! autoplay || reducedMotion || slides.length < 2 ) {
				return;
			}

			timer = window.setTimeout( function () {
				currentIndex = normalizeIndex( currentIndex + 1 );
				render();
				schedule();
			}, interval );
		}

		function goTo( index ) {
			currentIndex = normalizeIndex( index );
			render();
			schedule();
		}

		if ( previous ) {
			previous.addEventListener( 'click', function () {
				goTo( currentIndex - 1 );
			} );
		}

		if ( next ) {
			next.addEventListener( 'click', function () {
				goTo( currentIndex + 1 );
			} );
		}

		dots.forEach( function ( dot, index ) {
			dot.addEventListener( 'click', function () {
				goTo( index );
			} );
		} );

		root.addEventListener( 'keydown', function ( event ) {
			if ( 'ArrowLeft' === event.key ) {
				event.preventDefault();
				goTo( currentIndex - 1 );
			} else if ( 'ArrowRight' === event.key ) {
				event.preventDefault();
				goTo( currentIndex + 1 );
			}
		} );

		if ( pauseOnInteraction ) {
			root.addEventListener( 'mouseenter', stopTimer );
			root.addEventListener( 'mouseleave', schedule );
			root.addEventListener( 'focusin', stopTimer );
			root.addEventListener( 'focusout', function ( event ) {
				if ( ! root.contains( event.relatedTarget ) ) {
					schedule();
				}
			} );
		}

		if ( window.matchMedia ) {
			var mediaQuery = window.matchMedia( '(prefers-reduced-motion: reduce)' );
			if ( 'function' === typeof mediaQuery.addEventListener ) {
				mediaQuery.addEventListener( 'change', function ( event ) {
					reducedMotion = event.matches;
					schedule();
				} );
			}
		}

		render();
		schedule();
	}

	function boot() {
		Array.prototype.forEach.call(
			document.querySelectorAll( '[data-paper-hue-slider="1"]' ),
			initSlider
		);
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', boot );
	} else {
		boot();
	}
}() );
