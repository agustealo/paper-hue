<?php 
function get_breadcrumb() {
    if (!is_home()) : echo '<a href="' . get_option('home') . '">' .'Home' . "</a>"; else: echo 'Home'; endif;
   
    if (is_category() || is_single()) {
    
    /* Category or Post*/    
    echo "  »  ";
    the_category(' » ');
    if (is_single()) {
        /* Archive */
        echo "  »  ";
        if ( is_day() ) {
            printf( __( '%s', 'text_domain' ), get_the_date() );
        } elseif ( is_month() ) {
            printf( __( '%s', 'text_domain' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'text_domain' ) ) );
        } elseif ( is_year() ) {
            printf( __( '%s', 'text_domain' ), get_the_date( _x( 'Y', 'yearly archives date format', 'text_domain' ) ) );
        } else {
            _e( 'Blog Archives', 'text_domain' );
        }
    }
    } elseif
    (is_page()) {
    
        /* Page */        
        echo "  »  ";
        echo the_title();
    } elseif
    (is_search()) {
        
        /* Search */        
        echo "  »  Search Results for… ";
        echo '"';
        echo the_search_query();
        echo '"';
    } elseif
    (is_archive() || is_single()){
    
        /* Archive */
        echo "  »  ";
        if ( is_day() ) {
            printf( __( '%s', 'text_domain' ), get_the_date() );
        } elseif ( is_month() ) {
            printf( __( '%s', 'text_domain' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'text_domain' ) ) );
        } elseif ( is_year() ) {
            printf( __( '%s', 'text_domain' ), get_the_date( _x( 'Y', 'yearly archives date format', 'text_domain' ) ) );
        } else {
            _e( 'Blog Archives', 'text_domain' );
        }
    }
    } // End breadcrumb