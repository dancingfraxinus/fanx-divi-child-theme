<?php
//People Directory - for Panelists and non-celebrity status guests. Celebrity Guests are listed in teh  Guests CPT

add_action( 'init', 'rename_project_cpt' );

function rename_project_cpt(){

register_post_type( 'project',
	array(
	'labels' => array(
	'name'          => __( 'People', 'divi' ),
	'singular_name' => __( 'People', 'divi' ),
	'menu_name'		=> __( 'People', 'divi'),
	'description'	=> __( 'Panelists Authors & Artists coming to FanX Salt Lake Comic Convention'),
	'add_new_item'  => __( 'New Person', 'divi' ),
	'new_item_name' => __( 'New Person Name' ),
	'all_items'		=> __( 'All People', 'divi'),
	'edit_items'	=> __( 'Edit People', 'divi'),
	'delete_items'  => __( 'Delete People'),
	'view_item'     => __( 'View Person', 'divi' ),
    'search_items'  => __( 'Search People', 'Divi' ),
	),
	'has_archive'  => true,
	'hierarchical' => true,
	'menu_icon'    => 'dashicons-nametag',
	'show_in_rest' => true,
	'menu_position' => 11,
	'query_var'    	=> true,
	'show_ui'      => true,
	'public'       => true,
	'publicly_queryable'       => true,
	'rewrite'      => array( 'slug' => 'ppl', 'with_front' => true, 'pages' => true, 'feeds' => true,),
  	'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'custom-fields', 'page-attributes'),
			)
		);
		};

//TAXONOMIES 
    //--GUEST LIST
register_taxonomy( 'project_category', 
	array( 'guests' ),
   array(
	'labels' => array(
	'name'          => __( 'Guest List', 'df' ), 
	'singular_name' => __( 'Guest List', 'df' ), 
	'search_items' => __( 'Guest Search', 'df' ),
	'add_new_item' =>__('Add New Guest List', 'df'),
	'new_item_name' => __( 'New Guest List Name' ),	
	'edit_item'	=> __( 'Edit Guest List', 'df'),
	'update_item' => __( 'Update Guest List' ),	
	'all_items' => __( 'All Guest Lists', 'df' ),
	'parent' => __( 'Main Guestlist' ), 
  
		), 
	'has_archive'  			 => true,   
    'show_admin_column' 	 => true, 
    'show_in_rest'           => true,
    'show_ui'      			 => true,    
    'hierarchical' 			 => true,   
	'query_var'    			 => true,    
	'public'      			 => true,  
	'publicly_queryable'     => true,    
	'rewrite' 		=> array('slug' => 'guestlist', 'with_front' => true ),
	'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),   
			)
	);
//-- FANDOM
register_taxonomy( 'project_tag', 
	array( 'guests' ), 
	array(
	  	'labels' => array(
	  	'name' 			=> __( 'Fandoms', 'df' ),
    	'singular_name' => __( 'Fandom', 'df' ),
		'search_items' 	=> __( 'Search Fandoms', 'df' ),
		'add_new_item'          => __( 'Add Fandom', 'df' ),
		'new_item_name' => __( 'New Fandom' ),	
		'edit_item'	=> __( 'Edit Fandom', 'df'),	
		'all_items' 	=> __( 'All Fandoms', 'df' ),		
	  ),
		'has_archive'           => true,
    	'show_admin_column' 	=> true,    
        'show_ui'      			=> true,
        'show_in_rest'          => true,  
        'query_var'   		 	=> true, 
		'public'       			=> true,  
		'publicly_queryable'    => true, 
		'rewrite' 		=> array('slug' => 'fandoms', 'with_front' => true ),
		'supports'     => array('title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes'),
		) 
				 );	
});

//-- GUEST TYPE
add_action( 'init', 'create_type_taxonomy', 0 );
 
function create_type_taxonomy() {
  
  $labels = array(
    'name' => _x( 'Type', 'taxonomy general name' ),
    'singular_name' => _x( 'Type', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Guest Types' ),
    'popular_items' => __( 'Popular Guest Types' ),
    'all_items' => __( 'All Guest Types' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Guest Types' ), 
    'update_item' => __( 'Update Guest Type' ),
    'add_new_item' => __( 'Add New Guest Type' ),
    'new_item_name' => __( 'New Guest Type' ),
    'add_or_remove_items' => __( 'Add or remove Guest Types' ),
    'choose_from_most_used' => __( 'Choose from the most used Guest Types' ),
    'menu_name' => __( 'Guest Types' ),
  ); 
  
  register_taxonomy('type','guests',
	array(
    'hierarchical'          => true,
    'labels'                => $labels,
    'show_ui'               => true,
    'show_admin_column'     => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var'             => true,
    'rewrite'               => array( 'slug' => 'type' ),
	'supports'              => array('title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes'),
  ));
}


//FILTERS - Guest Title
add_filter( 'enter_title_here', function( $title ) {
    $screen = get_current_screen();

    if  ( 'guest' == $screen->post_type ) {
        $title = 'Guest';
    }

    return $title;
});


//Help Tab in Guest Posts
add_action('admin_head', function() {
    $screen = get_current_screen();

    if ( 'people' != $screen->post_type )
        return;

    $args = [
        'id'      => 'people_help',
        'title'   => 'Panelist, Author & Artist Guest Help',
        'content' => '<h3>Guest Pages</h3><p><i>The Panelist, Author & Artist Guest Pages Layouts are controlled in the Divi Theme Builder</i>.</p>
		<p><b>To create a Guest Page:</b> Upload the headshot to the featured Image, add Bio to main editor, and the Guest Name to the Title.</p>
		<p><b>To activate the Alumni Layout:</b> check the Alumni Box in the Guestlist Tab.
		</br>-If the guest is a returning guest, simply uncheck the Alumni box to restore the standard Guest Page.</p>
		<p><b>To add or remove guests on guestlists:</b> Check boxes corrisponding boxes in Guestlist Tab.</p>
		<p><b>To add or remove guests on fandoms:</b> Type in the Fandom Name. If the name pops up that fandom already exists - click on it to choose it.
		</br>-Adding a Fandom that is not already on the list will create a new fandom on the site. Watch out for misspellings, alternate spellings (sensitive to spaces and caps).</p>',
    ];

    $screen->add_help_tab( $args );
});


// Use when needed
flush_rewrite_rules();
