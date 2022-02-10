<?php

//Admin Bar (Top)
function fanx_admin_bar_render() {
global $wp_admin_bar;

// Remove an existing link
$wp_admin_bar->remove_menu('wp-admin-bar-wpengine_adminbar');

//CloudFlare Top Level Link
$cloudflareTAB = '/wp-admin/options-general.php?page=cloudflare#/home';
$wp_admin_bar->add_menu( array(
'parent' => false,
'id' => 'cloudflare',
'title' => __('Cloudflare'),
'href' => $cloudflareTAB
));

// Submenu Items

$cfsettingsTAB = '/wp-admin/options-general.php?page=cloudflare#/more-settings';
$wp_admin_bar->add_menu(array(
'parent' => 'cloudflare',
'id' => 'cf_settings',
'title' => __('Cloudflare Settings (Dev Mode)'),
'href' => $cfsettingsTAB
));
}



add_action( 'wp_before_admin_bar_render', 'fanx_admin_bar_render' );


//Footer Text
function et_change_admin_footer_text () {
 return __('Powered by Dan Farr Productions. Premium Child Theme designed & coded with ♥ by <a href="https://www.dancingfraxinus.com/">Liz Moore</a>.');
}
add_filter( 'admin_footer_text', 'et_change_admin_footer_text' );


//Use When Needed:
remove_action('shutdown', 'wp_ob_end_flush_all', 1);  //Flush error
flush_rewrite_rules(); //Flush Rules
