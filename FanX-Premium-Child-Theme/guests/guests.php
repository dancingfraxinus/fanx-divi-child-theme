<?php
//Theme Support
add_theme_support('post-thumbnails');

//ACTIONS
add_action( 'init', function() {
     $type = 'guests';
	 $labels = xcompile_post_type_labels('Guest', 'Guests');
	 $supports = ['title', 'editor', 'revisions', 'page-attributes', 'thumbnail', 'custom fields', 'excerpt', 'author'];
	 $rewrite = array( 'slug' => 'guests', 'with_front' => true, 'pages' => true, 'feeds' => true,);


//ARGUEMENTS
    $arguments = [
        'public' => true,
		'show_in_rest' => true,
		'rest_base' => 'guests',
        'description' => 'The Guestlist for FanX',
        'capability_type' => 'post',
		'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'show_in_menu' => true,
		'menu-position' => 1,
        'show_ui' => true,
		'menu_icon' => 'dashicons-groups',
        'labels'  => $labels,
		'supports' => $supports,
		'has_archive' => true,
        'hierarchical' => true,
        'rewrite'    => $rewrite,
    ];

    register_post_type( $type, $arguments);

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

//FILTERS --Guest Title
add_filter( 'enter_title_here', function( $title ) {
    $screen = get_current_screen();

    if  ( 'guests' == $screen->post_type ) {
        $title = 'Guest Name Here';
    }

    return $title;
} );

// --Post Updates
add_filter( 'post_updated_messages', function($messages) {
    global $post, $post_ID;
    $link = esc_url( get_permalink($post_ID) );

    $messages['guests'] = array(
        0 => '',
        1 => sprintf( __('Guest Info updated. <a href="%s">View Guest Page</a>'), $link ),
        2 => __('Custom field updated.'),
        3 => __('Custom field deleted.'),
        4 => __('Guest Info updated.'),
        5 => isset($_GET['revision']) ? sprintf( __('Guest Info restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __('Guest Page published. <a href="%s">View Guest Page</a>'), $link ),
        7 => __('Guest Info saved.'),
        8 => sprintf( __('Guest Page submitted. <a target="_blank" href="%s" rel="noopener noreferrer">Preview Guest Page</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        9 => sprintf( __('Guest Page scheduled to go live on: <strong>%1$s</strong>. <a target="_blank" href="%2$s" rel="noopener noreferrer">Preview Guest Page</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), $link ),
        10 => sprintf( __('Guest Page draft updated. <a target="_blank" href="%s" rel="noopener noreferrer">Preview Guest Page</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    );
    return $messages;
});

// --Bulk Updates
add_filter( 'bulk_post_updated_messages', function( $bulk_messages, $bulk_counts ) {
    $bulk_messages['guests'] = array(
        'updated'   => _n( "%s guest updated.", "%s guest pages updated.", $bulk_counts["updated"] ),
        'locked'    => _n( "%s guest page not updated, somebody is editing it.", "%s guest pages not updated, somebody is editing them.", $bulk_counts["locked"] ),
        'deleted'   => _n( "%s guest page permanently deleted.", "%s guest pages permanently deleted.", $bulk_counts["deleted"] ),
        'trashed'   => _n( "%s guest page moved to the Trash.", "%s guest pages moved to the Trash.", $bulk_counts["trashed"] ),
        'untrashed' => _n( "%s guest page restored from the Trash.", "%s guest pages restored from the Trash.", $bulk_counts["untrashed"] ),
    );

    return $bulk_messages;
}, 10, 2 );


//LABELS
function xcompile_guest_type_labels($singular = 'Guest', $plural = 'Guests') {
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

//--- GUEST POST ADMIN COLUMNS --->>
add_filter( 'manage_guests_posts_columns', 'df_guests_columns' );
function df_guests_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
        'featured_image' => __('Image', 'df'),
        'title' => __('Guest Name', 'df'),
		'type' => __('Type', 'df'),
		'project_category' => __('Guest List', 'df'),
		'project_tag'  => __('Fandom', 'df'),
        'date'  => __('Date', 'df'),
	);

	return $columns;
}

//---CUSTOM COLUMNS-->>
add_action( 'manage_guests_posts_custom_column', 'df_guests_custom_column', 10, 2);

function df_guests_custom_column( $column, $post_id ) {
//---IMG-->>
    if ( 'featured_image' === $column ) {
    echo get_the_post_thumbnail( $post_id, array(200, 200) );
  }
//---GUEST LIST-->>
 if ( 'project_category' === $column ) {
      echo get_the_term_list ( $post_id, 'project_category' ) ;
    }
//----GUEST TYPE -->>
if ('type' === $column){
    echo get_the_term_list ($post_id, 'type');
}
//---FANDOM --->>
if ('project_tag' === $column ) {
    echo get_the_term_list ($post_id, 'project_tag', ' ', ', '  );
}
    }

//FILTER GUESTS IN ADMIN COLUMNS -- Drop Downs -->>

//**Type**
add_action('restrict_manage_posts', 'filter_guest_type_df');
function filter_guest_type_df() {
    global $typenow;
    if ($typenow =='guests'){
	$taxonomy  = 'type';
	$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => sprintf( __( 'Show all %s', 'df' ), $info_taxonomy->label ),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	}};

add_filter('parse_query', 'convert_type_id_df');
function convert_type_id_df($query) {
	global $pagenow;
	$post_type = 'guests';
	$taxonomy  = 'type';
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
};


//**Guest List**
add_action('restrict_manage_posts', 'filter_guest_list_df');
function filter_guest_list_df() {
	 global $typenow;
    if ($typenow =='guests'){
	$taxonomy  = 'project_category';
	$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => sprintf( __( 'Show all %s', 'textdomain' ), $info_taxonomy->label ),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	}};

add_filter('parse_query', 'convert_guestlist_id_df');
function convert_guestlist_id_df($query) {
	global $pagenow;
	$post_type = 'guests';
	$taxonomy  = 'project_category';
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
};


//**Fandom**
add_action('restrict_manage_posts', 'filter_guest_fandom_df');
function filter_guest_fandom_df() {
	 global $typenow;
    if ($typenow =='guests'){
	$taxonomy  = 'project_tag';
	$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => sprintf( __( 'Show all %s', 'textdomain' ), $info_taxonomy->label ),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	}};

add_filter('parse_query', 'convert_fandom_id_df');
function convert_fandom_id_df($query) {
	global $pagenow;
	$post_type = 'guests';
	$taxonomy  = 'project_tag';
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
};


//USE WHEN NEEDED:
//remove_action('shutdown', 'wp_ob_end_flush_all', 1);  //Flush error
flush_rewrite_rules(); //Flush Rules
