<?php

add_action('wp_dashboard_setup', 'update_dashboard_widgets');

function update_dashboard_widgets() {
global $wp_meta_boxes;

wp_add_dashboard_widget('update_widget', 'FanX Theme Updates', 'custom_dashboard_updates');
}

function custom_dashboard_updates() {
echo 'updates go here';
}

//Use When Needed:
//remove_action('shutdown', 'wp_ob_end_flush_all', 1);  //Flush error
flush_rewrite_rules(); //Flush Rules
