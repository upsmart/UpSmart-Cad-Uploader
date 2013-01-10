<?php
/* 	DISCUSSION Theme's Functions
	Copyright: 2012, D5 Creation, www.d5creation.com
	Based on the Simplest D5 Framework for WordPress
	Since DISCUSSION 1.0
*/
   
  
  	add_theme_support( 'automatic-feed-links' );
  	register_nav_menu( 'main-menu', 'Main Menu' );
 	

//	Set the content width based on the theme's design and stylesheet.
	if ( ! isset( $content_width ) ) $content_width = 584;

// 	Tell WordPress for wp_title in order to modify document title content
	function discussion_filter_wp_title( $title ) {
    $discussion_site_name = get_bloginfo( 'name' );
    $discussion_filtered_title = $discussion_site_name . $title;
    return $discussion_filtered_title;
	}
	add_filter( 'wp_title', 'discussion_filter_wp_title' );

	add_editor_style();
		
// 	This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
	 
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 150, 150, true ); // default Post Thumbnail dimensions (cropped)
		
// 	WordPress 3.4 Custom Background Support	
	$discussion_custom_background = array(
	'default-color'          => '966c40',
	'default-image'          => get_template_directory_uri() . '/images/back.png',
	);
	add_theme_support( 'custom-background', $discussion_custom_background );
	
// 	WordPress 3.4 Custom Header Support				
	$discussion_custom_header = array(
	'default-image'          => get_template_directory_uri() . '/images/logo.png',
	'random-default'         => false,
	'width'                  => 450,
	'height'                 => 120,
	'flex-height'            => false,
	'flex-width'             => false,
	'default-text-color'     => '000000',
	'header-text'            => false,
	'uploads'                => true,
	'wp-head-callback' 		 => '',
	'admin-head-callback'    => '',
	'admin-preview-callback' => '',
	);
	add_theme_support( 'custom-header', $discussion_custom_header );

// 	Functions for adding script
	function discussion_enqueue_scripts() { ?>
	<!--[if lt IE 9]>
	<?php wp_enqueue_script( 'html5forie', get_template_directory_uri(). '/js/html5.js'); ?>
	<![endif]-->
	<?php
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { 
		wp_enqueue_script( 'comment-reply' ); 
	}
	
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'discussion-menu-style', get_template_directory_uri(). '/js/menu.js' );
	wp_enqueue_style('discussion-gfonts1', 'http://fonts.googleapis.com/css?family=Oswald', false );
	
	}
	add_action( 'wp_enqueue_scripts', 'discussion_enqueue_scripts' );


//	Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and discussion_continue_reading_link().
//	function tied to the excerpt_more filter hook.
	function discussion_excerpt_length( $length ) {
	global $discussion_ExcerptLength;
	if ($discussion_ExcerptLength) {
    return $discussion_ExcerptLength;
	} else {
    return 50; //default value
    } }
	add_filter( 'excerpt_length', 'discussion_excerpt_length', 999 );
	
	function discussion_excerpt_more($more) {
       global $post;
	return '<a href="'. get_permalink($post->ID) . '" class="read-more">Read the Rest...</a>';
	}
	add_filter('excerpt_more', 'discussion_excerpt_more');


//	Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link
	function discussion_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
	}
	add_filter( 'wp_page_menu_args', 'discussion_page_menu_args' );


//	Registers the Widgets and Sidebars for the site
	function discussion_widgets_init() {

	register_sidebar( array(
		'name' =>  'Primary Sidebar', 
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' =>  'Secondary Sidebar', 
		'id' => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	}
	add_action( 'widgets_init', 'discussion_widgets_init' );

	add_filter('the_title', 'discussion_title');
	function discussion_title($title) {
        if ( '' == $title ) {
            return '(Untitled)';
        } else {
            return $title;
        }
    }

//	Remove WordPress Custom Header Support for the theme discussion
//	remove_theme_support('custom-header');