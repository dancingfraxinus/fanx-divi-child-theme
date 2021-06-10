<?php
//Theme Support
add_theme_support('post-thumbnails');

//ACTIONS
add_action( 'init', function() {
     $type = 'features';
	 $labels = xcompile_post_type_labels('Feature', 'Features');
	 $supports = ['title', 'editor', 'revisions', 'page-attributes', 'thumbnail', 'custom fields', 'excerpt', 'author'];
	 $rewrite = array( 'slug' => 'features', 'with_front' => true, 'pages' => false, 'feeds' => true,);


//ARGUEMENTS
    $arguments = [
        'public' => true,
		'show_in_rest' => true,
		'rest_base' => 'features',
        'description' => 'Features and Groups coming to FanX',
        'capability_type' => 'post',
		'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'show_in_menu' => true,
		'menu-position' => 4,
        'show_ui' => true,
		'menu_icon' => 'dashicons-buddycons-activity',
        'labels'  => $labels,
		'supports' => $supports,
		'has_archive' => true,
        'hierarchical' => true,
        'rewrite'    => $rewrite,
    ];

    register_post_type( $type, $arguments);

//TAXONOMIES
    //--Feature Categories
register_taxonomy( 'feat_cat',
	array( 'features' ),
   array(
	'labels' => array(
	'name'          => __( 'Feature Category', 'df' ),
	'singular_name' => __( 'Feature Category', 'df' ),
	'search_items' => __( 'Search Feature Categories', 'df' ),
	'add_new_item' =>__('Add New Category', 'df'),
	'new_item_name' => __( 'New Category Name' ),
	'edit_item'	=> __( 'Edit Category', 'df'),
	'update_item' => __( 'Update Category' ),
	'all_items' => __( 'All Feature Categories', 'df' ),
	'parent' => __( 'parent category' ),

		),
	'has_archive'  			 => true,
    'show_admin_column' 	 => true,
    'show_in_rest'           => true,
    'show_ui'      			 => true,
    'hierarchical' 			 => true,
	'query_var'    			 => true,
	'public'      			 => true,
	'publicly_queryable'     => true,
	'rewrite' 		=> array('slug' => 'category', 'with_front' => false ),
	'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
			)
	});

//FILTERS --Feature Title
add_filter( 'enter_title_here', function( $title ) {
    $screen = get_current_screen();

    if  ( 'features' == $screen->post_type ) {
        $title = 'Feature Name Here';
    }

    return $title;
} );

// --Post Updates
add_filter( 'post_updated_messages', function($messages) {
    global $post, $post_ID;
    $link = esc_url( get_permalink($post_ID) );

    $messages['features'] = array(
        0 => '',
        1 => sprintf( __('Guest Info updated. <a href="%s">View Feature Page</a>'), $link ),
        2 => __('Custom field updated.'),
        3 => __('Custom field deleted.'),
        4 => __('Feature Info updated.'),
        5 => isset($_GET['revision']) ? sprintf( __('Feature Info restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __('Feature Page published. <a href="%s">View Feature Page</a>'), $link ),
        7 => __('Feature Info saved.'),
        8 => sprintf( __('Feature Page submitted. <a target="_blank" href="%s" rel="noopener noreferrer">Preview Feature Page</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        9 => sprintf( __('Feature Page scheduled to go live on: <strong>%1$s</strong>. <a target="_blank" href="%2$s" rel="noopener noreferrer">Preview Feature Page</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), $link ),
        10 => sprintf( __('Feature Page draft updated. <a target="_blank" href="%s" rel="noopener noreferrer">Preview Feature Page</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    );
    return $messages;
});

// --Bulk Updates
add_filter( 'bulk_post_updated_messages', function( $bulk_messages, $bulk_counts ) {
    $bulk_messages['features'] = array(
        'updated'   => _n( "%s feature updated.", "%s feature pages updated.", $bulk_counts["updated"] ),
        'locked'    => _n( "%s feature page not updated, somebody is editing it.", "%s feature pages not updated, somebody is editing them.", $bulk_counts["locked"] ),
        'deleted'   => _n( "%s feature page permanently deleted.", "%s feature pages permanently deleted.", $bulk_counts["deleted"] ),
        'trashed'   => _n( "%s feature page moved to the Trash.", "%s feature pages moved to the Trash.", $bulk_counts["trashed"] ),
        'untrashed' => _n( "%s feature page restored from the Trash.", "%s feature pages restored from the Trash.", $bulk_counts["untrashed"] ),
    );

    return $bulk_messages;
}, 10, 2 );


//LABELS
function xcompile_features_type_labels($singular = 'Feature', $plural = 'Features') {
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

//Help Tab in Feature Posts
add_action('admin_head', function() {
    $screen = get_current_screen();

    if ( 'features' != $screen->post_type )
        return;

    $args = [
        'id'      => 'features_help',
        'title'   => 'Features Page Help',
        'content' => 'Coming Soon',
    ];

    $screen->add_help_tab( $args );
});

//--- FEATURE POST ADMIN COLUMNS --->>
add_filter( 'manage_features_posts_columns', 'df_features_columns' );
function df_features_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
        'featured_image' => __('Image', 'df'),
        'title' => __('Feature Name', 'df'),
		'feat_cat' => __('Category', 'df'),
		'project_tag'  => __('Fandom', 'df'),
        'date'  => __('Date', 'df'),
	);

	return $columns;
}

//---CUSTOM COLUMNS-->>
add_action( 'manage_features_posts_custom_column', 'df_features_custom_column', 10, 2);

function df_features_custom_column( $column, $post_id ) {
//---IMG-->>
    if ( 'featured_image' === $column ) {
    echo get_the_post_thumbnail( $post_id, array(200, 200) );
  }
//---FEATURE CATEGORY-->>
 if ( 'feat_cat' === $column ) {
      echo get_the_term_list ( $post_id, 'feat_cat' ) ;
    }

//---FANDOM --->>
if ('project_tag' === $column ) {
    echo get_the_term_list ($post_id, 'project_tag', ' ', ', '  );
}
    }

//FILTER FEATURES IN ADMIN COLUMNS -- Drop Downs -->>


//**Guest List**
add_action('restrict_manage_posts', 'filter_feature_cat_df');
function filter_feature_cat_df() {
	 global $typenow;
    if ($typenow =='features'){
	$taxonomy  = 'feat_cat';
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

add_filter('parse_query', 'convert_featurecat_id_df');
function convert_featurecat_id_df($query) {
	global $pagenow;
	$post_type = 'features';
	$taxonomy  = 'feat_cat';
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
?>
