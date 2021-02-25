<?php
//Theme Support 
add_theme_support('post-thumbnails');

//ACTIONS
add_action( 'init', function() {
     $type = 'event';
	 $labels = xcompile_post_type_labels('Event', 'Events');
	 $supports = ['title', 'editor', 'revisions', 'page-attributes', 'thumbnail', 'custom fields', 'excerpt', 'author'];
	 $rewrite = array( 'slug' => 'event', 'with_front' => true, 'pages' => true, 'feeds' => true,);
	 

//ARGUEMENTS
    $arguments = [
        'public' => true,
		'show_in_rest' => true,
		'rest_base' => 'events',
        'description' => 'Event Calendar for FanX Salt Lake Comic Convention',
        'capability_type' => 'post',
		'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'show_in_menu' => true,
		'menu-position' => 5,
        'show_ui' => true,
		'menu_icon' => 'dashicons-calendar-alt',
        'labels'  => $labels,
		'supports' => $supports,
		'has_archive' => true,
        'hierarchical' => false,
        'rewrite'    => $rewrite,
    ];
	
    register_post_type( $type, $arguments);
	
//TAXONOMIES 
register_taxonomy( 'event_category', 
	array( 'event' ),
   array(
	'labels' => array(
	'name'          => __( 'Event Category', 'df' ), 
	'singular_name' => __( 'Event Category', 'df' ), 
	'search_items' => __( 'Event Category Search', 'df' ),
	'add_new_item' =>__('Add New Event Category', 'df'),
	'new_item_name' => __( 'New Event Category Name' ),	
	'edit_item'	=> __( 'Edit Event Cateogry', 'df'),
	'update_item' => __( 'Update Event Category' ),	
	'all_items' => __( 'All Event Categories', 'df' ),
	'parent' => __( '' ), 
  
		), 
	'has_archive'  			 => true,   
    'show_admin_column' 	 => true, 
    'show_in_rest'           => true,
    'show_ui'      			 => true,    
    'hierarchical' 			 => true,   
	'query_var'    			 => true,    
	'public'      			 => true,  
	'publicly_queryable'     => true,    
	'rewrite' 		=> array('slug' => 'events', 'with_front' => false ),
	'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),   
			)
	);

register_taxonomy( 'event_tag', 
	array( 'event' ), 
	array(
	  	'labels' => array(
	  	'name' 			=> __( 'Event Tags', 'df' ),
    	'singular_name' => __( 'Event Tag', 'df' ),
		'search_items' 	=> __( 'Search Event Tags', 'df' ),
		'add_new_item'          => __( 'Add New Event Tag', 'df' ),
		'new_item_name' => __( 'New Event Tag Name' ),	
		'edit_item'	=> __( 'Edit Event Tag', 'df'),	
		'all_items' 	=> __( 'All Event Tags', 'df' ),		
	  ),
		'has_archive'           => true,
    	'show_admin_column' 	=> true,    
        'show_ui'      			=> true,
        'show_in_rest'          => true,  
        'query_var'   		 	=> true, 
		'public'       			=> true,  
		'publicly_queryable'    => true, 
		'rewrite' 		=> array('slug' => 'event_type', 'with_front' => false ),
		'supports'     => array('title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes'),
		) 
				 );	
});

//CUSTOM TAXONOMIES 
add_action( 'init', 'create_venue_taxonomy', 0 );
 
function create_venue_taxonomy() {
  
  $labels = array(
    'name' => _x( 'Venue', 'taxonomy general name' ),
    'singular_name' => _x( 'Venue', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Venues' ),
    'popular_items' => __( 'Popular Venues' ),
    'all_items' => __( 'All Venues' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Venue' ), 
    'update_item' => __( 'Update Venue' ),
    'add_new_item' => __( 'Add New Venue' ),
    'new_item_name' => __( 'New Venue Name' ),
    'add_or_remove_items' => __( 'Add or remove venues' ),
    'choose_from_most_used' => __( 'Choose from the most used venues' ),
    'menu_name' => __( 'Venues' ),
  ); 
  
  register_taxonomy('venue','event',
	array(
    'hierarchical'          => true,
    'labels'                => $labels,
    'show_ui'               => true,
    'show_admin_column'     => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var'             => true,
    'rewrite'               => array( 'slug' => 'venues' ),
	'supports'              => array('title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes'),
  ));
}

//FILTERS --Event Title
add_filter( 'enter_title_here', function( $title ) {
    $screen = get_current_screen();

    if  ( 'event' == $screen->post_type ) {
        $title = 'Enter name of the event here';
    }

    return $title;
} );

// --Post Updates
add_filter( 'post_updated_messages', function($messages) {
    global $post, $post_ID;
    $link = esc_url( get_permalink($post_ID) );

    $messages['event'] = array(
        0 => '',
        1 => sprintf( __('Event updated. <a href="%s">View Event</a>'), $link ),
        2 => __('Custom field updated.'),
        3 => __('Custom field deleted.'),
        4 => __('Event updated.'),
        5 => isset($_GET['revision']) ? sprintf( __('Event restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __('Event published. <a href="%s">View Event</a>'), $link ),
        7 => __('Event saved.'),
        8 => sprintf( __('Event submitted. <a target="_blank" href="%s" rel="noopener noreferrer">Preview Event</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        9 => sprintf( __('Event scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s" rel="noopener noreferrer">Preview Event</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), $link ),
        10 => sprintf( __('Event draft updated. <a target="_blank" href="%s" rel="noopener noreferrer">Preview Event</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    );
    return $messages;
});

// --Bulk Updates
add_filter( 'bulk_post_updated_messages', function( $bulk_messages, $bulk_counts ) {
    $bulk_messages['event'] = array(
        'updated'   => _n( "%s event updated.", "%s events updated.", $bulk_counts["updated"] ),
        'locked'    => _n( "%s event not updated, somebody is editing it.", "%s events not updated, somebody is editing it.", $bulk_counts["locked"] ),
        'deleted'   => _n( "%s event permanently deleted.", "%s events permanently deleted.", $bulk_counts["deleted"] ),
        'trashed'   => _n( "%s event moved to the Trash.", "%s events moved to the Trash.", $bulk_counts["trashed"] ),
        'untrashed' => _n( "%s event restored from the Trash.", "%s events restored from the Trash.", $bulk_counts["untrashed"] ),
    );

    return $bulk_messages;
}, 10, 2 );


//LABELS
function xcompile_post_type_labels($singular = 'Post', $plural = 'Posts') {
    $p_lower = strtolower($plural);
    $s_lower = strtolower($singular);

    return [
        'name' => $plural,
        'singular_name' => $singular,
		'menu_name'   => $plural,
        'add_new_item' => "New $singular",
		'add_new'  => "New $singular",
        'edit_item' => "Edit $singular",
        'view_item' => "View $singular",
        'view_items' => "View $plural",
        'search_items' => "Search $plural",
        'not_found' => "No $p_lower found",
        'not_found_in_trash' => "No $p_lower found in trash",
        'parent_item_colon' => "Parent $singular",
        'all_items' => "All $plural",
        'archives' => "$singular Archives",
        'attributes' => "$singular Attributes",
        'insert_into_item' => "Insert into $s_lower",
        'uploaded_to_this_item' => "Uploaded to this $s_lower",
    ];
}

// EVENT INDEX - DP Blog Module 
function dp_ppp_custom_query_function() {
    return array(
    'post_type' => 'event',  
    'orderby'  => 'custom_query','when',
    'order' => 'asc', 
    'posts_number' => '12',
    'taxonomy' => 'current', 
  );   
}
 
add_filter('dp_ppp_custom_query_args', 'dp_ppp_custom_query_function');
           

//HELP TAB
add_action('admin_head', function() {
    $screen = get_current_screen();

    if ( 'event' != $screen->post_type )
        return;

    $args = [
        'id'      => 'event_help',
        'title'   => 'Event Post Help',
        'content' => '<h3>This is Event Help</h3>'
    ];

    $screen->add_help_tab( $args );
});


// EVENT POST ADMIN COLUMNS
add_filter( 'manage_edit-event_columns', 'df_event_columns' );
function df_event_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
        'featured_image' => __('Image', 'df'),
        'title' => __('Event Title', 'df'),
		'venue' => __('Venue', 'df'),
		'categories' => __('Categories', 'df'),
		'tags'  => __('Tags', 'df'),
	);

	return $columns;
}


// MAKE EVENT EXPIRE
if ($expireTransient = get_transient($post->ID) === false) {
	set_transient($post->ID, 'set for 1 minutes', 1 * MINUTE_IN_SECONDS );
	$today = date('Y-m-d H:i:s', current_time('timestamp', 0));
	$args = array(
		'post_type' => 'event',
		'post_status' => 'publish',
		'meta_query' => array(
			array(
				'key' => 'end_date_time',
				'value' => $today,
				'compare' => '<='
			)
		)
	);
	$posts = get_posts($args);
	foreach( $posts as $post ) {
		if(get_field('end_date_time', $post->ID)) {
			$postdata = array(
				'ID' => $post->ID,
				'post_status' => 'draft'
			);
			wp_update_post($postdata);
		}
	}
}



//Use When Needed:
//remove_action('shutdown', 'wp_ob_end_flush_all', 1);  //Flush error 
//flush_rewrite_rules(); //Flush Rules