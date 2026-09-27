( function () {
  'use strict';

  var navBar = document.querySelector( '.hue-nav-wraper' );
  var content = document.querySelector( '.site-content' );
  var navigation = document.querySelector( '#main-navigation' );
  var menuToggle = navigation ? navigation.querySelector( '.paper-hue-menu-toggle' ) : null;
  var position = navBar ? navBar.offsetTop : 0;

  function updateStickyNavigation() {
    if ( ! navBar || ! content ) {
      return;
    }

    if ( window.pageYOffset > position ) {
      navBar.classList.add( 'is-fixed' );
      content.classList.add( 'site-content-padding-top' );
    } else {
      navBar.classList.remove( 'is-fixed' );
      content.classList.remove( 'site-content-padding-top' );
    }
  }

  function setMenuOpen( open ) {
    if ( ! navigation || ! menuToggle ) {
      return;
    }

    navigation.classList.toggle( 'toggled', open );
    menuToggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
  }

  window.addEventListener( 'scroll', updateStickyNavigation, { passive: true } );

  if ( menuToggle ) {
    menuToggle.addEventListener( 'click', function () {
      setMenuOpen( 'true' !== menuToggle.getAttribute( 'aria-expanded' ) );
    } );

    navigation.addEventListener( 'keydown', function ( event ) {
      if ( 'Escape' === event.key && 'true' === menuToggle.getAttribute( 'aria-expanded' ) ) {
        setMenuOpen( false );
        menuToggle.focus();
      }
    } );
  }

  window.addEventListener( 'resize', function () {
    if ( window.matchMedia && window.matchMedia( '(min-width: 60em)' ).matches ) {
      setMenuOpen( false );
    }
  } );

  updateStickyNavigation();
}() );
