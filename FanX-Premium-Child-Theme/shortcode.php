<?php

//Page Title

function page_title_df( ){
   return get_the_title();
}
add_shortcode( 'page_title', 'page_title_df' );

/* ------- Line Break Shortcode --------*/
function line_break_df() {
	return '<br />';
}
add_shortcode( 'br', 'line_break_df' );
