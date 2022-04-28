<?php

// -- [page_title] -->

function page_title_df( ){
   return get_the_title();
}
add_shortcode( 'page_title', 'page_title_df' );

// -- [page_content] -->

function page_content_df( ){
   return get_the_content();
}
add_shortcode( 'page_content', 'page_content_df' );

// -- [tax_venue] -->

function tax_venue_df($atts){
   return get_the_terms( $post, 'venue' );
}
add_shortcode( 'tax_venue', 'tax_venue_df' );



// -- [br] -->
function line_break_df() {
	return '<br />';
}
add_shortcode( 'br', 'line_break_df' );
