<?php

add_action( 'wp_enqueue_scripts', 'my_enqueue_assets' );
include('updates/updates.php');
include('white-label.php');
include('shortcode.php');

function my_enqueue_assets() {

    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
 wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}

// ---Remove Gutenberg Block Library CSS from loading on the frontend -->
function smartwp_remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
}
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );

// --- Remove Yoast Filter Dropdown --->
 function disable_yoast_seo_metabox( $post_types ) {
   unset( $post_types['guests'] );
   return $post_types;
 }
 add_filter( 'wpseo_accessible_post_types', 'disable_yoast_seo_metabox' );


 //USE AS NEEDED - Does not allow a Browser Cache
 header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
 header("Pragma: no-cache"); // HTTP 1.0
 header("Expires: Wed, 1 Jan 2020 00:00:00 GMT"); // Anytime in the past


//Use When Needed:
remove_action('shutdown', 'wp_ob_end_flush_all', 1);  //Flush error
flush_rewrite_rules(); //Flush Rules
