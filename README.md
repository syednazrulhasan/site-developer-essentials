# The purpose of code on this repo is to improve development time of a wordrpess developer by some simple tweaks

## What is in it

Well just couple of hooks to make life easier as a developer. 


### Overview

There are certain area where wordpress might consider improving in future like 

1- While you are logged in backend and looking at frontend you are able to go directly to [Dashboard,Themes,Widgets & Menus](https://imgur.com/fGrMLfq) but what about Media, Plugins, Pages.

2- There should be an way to turn off default Block Editor by default

3- Wordpress does not have a in built mechanism to change the backend logo

4- Every now and then we need to upload SVG images in wordpress media to improve clariity of image however we need to add support for SVG files

5- Wordpress includes lot of scripts related to emojis which are not used in general site most of time 

6- Sometimes developers need a way to identify or target particular browser with body class 

7- Defer parsing of JavaScripts

8- Removing query strings from static resource 

9- Many sites dont actually need comments systems from legacy wordpress

10- There is no way to identify the featured image used in wordpress posts by default in All Posts Grid

11- Sometimes we need to see a glimpse of site at a glance like which ones are active themes what is timezone what are database credentials web server Public IP for DNS A record, no of active plugins, site url, home url, admin email, current user role. Upload Directory etc for that [this plugin can be used](https://github.com/syednazrulhasan/site-at-glance)



### We covered all in the code **Should be used for Development Purpose Only**

To disable block editor you can use following codes in active themes `functions.php` 

```
add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 100);
add_filter('gutenberg_use_widgets_block_editor', '__return_false', 100 );
add_filter('use_widgets_block_editor', '__return_false' );
```

To enable Media links under admin node you can add following code in active themes `functions.php`
```
add_action( 'admin_bar_menu', 'admin_bar_links_247',999);

function admin_bar_links_247($admin_bar) {         

    $args = array(
        'parent' => 'site-name',
        'id'     => 'media-libray',
        'title'  => 'Media',
        'href'   => esc_url( admin_url( 'upload.php' ) ),
        'meta'   => false
        );
    $admin_bar->add_node( $args );

}
```

To have page show below black admin on frontend can add following code in active themes `functions.php`

```
add_action('wp_head', function () {
   if(is_user_logged_in()){
    echo '<style>.admin-bar header {
        margin-top: 32px
        }</style>';
   }
});
```

You can create a theme option for backend logo with ACF Theme options field by using following code in active themes `functions.php`

```

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
```

To disable emojis you can use following code in active themes `functions.php`

```
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
```

You can always add aa body class to your browser or even extend following function or directly use following code in active themes `functions.php`

```
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
```

Defer Parsing of Javascript 
```
if ( ! is_admin() ) {
function defer_parsing_of_js ( $url ) {

    if ( FALSE === strpos( $url, '.js' ) ) return $url;
    if ( strpos( $url, 'jquery.js' ) ) return $url;
    return "$url' defer='defer";
}
add_filter( 'clean_url', 'defer_parsing_of_js', 11, 1 );
}
```
Removing query string from browser resource urls 

```
function remove_cssjs_ver_247( $src ) {
 if( strpos( $src, '?ver=' ) )
 $src = remove_query_arg( 'ver', $src );
 return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver_247', 10, 2 );
add_filter( 'script_loader_src', 'remove_cssjs_ver_247', 10, 2 );
```

Entirely remove comment setup from wordpress in case your site doesnt have a blog

```
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
```
Showing featured image in All posts Grid 

```
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

```
Helper function to show SVG paths for static resources for animation purpose 

```
function get_svg_inline($path, $style = '') {
   if ( stripos( $path, '://' ) !== FALSE ) {
       $path = str_replace( get_bloginfo('url'), ABSPATH, $path );
       $path = str_replace( '//', '/', $path );
   }
  
   $svg = file_get_contents($path);
  
   if ( $style ) {
       $svg = str_replace( '<svg ', '<svg class="icon" style="' . $style . '" ', $svg );
   }
   return $svg;
   }
  
   function svg_inline($path, $style = '') {
   echo get_svg_inline($path, $style);
}
```
and you can use this code as 
```
 <?php svg_inline( get_template_directory_uri() . '/app/images/loader.svg' )?>   
```

An small tweak to make the update button [sticky](https://imgur.com/lpfMuI4)  for long posts so you dont need to scroll up to hit update to save changes 

```
add_action('admin_enqueue_scripts', 'sticky_publish_247');
function sticky_publish_247() {
  echo '<style>
        #publishing-action {
            position: fixed;
            right: 0;
            top: 150px;
            z-index:100;
        }   
        </style>';
}
```

Wordpress doesnt allows for SVG files upload in media library for that you can apply a simple tweak to your functions.php of active theme.
```
function upload_svg_files_247( $allowed ) {
    if ( !current_user_can( 'manage_options' ) )
        return $allowed;
    $allowed['svg'] = 'image/svg+xml';
    return $allowed;
}
add_filter( 'upload_mimes', 'upload_svg_files_247');
```

Also make sure to add following lines to your `wp-config.php`  when you add above svg upload code to `functions.php`

```
define( 'ALLOW_UNFILTERED_UPLOADS', true );
```

