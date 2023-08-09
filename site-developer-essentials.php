<?php 
/*
Plugin Name: Site Developer Essentials
Plugin URI: http://wordpress.org/extend/plugins/site-developer-essentials/
Description: Performs couple of tweaks to improve project development time.
Author: 24x7 Security Advisors
Version: 1.0
Author URI: https://nate512.wixsite.com/wpfixxyz
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 100);
add_filter('gutenberg_use_widgets_block_editor', '__return_false', 100 );
add_filter('use_widgets_block_editor', '__return_false' );


add_action( 'admin_bar_menu', 'admin_bar_links_247',999);

function admin_bar_links_247($admin_bar) {         


	$args = array(
		'parent' => 'site-name',
		'id'     => 'posts',
		'title'  => 'Posts',
		'href'   => esc_url( admin_url( 'edit.php' ) ),
		'meta'   => false
	);
	$admin_bar->add_node( $args );

	$args = array(
		'parent' => 'site-name',
		'id'     => 'pages',
		'title'  => 'Pages',
		'href'   => esc_url( admin_url( 'edit.php?post_type=page' ) ),
		'meta'   => false
	);
	$admin_bar->add_node( $args );

	$args = array(
	    'parent' => 'site-name',
	    'id'     => 'media-libray',
	    'title'  => 'Media',
	    'href'   => esc_url( admin_url( 'upload.php' ) ),
	    'meta'   => false
		);
	$admin_bar->add_node( $args );

	$args = array(
		'parent' => 'site-name',
		'id'     => 'plugins',
		'title'  => 'Plugins',
		'href'   => esc_url( admin_url( 'plugins.php' ) ),
		'meta'   => false
	);
	$admin_bar->add_node( $args );


	if( class_exists( 'WPCF7' ) ) {
		$args = array(
			'parent' => 'site-name',
			'id'     => 'wpcf7',
			'title'  => 'Forms',
			'href'   => esc_url( admin_url( 'admin.php?page=wpcf7' ) ),
			'meta'   => false
		);
		$admin_bar->add_node( $args );
	}

	$args = array(
		'parent' => 'site-name',
		'id'     => 'users',
		'title'  => 'Users',
		'href'   => esc_url( admin_url( 'users.php' ) ),
		'meta'   => false
	);
	$admin_bar->add_node( $args );

	$args = array(
		'parent' => 'site-name',
		'id'     => 'settings',
		'title'  => 'Settings',
		'href'   => esc_url( admin_url( 'options-general.php' ) ),
		'meta'   => false
	);
	$admin_bar->add_node( $args );

	if( function_exists('acf_add_options_page') ) {

		$args = array(
			'parent' => 'settings',
			'id'     => 'themesettings',
			'title'  => 'Theme',
			'href'   => esc_url( admin_url( 'admin.php?page=theme-general-settings' ) ),
			'meta'   => false
		);

		$admin_bar->add_node( $args );
			$args = array(
			'parent' => 'settings',
			'id'     => 'headersettings',
			'title'  => 'Header',
			'href'   => esc_url( admin_url( 'admin.php?page=acf-options-header' ) ),
			'meta'   => false
		);
		$admin_bar->add_node( $args );

		$admin_bar->add_node( $args );
			$args = array(
			'parent' => 'settings',
			'id'     => 'footersettings',
			'title'  => 'Footer',
			'href'   => esc_url( admin_url( 'admin.php?page=acf-options-footer' ) ),
			'meta'   => false
		);
		$admin_bar->add_node( $args );


		$args = array(
			'parent' => 'site-name',
			'id'     => 'acf-field-group',
			'title'  => 'ACF Fields',
			'href'   => esc_url( admin_url( 'edit.php?post_type=acf-field-group' ) ),
			'meta'   => false
		);
		$admin_bar->add_node( $args );
	}

}

add_action('admin_enqueue_scripts', 'sticky_publish_247');
function sticky_publish_247() {
  echo '<style>
    	div#submitdiv{
			position: sticky;
    		top: 0;
    		z-index: 1;
    	}	
  		</style>';
}

add_action('wp_head', function () {
   if(is_user_logged_in()){
   	echo '<style>.admin-bar header {
		margin-top: 32px
		}</style>';
   }
});

if ( class_exists( 'ACF' ) ) {
function backend_logo_247() { 
	if(get_field('backend_logo','option')){
?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_field('backend_logo','option')['url']; ?>);
        height:100px;
        width:300px;
        background-size: 300px 100px;
        background-repeat: no-repeat;
        padding-bottom: 10px;
        }
    </style>
<?php } }
add_action( 'login_enqueue_scripts', 'backend_logo_247' );
}

function backend_logo_url_247() {
    return home_url();
}
add_filter( 'login_headerurl', 'backend_logo_url_247' );


if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page(array(
        'page_title'    => 'General Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
    
    acf_add_options_sub_page(array(
        'page_title'    => 'Header Settings',
        'menu_title'    => 'Header',
        'parent_slug'   => 'theme-general-settings',
    ));
    
    acf_add_options_sub_page(array(
        'page_title'    => 'Footer Settings',
        'menu_title'    => 'Footer',
        'parent_slug'   => 'theme-general-settings',
    ));
    
}

function upload_svg_files_247( $allowed ) {
    if ( !current_user_can( 'manage_options' ) )
        return $allowed;
    $allowed['svg'] = 'image/svg+xml';
    return $allowed;
}
add_filter( 'upload_mimes', 'upload_svg_files_247');


	



/* Disable the emoji's */
function disable_emojis_247() {
 remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
 remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
 remove_action( 'wp_print_styles', 'print_emoji_styles' );
 remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
 remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
 remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
 remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'disable_emojis_247' );

/*Add DNS Prefetch*/
function dns_prefetch_247() {
echo 
   '<meta http-equiv="x-dns-prefetch-control" content="on">
	<link rel="dns-prefetch" href="//fonts.googleapis.com" />
	<link rel="dns-prefetch" href="//fonts.gstatic.com" />
	<link rel="dns-prefetch" href="//vjs.zencdn.net" />
	<link rel="dns-prefetch" href="//connect.facebook.net" /> 
	<link rel="dns-prefetch" href="//www.facebook.com" /> 
	<link rel="dns-prefetch" href="//cdnjs.cloudflare.com" /> 
	<link rel="dns-prefetch" href="//f.vimeocdn.com" /> 
	<link rel="dns-prefetch" href="//i.vimeocdn.com" /> 
	<link rel="dns-prefetch" href="//fresnel.vimeocdn.com" /> 
	<link rel="dns-prefetch" href="//player.vimeo.com" /> 
	<link rel="dns-prefetch" href="//cdn.jsdelivr.net" /> 
	<link rel="dns-prefetch" href="//stackpath.bootstrapcdn.com" />   
	<link rel="dns-prefetch" href="//maxcdn.bootstrapcdn.com" />   
	<link rel="dns-prefetch" href="//cdnjs.cloudflare.com" />   
	<link rel="dns-prefetch" href="//www.googletagmanager.com" />   
	<link rel="dns-prefetch" href="//www.google-analytics.com" />   
	<link rel="dns-prefetch" href="//0.gravatar.com/" />
	<link rel="dns-prefetch" href="//2.gravatar.com/" />
	<link rel="dns-prefetch" href="//1.gravatar.com/" />
	<link rel="dns-prefetch" href="https://maps.googleapis.com">
	<link rel="dns-prefetch" href="https://maps.gstatic.com">
	<link rel="dns-prefetch" href="https://ajax.googleapis.com">
	<link rel="dns-prefetch" href="https://apis.google.com">
	<link rel="dns-prefetch" href="https://google-analytics.com">
	<link rel="dns-prefetch" href="https://www.google-analytics.com">
	<link rel="dns-prefetch" href="https://ssl.google-analytics.com">
	<link rel="dns-prefetch" href="https://ad.doubleclick.net">
	<link rel="dns-prefetch" href="https://googleads.g.doubleclick.net">
	<link rel="dns-prefetch" href="https://stats.g.doubleclick.net">
	<link rel="dns-prefetch" href="https://cm.g.doubleclick.net">
	<link rel="dns-prefetch" href="https://www.googletagmanager.com">
	<link rel="dns-prefetch" href="https://www.googletagservices.com">
	<link rel="dns-prefetch" href="https://adservice.google.com">
	<link rel="dns-prefetch" href="https://pagead2.googlesyndication.com">
	<link rel="dns-prefetch" href="https://tpc.googlesyndication.com">
	<link rel="dns-prefetch" href="https://youtube.com">
	<link rel="dns-prefetch" href="https://i.ytimg.com">
	<link rel="dns-prefetch" href="https://api.pinterest.com">
	<link rel="dns-prefetch" href="https://pixel.wp.com">
	<link rel="dns-prefetch" href="https://connect.facebook.net">
	<link rel="dns-prefetch" href="https://platform.twitter.com">
	<link rel="dns-prefetch" href="https://syndication.twitter.com">
	<link rel="dns-prefetch" href="https://platform.instagram.com">
	<link rel="dns-prefetch" href="https://platform.linkedin.com">
	<link rel="dns-prefetch" href="https://disqus.com">
	<link rel="dns-prefetch" href="https://sitename.disqus.com">
	<link rel="dns-prefetch" href="https://s7.addthis.com">
	<link rel="dns-prefetch" href="https://w.sharethis.com">
	<link rel="dns-prefetch" href="https://s1.wp.com">
	<link rel="dns-prefetch" href="https://1.gravatar.com">
	<link rel="dns-prefetch" href="https://s.gravatar.com">
	<link rel="dns-prefetch" href="https://stats.wp.com">
	<link rel="dns-prefetch" href="https://securepubads.g.doubleclick.net">
	<link rel="dns-prefetch" href="https://ajax.microsoft.com">
	<link rel="dns-prefetch" href="https://s3.amazonaws.com">
	<link rel="dns-prefetch" href="https://a.opmnstr.com">
	<link rel="dns-prefetch" href="https://script.hotjar.com">
	<link rel="dns-prefetch" href="https://code.jquery.com">
	<link rel="dns-prefetch" href="https://player.vimeo.com">
	<link rel="dns-prefetch" href="https://github.githubassets.com">
	<link rel="dns-prefetch" href="https://referrer.disqus.com">
	<link rel="dns-prefetch" href="https://stats.buysellads.com">
	<link rel="dns-prefetch" href="https://s3.buysellads.com">
	<link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
	<link rel="dns-prefetch" href="https://fonts.googleapis.com">
	<link rel="dns-prefetch" href="https://pro.fontawesome.com">
	<link rel="dns-prefetch" href="https://stackpath.bootstrapcdn.com">
	';
}
add_action('wp_head', 'dns_prefetch_247', 0);
/*End Add DNS Prefetch*/

/*Add browser identification to the body classes*/
function browser_body_class_247($classes) {
        global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
        if($is_lynx) $classes[] = 'lynx';
        elseif($is_gecko) $classes[] = 'gecko';
        elseif($is_opera) $classes[] = 'opera';
        elseif($is_NS4) $classes[] = 'ns4';
        elseif($is_safari) $classes[] = 'safari';
        elseif($is_chrome) $classes[] = 'chrome';
        elseif($is_IE) {
                $classes[] = 'ie';
                if(preg_match('/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $_SERVER['HTTP_USER_AGENT'], $browser_version))
                $classes[] = 'ie'.$browser_version[1];
        } else $classes[] = 'unknown';
        if($is_iphone) $classes[] = 'iphone';
        if ( stristr( $_SERVER['HTTP_USER_AGENT'],"mac") ) {
                 $classes[] = 'osx';
           } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"linux") ) {
                 $classes[] = 'linux';
           } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"windows") ) {
                 $classes[] = 'windows';
           }
        return $classes;
}
add_filter('body_class','browser_body_class_247');

/*Defer parsing js*/
if ( ! is_admin() ) {
function defer_parsing_of_js ( $url ) {

    if ( FALSE === strpos( $url, '.js' ) ) return $url;
    if ( strpos( $url, 'jquery.js' ) ) return $url;
    return "$url' defer='defer";
}
add_filter( 'clean_url', 'defer_parsing_of_js', 11, 1 );
}
/*End Defer parsing js*/


/*Remove query string from static files*/
function remove_cssjs_ver_247( $src ) {
 if( strpos( $src, '?ver=' ) )
 $src = remove_query_arg( 'ver', $src );
 return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver_247', 10, 2 );
add_filter( 'script_loader_src', 'remove_cssjs_ver_247', 10, 2 );
/*Remove query string from static files*/



/*TURN OFF COMMENT SYSTEM*/
add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;
     
    if ($pagenow === 'edit-comments.php') {
        wp_safe_redirect(admin_url());
        exit;
    }
 
    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
 
    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});
 
// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
 
// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);
 
// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});
 
// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});
/*TURN OFF COMMENT SYSTEM*/

/*Show featured image in WP Grid*/
add_filter('manage_post_posts_columns', 'posts_featuredimage_custom_column');
function posts_featuredimage_custom_column($defaults) {
    $defaults['featured_image'] = 'Featured Image';
    return $defaults;
}

add_filter('manage_post_posts_custom_column', 'posts_featuredimage_column_data',10, 2);
function posts_featuredimage_column_data($column_name, $post_ID){
        if ($column_name == 'featured_image') {
        $post_thumbnail_id = get_post_thumbnail_id($post_ID);
        $src = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');
        if ($src) {
            echo '<img src="' . $src[0] . '"  height="100px"/>';
        }
    }
}