<?php

/*
Plugin Name: WP Bakery Page Builder Focus Image
Plugin URI: http://24hr.se
Description: 
Version: 0.0.4
Author: Camilo Tapia <camilo.tapia@24hr.se>
*/

// don't load directly
if ( !defined( 'ABSPATH' ) ) {

    die( '-1' );

} else {

    $dir = dirname( __FILE__ );

    define('PageBuilderFocusImageVERSION', '0.5.4');

    require_once( $dir . '/lib/focus-image.php');

    function page_builder_focus_image_init() {

        // Init or use instance of the manager.
        $dir = dirname( __FILE__ );

        if(class_exists( 'PageBuilderFocusImage' )){
            global $page_builder_focus_image;
            $page_builder_focus_image = new PageBuilderFocusImage($dir, PageBuilderFocusImageVERSION);
        }

    }

    add_action( 'init', 'page_builder_focus_image_init');

}
