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
	'query_var'    => true,
	'show_ui'      => true,
	//'taxonomies'   => array( '' ),	
	'public'       => true,
	'publicly_queryable'       => true,
	'rewrite'      => array( 'slug' => 'ppl', 'with_front' => true, 'pages' => true, 'feeds' => true,),
  	'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'custom-fields', 'page-attributes'),
			)
		);
		};

//TAXONOMIES   -- same as guest CPT


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
