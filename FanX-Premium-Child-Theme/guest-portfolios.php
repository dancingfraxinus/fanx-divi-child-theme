<?php
// PROJECTS RENAMED AS GUESTS -- Currently Not being used
add_action( 'init', 'rename_project_cpt' );

function rename_project_cpt(){

register_post_type( 'project',
	array(
	'labels' => array(
	'name'          => __( 'Guests', 'divi' ), 
	'singular_name' => __( 'Guest', 'divi' ), 
	'menu_name'		=> __( 'Guestlist', 'divi'),
	'description'	=> __( 'FanX Salt Lake Comic Convention Guestlist'),	
	'add_new_item'  => __( 'New Guest', 'divi' ), 	
	'new_item_name' => __( 'New Guest Name' ),	
	'all_items'		=> __( 'All Guests', 'divi'),
	'edit_items'	=> __( 'Edit Guests', 'divi'),
	'delete_items'  => __( 'Delete Guests'),	
	'view_item'     => __( 'View Guest', 'divi' ),	
    'search_items'  => __( 'Search Guestlist', 'Divi' ),		
	),
	'has_archive'  => true,	
	'hierarchical' => true,
	'menu_icon'    => 'dashicons-groups',
	'show_in_rest' => true, 
	'menu_position' => 6, 	
	'query_var'    	=> true,   
	'show_ui'      => true,   
	'public'       => true,  
	'publicly_queryable'       => true,	
	'rewrite'      => array( 'slug' => 'guests', 'with_front' => true, 'pages' => true, 'feeds' => true,),
  	'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'custom-fields', 'page-attributes'),    
			)	  
				);	
    
//TAXONOMIES    
register_taxonomy( 'project_category', 
	array( 'project' ),
   array(
	'labels' => array(
	'name'          => __( 'Guestlists', 'divi' ), 
	'singular_name' => __( 'Guestlist', 'divi' ), 
	'search_items' => __( 'Search Guestlist', 'Divi' ),
	'add_new_item' =>__('New Guestlist', 'Divi'),
	'new_item_name' => __( 'New Guestlist Name' ),	
	'edit_item'	=> __( 'Edit Guestlist', 'divi'),
	'update_item' => __( 'Update Guestlist' ),	
	'all_items' => __( 'Guestlists', 'Divi' ),
	'parent' => __( 'Parent Guestlist' ), 
  
		), 
	'has_archive'  			 => true,   
	'show_admin_column' 	 => true,   
	'hierarchical' 			 => true,   
	'query_var'    			 => true,   
	'show_ui'      			 => true,   
	'public'      			 => true,  
	'publicly_queryable'     => true,    
	'rewrite' 		=> array('slug' => 'guestlist'),
	'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),   
			)
	);

register_taxonomy( 'project_tag', 
	array( 'project' ), 
	array(
	  	'labels' => array(
	  	'name' 			=> __( 'Fandoms', 'Divi' ),
    	'singular_name' => __( 'Fandom', 'Divi' ),
		'search_items' 	=> __( 'Search Fandoms', 'Divi' ),
		'add_new_item'          => __( 'New Fandom', 'divi' ),
		'new_item_name' => __( 'New Fandom Name' ),	
		'edit_item'	=> __( 'Edit Fandom', 'divi'),	
		'all_items' 	=> __( 'All Fandoms', 'Divi' ),		
	  ),
		'has_archive'           => true,
    	'show_admin_column' 	=> true, 
		'query_var'   		 	=> true,   
		'show_ui'      			=> true,   
		'public'       			=> true,  
		'publicly_queryable'    => true, 
		'rewrite' 		=> array('slug' => 'fandoms', 'with_front' => false ),
		'supports'     => array('title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes'),
		) 
				 );	
					}

//CUSTOM TAXONOMIES 
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
    'edit_item' => __( 'Edit Guest Type' ), 
    'update_item' => __( 'Update Guest Type' ),
    'add_new_item' => __( 'Add New Guest Type' ),
    'new_item_name' => __( 'New Guest Type Name' ),
    'add_or_remove_items' => __( 'Add or remove Guest Types' ),
    'choose_from_most_used' => __( 'Choose from the most used Guest Types' ),
    'menu_name' => __( 'Guest Types' ),
  ); 
  
  register_taxonomy('type','project',
	array(
    'hierarchical'          => true,
    'labels'                => $labels,
    'show_ui'               => true,
    'show_admin_column'     => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var'             => true,
    'rewrite'               => array( 'slug' => 'guestlists'),
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
} );


//Help Tab in Guest Posts 
add_action('admin_head', function() {
    $screen = get_current_screen();

    if ( 'project' != $screen->post_type )
        return;

    $args = [
        'id'      => 'guest_help',
        'title'   => 'Guest Page Help',
        'content' => '<h3>Guest Pages</h3><p><i>The Guest Page & Alumni Page Layouts are controlled in the Divi Theme Builder</i>.</p>
		<p><b>To create a Guest Page:</b> Upload the headshot to the featured Image, add Bio to main editor, and the Guest Name to the Title.</p>
		<p><b>To activate the Alumni Layout:</b> check the Alumni Box in the Guestlist Tab.
		</br>-If the guest is a returning guest, simply uncheck the Alumni box to restore the standard Guest Page.</p>
		<p><b>To add or remove guests on guestlists:</b> Check boxes corrisponding boxes in Guestlist Tab.</p>
		<p><b>To add or remove guests on fandoms:</b> Type in the Fandom Name. If the name pops up that fandom already exists - click on it to choose it.
		</br>-Adding a Fandom that is not already on the list will create a new fandom on the site. Watch out for misspellings, alternate spellings (sensitive to spaces and caps).</p>',
    ];

    $screen->add_help_tab( $args );
});

//GUEST ADMIN COLUMNS 
add_filter('manage_edit-project_columns' , 'df_project_columns');
function df_project_columns( $columns ) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'featured_image' => __('Image', 'df'),
        'title' => __('Guest Name', 'df'),
        'type' => __('Guest Type', 'df'),
        'categories' => __('Guest Lists', 'df'),
		'tags'  => __('Fandoms', 'df'),
        'date' => __('Date' , 'df'),
     );
    
    return $columns;
}

//***Featured Image Admin Column 
add_action( 'manage_guests_img_column', 'df_guest_img_column', 10, 2);
function df_guest_img_column( $column, $post_id ) {
  // Image column
  if ( 'featured_image' === $column ) {
    echo get_the_post_thumbnail( $post_id, array(80, 80) );
  }
}

//****Custom Taxonomy Admin Column
add_action( 'guests_custom_column' , 'custom_guest_column', 10, 2 );
function custom_guest_column( $column, $post_id ) {
    switch ( $column ) {

        case 'type' :
            echo get_post_meta( $post_id , 'type' , true ); 
            break;
        
         case 'categories' :
            echo get_post_meta( $post_id , 'project_category' , true ); 
            break;    
        
          case 'tags' :
            echo get_post_meta( $post_id , 'project_tag' , true ); 
            break;       

    }
}


// Use when needed
flush_rewrite_rules();

?>