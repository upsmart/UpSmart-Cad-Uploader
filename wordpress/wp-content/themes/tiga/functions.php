<?php
/**
 * Theme functions file
 *
 * Contains all of the Theme's setup functions, custom functions,
 * custom Widgets, custom hooks, and Theme settings.
 * 
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		0.0.1
 *
 */

/**
 * Define Theme setup
 * 
 * @since 0.0.1
 */
add_action( 'after_setup_theme', 'tiga_setup' );

function tiga_setup() {

	global $content_width;

	/* Sets the theme version number. */
	define( 'TIGA_VERSION', 1.1 );

	/* Sets the path to the theme directory. */
	define( 'THEME_DIR', get_template_directory() );

	/* Sets the path to the theme directory URI. */
	define( 'THEME_URI', get_template_directory_uri() );

	/* Sets the path to the admin directory. */
	define( 'TIGA_ADMIN', trailingslashit( THEME_DIR ) . 'admin' );

	/* Sets the path to the includes directory. */
	define( 'TIGA_INCLUDES', trailingslashit( THEME_DIR ) . 'includes' );

	/* Sets the path to the js directory. */
	define( 'TIGA_IMAGE', trailingslashit( THEME_URI ) . 'img' );

	/* Sets the path to the js directory. */
	define( 'TIGA_CSS', trailingslashit( THEME_URI ) . 'css' );

	/* Sets the path to the js directory. */
	define( 'TIGA_JS', trailingslashit( THEME_URI ) . 'js' );

	/* Loads the Options Panel. */
	if ( !function_exists( 'optionsframework_init' ) ) {

		define( 'OPTIONS_FRAMEWORK_DIRECTORY', trailingslashit( get_template_directory_uri() ) . 'admin/' );
		require_once dirname( __FILE__ ) . '/admin/options-framework.php';

		/* Options panel extras. */
		require( trailingslashit( TIGA_INCLUDES ) . 'options-functions.php' );
		require( trailingslashit( TIGA_INCLUDES ) . 'options-sidebar.php' );

	}

	/* Loads the template tags. */
	require( trailingslashit( TIGA_INCLUDES ) . 'templates.php' );

	/* Loads the theme hooks. */
	require( trailingslashit( TIGA_INCLUDES ) . 'hooks.php' );

	/* Loads the theme metabox. */
	require( trailingslashit( TIGA_INCLUDES ) . 'metabox.php' );

	/* Set the content width based on the theme's design and stylesheet. */
	if ( ! isset( $content_width ) ) $content_width = 620;

	/* Embed width defaults. */
	add_filter( 'embed_defaults', 'tiga_embed_defaults' );
	
	/* Make tiga available for translation. */
	load_theme_textdomain( 'tiga', trailingslashit( THEME_DIR ) . 'languages' );

	/* WordPress theme support */
	add_editor_style();
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 
		'custom-background',
		array(
			'default-image' => trailingslashit( TIGA_IMAGE ) . 'pattern.png',
		)
	);
	register_nav_menus( 
		array(
			'primary' => __( 'Primary Navigation', 'tiga' ),
			'secondary' => __( 'Secondary Navigation', 'tiga' )
		) 
	);
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'tiga-140px' , 140, 140, true );
	add_image_size( 'tiga-300px' , 300, 130, true );
	add_image_size( 'tiga-700px' , 700, 300, true );
	add_image_size( 'tiga-620px' , 620, 350, true );
	add_image_size( 'tiga-460px' , 460, 300, true );

	/* Enqueue styles. */
	add_action( 'wp_enqueue_scripts', 'tiga_enqueue_styles' );

	/* Deregister wp-pagenavi plugin style. */
	add_action( 'wp_print_styles', 'tiga_deregister_styles', 100 );

	/* Enqueue scripts. */
	add_action( 'wp_enqueue_scripts', 'tiga_enqueue_scripts' );
	
	/* Fallback script for IE. */
	add_action( 'wp_footer', 'tiga_js_ie' );

	/* Comment reply js */
	add_action( 'comment_form_before', 'tiga_enqueue_comment_reply_script' );

	/* Remove gallery inline style */
	add_filter( 'use_default_gallery_style', '__return_false' );

	/* wp_title filter. */
	add_filter( 'wp_title', 'tiga_title' );

	/* Replace [...] */
	add_filter( 'excerpt_more', 'tiga_auto_excerpt_more' );

	/* Add 'Continue Reading' */
	add_filter( 'get_the_excerpt', 'tiga_custom_excerpt_more' );

	/* Stop more link from jumping to middle of page. */
	add_filter( 'the_content_more_link', 'tiga_remove_more_jump_link' );

	/* Add custom class to the body. */
	add_filter( 'body_class', 'tiga_body_classes' );

	/* Filter in a link to a content ID attribute for the next/previous image links on image attachment pages. */
	add_filter( 'attachment_link', 'tiga_enhanced_image_navigation', 10, 2 );

	/* Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link. */
	add_filter( 'wp_page_menu_args', 'tiga_page_menu_args' );

	/* Remove div from wp_page_menu() and replace with ul. */
	add_filter( 'wp_page_menu', 'tiga_wp_page_menu' );

	/* Customize tag cloud widget. */
	add_filter( 'widget_tag_cloud_args', 'tiga_new_tag_cloud' );

	/* HTML5 tag for image and caption. */
	add_filter( 'img_caption_shortcode', 'tiga_html5_caption', 10, 3 );

	/* Allow shortcode in widget. */
	add_filter( 'widget_text', 'do_shortcode' );

	/* Register additional widgets. */
	add_action( 'widgets_init', 'tiga_register_widgets' );

	/* Register custom sidebar. */
	add_action( 'widgets_init', 'tiga_register_custom_sidebars' );

	/* Removes default styles set by WordPress recent comments widget. */
	add_action( 'widgets_init', 'tiga_remove_recent_comments_style' );

} // end tiga_setup

/**
 * Overwrites the default widths for embeds.  This is especially useful for making sure videos properly
 * expand the full width on video pages.  This function overwrites what the $content_width variable handles
 * with context-based widths.
 *
 * @since 1.0
 */
function tiga_embed_defaults( $args ) {
	
	$args['width'] = 620;
	
	$layout = of_get_option( 'tiga_layouts' );

	if ( 'onecolumn' == $layout )
		$args['width'] = 700;

	return $args;
}

/**
 * Enqueue styles
 *
 * @since 0.0.1
 */
function tiga_enqueue_styles() {

	wp_enqueue_style( 'tiga-font', 'http://fonts.googleapis.com/css?family=Francois+One|Open+Sans:400italic,400,700', '', TIGA_VERSION, 'all' );

	wp_enqueue_style( 'tiga-style', get_stylesheet_uri(), '', TIGA_VERSION, 'all' );

}

/**
 * Deregistering default wp-pagenavi style
 *
 * @since 0.0.1
 */
function tiga_deregister_styles() {
	wp_deregister_style( 'wp-pagenavi' );
}

/**
 * Enqueue scripts
 *
 * @since 0.0.1
 */
function tiga_enqueue_scripts() {
	global $post;

	wp_enqueue_script( 'jquery' );
	
	wp_enqueue_script( 'tiga-modernizr', trailingslashit( TIGA_JS ) . 'vendor/modernizr-2.6.2.min.js', array('jquery'), '2.6.1' );

	if ( is_singular() && wp_attachment_is_image( $post->ID ) ) {
		wp_enqueue_script( 'tiga-keyboard-image-navigation', trailingslashit( TIGA_JS ) . 'vendor/keyboard-image-navigation.js', array( 'jquery' ), TIGA_VERSION, true );
	}
	
	if ( is_singular() && of_get_option('tiga_social_share') ) {
		wp_enqueue_script( 'tiga-social-share', trailingslashit( TIGA_JS ) . 'vendor/social-share.js', array( 'jquery' ), TIGA_VERSION, true );
	}
	
	wp_enqueue_script( 'tiga-plugins', trailingslashit( TIGA_JS ) . 'plugins.js', array('jquery'), TIGA_VERSION, true );
	
	wp_enqueue_script( 'tiga-methods', trailingslashit( TIGA_JS ) . 'methods.js', array('jquery'), TIGA_VERSION, true );

}

/**
 * Fallback script for IE
 *
 * @since 0.0.3
 */
function tiga_js_ie() { ?>

	<!--[if (gte IE 6)&(lte IE 8)]>
		<script src="<?php echo trailingslashit( TIGA_JS ) . 'vendor/selectivizr-min.js'; ?>"></script>
	<![endif]-->
	
<?php 
}

/**
 * Comment reply js
 *
 * @since 0.2
 */
function tiga_enqueue_comment_reply_script() {

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}

/**
 * wp_title filter
 * Credit: Thematic theme
 *
 * @since 0.0.3
 */
function tiga_title( $wp_doctitle ) {
   
 	if ( is_feed() || !tiga_seo() )
    	return $wp_doctitle;

	$site_name = get_bloginfo('name' , 'display');
	$separator = apply_filters('tiga_doctitle_separator', '|');
			
	if ( is_single() ) {
		$content = single_post_title('', FALSE);
	}
	elseif ( is_home() || is_front_page() ) { 
		$content = get_bloginfo('description', 'display');
	}
	elseif ( is_page() ) { 
		$content = single_post_title('', FALSE); 
	}
	elseif ( is_search() ) { 
		$content = __('Search Results for:', 'tiga'); 
		$content .= ' ' . get_search_query();
	}
	elseif ( is_category() ) {
		$content = __('Category Archives:', 'tiga');
		$content .= ' ' . single_cat_title('', FALSE);;
	}
	elseif ( is_tag() ) { 
		$content = __('Tag Archives:', 'tiga');
		$content .= ' ' . tiga_tag_query();
	}
	elseif ( is_404() ) { 
		$content = __('Not Found', 'tiga'); 
	}
	else { 
		$content = get_bloginfo('description', 'display');
	}

	if ( get_query_var('paged') ) {
		$content .= ' ' .$separator. ' ';
		$content .= 'Page';
		$content .= ' ';
		$content .= get_query_var('paged');
	}

	if($content) {
		if ( is_home() || is_front_page() ) {
			$elements = array(
				'site_name' => $site_name,
				'separator' => $separator,
				'content' => $content
			);
		}
		else {
			$elements = array(
				'content' => $content
			);
		}  
	} else {
		$elements = array(
			'site_name' => $site_name
		);
	}

	// Filters should return an array
	$elements = apply_filters( 'tiga_doctitle', $elements );
	
	// But if they don't, it won't try to implode
	if( is_array($elements) ) {
		$doctitle = implode(' ', $elements);
	}
	else {
		$doctitle = $elements;
	}
	
	$doctitle = $doctitle;
	
	echo $doctitle;
   
}

/**
 * Create nice multi_tag_title
 * Credit: Thematic theme
 *
 * @since Tiga 0.0.3
 */
function tiga_tag_query() {

	$nice_tag_query = get_query_var( 'tag' ); // tags in current query
	$nice_tag_query = str_replace(' ', '+', $nice_tag_query); // get_query_var returns ' ' for AND, replace by +
	$tag_slugs = preg_split('%[,+]%', $nice_tag_query, -1, PREG_SPLIT_NO_EMPTY); // create array of tag slugs
	$tag_ops = preg_split('%[^,+]*%', $nice_tag_query, -1, PREG_SPLIT_NO_EMPTY); // create array of operators

	$tag_ops_counter = 0;
	$nice_tag_query = '';

	foreach ($tag_slugs as $tag_slug) { 
		$tag = get_term_by('slug', $tag_slug ,'post_tag');
		// prettify tag operator, if any
		if ( isset($tag_ops[$tag_ops_counter])  &&  $tag_ops[$tag_ops_counter] == ',') {
			$tag_ops[$tag_ops_counter] = ', ';
		} elseif ( isset( $tag_ops[$tag_ops_counter])  &&  $tag_ops[$tag_ops_counter] == '+') {
			$tag_ops[$tag_ops_counter] = ' + ';
		}
		// concatenate display name and prettified operators
		if ( isset( $tag_ops[$tag_ops_counter] ) ) {
			$nice_tag_query = $nice_tag_query.$tag->name.$tag_ops[$tag_ops_counter];
			$tag_ops_counter += 1;
		} else {
			$nice_tag_query = $nice_tag_query.$tag->name;
			$tag_ops_counter += 1;
		}
	}
	return $nice_tag_query;

}

/**
 * Switch Tiga SEO functions on or off
 * 
 * Provides compatibility with SEO plugins: All in One SEO Pack, HeadSpace, 
 * Platinum SEO Pack, wpSEO and Yoast SEO. Default: ON
 * 
 * Credit: Thematic theme
 * @since Tiga 0.1
 */
function tiga_seo() {

	if ( class_exists('All_in_One_SEO_Pack') || class_exists('HeadSpace_Plugin') || class_exists('Platinum_SEO_Pack') || class_exists('wpSEO') || defined('WPSEO_VERSION') ) {
		$content = FALSE;
	} else {
		$content = true;
	}
		return apply_filters( 'tiga_seo', $content );

}

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since 0.0.1
 */
function tiga_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'tiga' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with 
 * an ellipsis and tiga_continue_reading_link().
 *
 * @since 0.0.1
 */
function tiga_auto_excerpt_more( $more ) {
	return ' &hellip;' . tiga_continue_reading_link();
}

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * @since 0.0.1
 */
function tiga_custom_excerpt_more( $output ) {

	if ( has_excerpt() && ! is_attachment() ) {
		$output .= tiga_continue_reading_link();
	}
	return $output;

}

/**
 * Stop more link from jumping to middle of page
 *
 * @since 0.0.1
 */
function tiga_remove_more_jump_link($link) {

	$offset = strpos($link, '#more-');
	if ($offset) {
		$end = strpos($link, '"',$offset);
	}
	if ($end) {
		$link = substr_replace($link, '', $offset, $end-$offset);
	}
	return $link;

}

/**
 * Adds custom classes to the array of body classes.
 *
 * @since 0.0.1
 */
function tiga_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'multi-author';
	}

	return $classes;

}

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 *
 * @since 0.0.1
 */
function tiga_enhanced_image_navigation( $url, $id ) {

	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
		return $url;

	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
		$url .= '#main';

	return $url;

}

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since 0.0.1
 */
function tiga_page_menu_args( $args ) {

	$args['show_home'] = true;
	return $args;

}

/**
 * Remove div from wp_page_menu() and replace with ul
 *
 * @since 0.0.1
 */
function tiga_wp_page_menu ($page_markup) {
    preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches);
        $divclass = $matches[1];
        $replace = array('<div class="'.$divclass.'">', '</div>');
        $new_markup = str_replace($replace, '', $page_markup);
        $new_markup = preg_replace('/^<ul>/i', '<ul class="'.$divclass.'">', $new_markup);
        return $new_markup; 
	}

/**
 * Customize tag cloud widget
 *
 * @since 0.0.1
 */
function tiga_new_tag_cloud( $args ) {
	$args['largest'] 	= 12;
	$args['smallest'] 	= 12;
	$args['unit'] 		= 'px';
	return $args;
}

/**
 * HTML5 tag for image and caption
 *
 * @since Tiga 0.2.1
 */
function tiga_html5_caption( $output, $attr, $content ) {

	/* We're not worried abut captions in feeds, so just return the output here. */
	if ( is_feed() )
		return $output;

	/* Set up the default arguments. */
	$defaults = array(
		'id' => '',
		'align' => 'alignnone',
		'width' => '',
		'caption' => ''
	);

	/* Merge the defaults with user input. */
	$attr = shortcode_atts( $defaults, $attr );

	/* If the width is less than 1 or there is no caption, return the content wrapped between the [caption]< tags. */
	if ( 1 > $attr['width'] || empty( $attr['caption'] ) )
		return $content;

	/* Set up the attributes for the caption <div>. */
	$attributes = ( !empty( $attr['id'] ) ? ' id="' . esc_attr( $attr['id'] ) . '"' : '' );
	$attributes .= ' class="wp-caption ' . esc_attr( $attr['align'] ) . '"';
	$attributes .= ' style="width: ' . esc_attr( $attr['width'] ) . 'px"';

	/* Open the caption <figure>. */
	$output = '<figure' . $attributes .'>';

	/* Allow shortcodes for the content the caption was created for. */
	$output .= do_shortcode( $content );

	/* Append the caption text. */
	$output .= '<figcaption class="wp-caption-text">' . $attr['caption'] . '</figcaption>';

	/* Close the caption </figure>. */
	$output .= '</figure>';

	/* Return the formatted, clean caption. */
	return $output;

}

/**
 * Registers extra widgets.
 * 
 * @since 1.0
 */
function tiga_register_widgets() {

	require_once( trailingslashit( TIGA_INCLUDES ) . 'widget-social.php' );
	register_widget( 'tiga_social' );

	require_once( trailingslashit( TIGA_INCLUDES ) . 'widget-subscribe.php' );
	register_widget( 'tiga_subscribe' );

	require_once( trailingslashit( TIGA_INCLUDES ) . 'widget-twitter.php' );
	register_widget( 'tiga_twitter' );

	require_once( trailingslashit( TIGA_INCLUDES ) . 'widget-fbfans.php' );
	register_widget( 'tiga_fb_box' );

}

/**
 * Registers custom sidebars.
 * 
 * @since 0.0.1
 */
function tiga_register_custom_sidebars() {

    register_sidebar(array(
    	'id'			=> 'primary',
		'name'          => __( 'Primary', 'tiga'),
		'description'   => __( 'Primary sidebar, appears on all pages.', 'tiga' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<span class="widget-title">',
		'after_title'   => '</span>',
	));
	
	register_sidebar(array(
		'id'			=> 'subsidiary',
		'name'          => __( 'Subsidiary', 'tiga'),
		'description'   => __( 'Subsidiary sidebar, appears on the footer side of your site.', 'tiga' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<span class="widget-title">',
		'after_title'   => '</span>',
	));

	register_sidebar(array(
		'id'			=> 'above-content',	
		'name'          => __( 'Above Single Post Content', 'tiga'),
		'description'   => __( 'This sidebar appears on the single post, above the content.', 'tiga' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<span class="widget-title">',
		'after_title'   => '</span>',
	));

	register_sidebar(array(
		'id'			=> 'below-content',	
		'name'          => __( 'Below Single Post Content', 'tiga'),
		'description'   => __( 'This sidebar appears on the single post, below the content.', 'tiga' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<span class="widget-title">',
		'after_title'   => '</span>',
	));

	register_sidebar(array(
		'id'			=> 'home',	
		'name'          => __( 'Custom Home Page', 'tiga'),
		'description'   => __( 'This sidebar appears on custom home page template.', 'tiga' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<span class="widget-title">',
		'after_title'   => '</span>',
	));

}

/**
 * Count the number of widgets to enable dynamic classes
 *
 * @since 1.0
 */
function tiga_dynamic_sidebar_class( $sidebar_id ) {

	$sidebars = wp_get_sidebars_widgets();
	$get_count = count( $sidebars[$sidebar_id] );

	$class = '';

	switch ( $get_count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
		case '4':
			$class = 'four';
			break;
	}

	if ( $class )
		echo $class;

}

/**
 * Removes default styles set by WordPress recent comments widget.
 *
 * @since 0.0.1
 */
function tiga_remove_recent_comments_style() {

	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );

}
?>