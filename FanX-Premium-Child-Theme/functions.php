<?php 

add_action( 'wp_enqueue_scripts', 'my_enqueue_assets' ); 
include('login-editor.php');
//include('guest-portfolios.php');
include('white-label.php');
include('events/events.php');
include('guests/guests.php');
include('guests/growtix.php');

function my_enqueue_assets() { 

    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' ); 
 wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );	
}

//Theme Support 
add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );


//Redirect Images to Post 
add_action( 'template_redirect', 'wpsites_attachment_redirect' );
function wpsites_attachment_redirect(){
global $post;
if ( is_attachment() && isset($post->post_parent) && is_numeric($post->post_parent) && ($post->post_parent != 0) ) :
    wp_redirect( get_permalink( $post->post_parent ), 301 );
    exit();
    wp_reset_postdata();
    endif;
}

//Removes Default Image Linking 
function wpb_imagelink_setup() {
    $image_set = get_option( 'image_default_link_type' );
     
    if ($image_set !== 'none') {
        update_option('image_default_link_type', 'none');
    }
}
add_action('admin_init', 'wpb_imagelink_setup', 10);




//POST ADMIN COLUMNS
add_filter('manage_posts_columns' , 'df_custom_columns');
function df_custom_columns( $columns ) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'featured_image' => 'Image',
        'title' => 'Title',
        'categories' => 'Categories',
		'tags'  => 'Tags',
        'date' => 'Date'
     );
    return $columns;
}

//**Post Admin Thumbnails 
add_action( 'manage_posts_custom_column' , 'df_custom_columns_data', 10, 2 );
function df_custom_columns_data( $column, $post_id ) {
    switch ( $column ) {
    case 'featured_image':
        $thumb = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
			echo ($thumb) ? '<img src="'.$thumb.'" width="100%" height="auto">' : '';
			break;
    }
}



//****HTML SITEMAPS*****
//Posts
function sitemap() {
    $sitemap = '';
    $sitemap .= '<h4>Articles </h4>';
    $sitemap .= '<ul class="sitemapul">';
    $posts_array = get_posts();
    foreach ($posts_array as $spost):
        $sitemap .='<div class="blockArticle">
            <li><a href="' . $spost->guid . '" rel="bookmark" class="linktag">' . $spost->post_title . '</a> </li>
        </div>';
    endforeach;
    $sitemap .= '</ul>';
		
    return$sitemap;
}
add_shortcode('sitemap', 'sitemap');


//Pages 
function sitemapage(){
    $sitemapage = ''; 
    $pages_args = array(
        'exclude' => '', /* ID of pages to be excluded, separated by comma */
        'post_type' => 'page',
        'post_status' => 'publish'
    );
    $sitemapage .= '<h4>Pages</h4>';
    $sitemapage .= '<ul class="sitemapul">';
    $pages = get_pages($pages_args);
    foreach ($pages as $page) :
        $sitemapage .= '<li class="pages-list"><a href="' . get_page_link($page->ID) . '" rel="bookmark">' . $page->post_title . '</a></li>';
    endforeach;
    $sitemapage .= '</ul>';
	
    return$sitemapage;
}
add_shortcode('sitemapage', 'sitemapage');

//Footer Pages 
function sitemapfoot(){
    $sitemapfoot = ''; 
    $pages_args = array(
        'exclude' => '', /* ID of pages to be excluded, separated by comma */
        'post_type' => 'page',
        'post_status' => 'publish'
    );
    $sitemapfoot .= '<ul class="sitemapfootul">';
    $pages = get_pages($pages_args);
    foreach ($pages as $page) :
        $sitemapfoot .= '<li class="pages-list"><a href="' . get_page_link($page->ID) . '" rel="bookmark">' . $page->post_title . '</a></li>';
    endforeach;
    $sitemapfoot .= '</ul>';
	
    return$sitemapfoot;
}
add_shortcode('sitemapfoot', 'sitemapfoot');

//Images

// Begin remove Divi Portfolio and Filterable Portfolio featured image crop
function pa_portfolio_image_width($width) {
	return '9999';
}
function pa_portfolio_image_height($height) {
	return '9999';
}
add_filter( 'et_pb_portfolio_image_width', 'pa_portfolio_image_width' );
add_filter( 'et_pb_portfolio_image_height', 'pa_portfolio_image_height' );
 

//Security
add_filter( 'jetpack_sso_require_two_step', '__return_true' ); //Google Auth for .com login


// Remove Yoast Filter Dropdown 
 function disable_yoast_seo_metabox( $post_types ) {
   unset( $post_types['guests'] );
   return $post_types;
 }    
 add_filter( 'wpseo_accessible_post_types', 'disable_yoast_seo_metabox' ); 



//Use When Needed:
//remove_action('shutdown', 'wp_ob_end_flush_all', 1);  //Flush error 
flush_rewrite_rules(); //Flush Rules