<?php

add_action( 'wp_enqueue_scripts', 'my_enqueue_assets' );
include('login-editor.php');
include('updates.php');


//Use When Needed:
//remove_action('shutdown', 'wp_ob_end_flush_all', 1);  //Flush error
flush_rewrite_rules(); //Flush Rules
