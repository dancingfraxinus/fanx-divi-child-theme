<?php

//Admin bar menu greeting text
add_action( 'admin_bar_menu', 'et_custom_admin_bar_greeting_text' );
function et_custom_admin_bar_greeting_text( $wp_admin_bar ) {
  // Verify current user ID and name
  $user_data         = wp_get_current_user();
  $user_display_name = isset( $user_data->display_name ) ? $user_data->display_name : false;
  $user_id           = isset( $user_data->ID ) ? (int) $user_data->ID : 0;
  if ( ! $user_id || ! $user_display_name ) {
    return;
  }  // Get user avatar
  $user_avatar = get_avatar( $user_id, 26 );  // Set new greeting text "Hello" and regenerate menu text
  // translators: %s: Current user's display name
  $my_account_text = sprintf(
    __( 'Hello, %s' ),
    '<span class="display-name">' . esc_html( $user_data->display_name ) . '</span>'
  );  // Override existing my account text with the new one
  $wp_admin_bar->add_node(
    array(
      'id'    => 'my-account',
      'title' => $my_account_text . $user_avatar,
    )
  );
}
add_action( 'admin_bar_menu', 'et_custom_admin_bar_greeting_text' );


//Footer Text
function et_change_admin_footer_text () {
 return __('Powered by Dan Farr Productions. Premium Child Theme designed & coded with ♥ by <a href="https://www.dancingfraxinus.com/">Liz Moore</a>.');
}
add_filter( 'admin_footer_text', 'et_change_admin_footer_text' );



//Admin Menu Bar Add & Remove

function et_custom_admin_top_left_logo() {
  $logo_url   = get_stylesheet_directory_uri() . '/images/FanXuberminiLogo.png';
  $logo_style = '#wp-admin-bar-wp-logo > .ab-item { background-image: url(' . esc_url( $logo_url ) . '); background-size: 28px; background-repeat: no-repeat; background-position: center; } #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before { content: none; }';
  wp_add_inline_style( 'admin-bar', $logo_style );
}
add_action( 'admin_enqueue_scripts', 'et_custom_admin_top_left_logo' );

//Admin Menu Links
update_option( 'link_manager_enabled', 0 );

add_action( 'admin_init', 'my_remove_admin_menus' ); //Side Admin Bar
function my_remove_admin_menus() {
    remove_menu_page( 'edit-comments.php' );
	remove_menu_page ( 'feedback.php' );
}

function my_admin_bar_render() { 
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
	$wp_admin_bar->remove_menu('stats');
}
add_action( 'wp_before_admin_bar_render', 'my_admin_bar_render' ); //Top Menu Bar

	
// Use when needed: flush_rewrite_rules();
?>