<?php

// Set the content width
if ( ! isset( $content_width ) )
	$content_width = 598; /* pixels */

/*-----------------------------------------------------------------------------------*/
/* Theme setup
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'gamepress_setup' ) ):

function gamepress_setup() {



	// Enable theme translations
	load_theme_textdomain( 'gamepress', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Add stylesheet for the WYSIWYG editor
	add_editor_style();
	
	// Image thumbnails
	if (function_exists('add_theme_support')) {
		add_theme_support('post-thumbnails');
		set_post_thumbnail_size(80, 80, true);
		add_image_size('article-thumb', 200, 200, true);
		add_image_size('gamecover', 137, 161, true);
		add_image_size('gamecover-thumb', 100, 118, true);
		add_image_size('video-thumb', 284, 158, true);
		add_image_size('nivo-slide', 642, 362, true);
		add_image_size('nivo-thumb', 128, 72, true);
	}
	
	// Register menu
	register_nav_menus( array(
		'primary-menu' => __('GamePress Main Menu','gamepress'),
	) );
	
	// Clean up the <head>
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'wp_generator');
	
	// Custom backgrounds support
	$defaults = array(
	'default-color'          => '',
	'default-image'          => get_template_directory_uri().'/images/body-bg1.png',
	'wp-head-callback'       => 'gamepress_custom_background_callback'
	);
	add_theme_support( 'custom-background', $defaults );
	
	
}
endif;

add_action( 'after_setup_theme', 'gamepress_setup' );

function gamepress_custom_background_callback() {

	/* Get the background image. */
	$image = get_background_image();

	/* If there's an image, just call the normal WordPress callback. We won't do anything here. */
	if ( !empty( $image ) ) {
		_custom_background_cb();
		return;
	}

	/* Get the background color. */
	$color = get_background_color();

	/* If no background color, return. */
	if ( empty( $color ) )
		return;

	/* Use 'background' instead of 'background-color'. */
	$style = "background: #{$color};";

?>
<style type="text/css">body { <?php echo trim( $style ); ?> }</style>
<?php

}

/*-----------------------------------------------------------------------------------*/
/* JavaScript & CSS
/*-----------------------------------------------------------------------------------*/
function gamepress_enqueue_scripts()
{
	if (!is_admin()) {
        wp_enqueue_script('jquery');
		wp_register_script('gamepress', get_template_directory_uri() . '/js/gamepress.js',array('jquery'));
		wp_enqueue_script('gamepress');
		wp_register_script('jquery_tools', get_template_directory_uri() . '/js/jquery.tools.min.js');
		wp_enqueue_script('jquery_tools');
		wp_register_script('easing', get_template_directory_uri() . '/js/jquery.easing.1.3.js');
		wp_enqueue_script('easing');
		wp_register_script('modernizr', get_template_directory_uri() . '/js/modernizr-custom.min.js');
		wp_enqueue_script('modernizr');
		wp_register_script('placeholder', get_template_directory_uri() . '/js/jquery.placeholder.min.js');
		wp_enqueue_script('placeholder');
	}
	if (is_home()){
		wp_register_script('nivoslider', get_template_directory_uri() . '/js/nivo-slider/jquery.nivo.slider.pack.js');
		wp_enqueue_script('nivoslider');	
	}
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action('wp_enqueue_scripts', 'gamepress_enqueue_scripts');

function gamepress_enqueue_css()
{
	if (is_home()){
			wp_register_style('nivo_css', get_template_directory_uri() . '/js/nivo-slider/nivo-slider.css');
			wp_enqueue_style('nivo_css');
		if(of_get_option('gamepress_slider_type') == '1') {
			wp_register_style('nivo_default_css', get_template_directory_uri() . '/js/nivo-slider/nivo-default.css');
			wp_enqueue_style('nivo_default_css');
		}else {
			wp_register_style('nivo_thumb_css', get_template_directory_uri() . '/js/nivo-slider/nivo-thumbnails.css');
			wp_enqueue_style('nivo_thumb_css');
		}
	}
    wp_register_style('default_stylesheet', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('default_stylesheet');
}
add_action('wp_print_styles', 'gamepress_enqueue_css');

if ( ! function_exists( 'gamepress_enqueue_skin_css' ) ) {
    function gamepress_enqueue_skin_css() {

        wp_register_style('dark_stylesheet', get_template_directory_uri() . '/css/dark.css');
        wp_enqueue_style('dark_stylesheet');
        
        if(of_get_option('gamepress_color_scheme') && of_get_option('gamepress_color_scheme') != 'red') {
            wp_register_style('color_scheme', get_template_directory_uri() . '/images/' . of_get_option('gamepress_color_scheme','red') . '/style.css');
            wp_enqueue_style('color_scheme');
        }
    }
}
add_action('wp_print_styles', 'gamepress_enqueue_skin_css');

/*-----------------------------------------------------------------------------------*/
/* Display <title> tag
/*-----------------------------------------------------------------------------------*/

// filter function for wp_title
function gamepress_filter_wp_title( $old_title, $sep, $sep_location ){
 
    // add padding to the sep
    $ssep = ' ' . $sep . ' ';
     
    // find the type of index page this is
    if( is_category() ) $insert = $ssep . __('Category','gamepress');
    elseif( is_tag() ) $insert = $ssep . __('Tag','gamepress');
    elseif( is_author() ) $insert = $ssep . __('Author','gamepress');
    elseif( is_year() || is_month() || is_day() ) $insert = $ssep . __('Archives','gamepress');
    elseif( is_home() ) $insert = $ssep . get_bloginfo('description');
    else $insert = NULL;
     
    // get the page number we're on (index)
    if( get_query_var( 'paged' ) )
    $num = $ssep . __('Page ','gamepress') . get_query_var( 'paged' );
     
    // get the page number we're on (multipage post)
    elseif( get_query_var( 'page' ) )
    $num = $ssep . __('Page ','gamepress') . get_query_var( 'page' );
     
    // else
    else $num = NULL;

     
    // concoct and return new title
    return get_bloginfo( 'name' ) . $insert . $old_title . $num;
}

add_filter( 'wp_title', 'gamepress_filter_wp_title', 10, 3 );

/*-----------------------------------------------------------------------------------*/
/* Excerpt config
/*-----------------------------------------------------------------------------------*/
function gamepress_excerptlength_teaser($length) {
    return 20;
}
function gamepress_excerptlength_default($length) {
    return 40;
}
function gamepress_excerptmore($more)
{
	global $post;
	return '... <br /><a  class="more" href="' . get_permalink($post->ID) . '">'.__('Read more', 'gamepress').' &raquo;</a>';
}
function gamepress_morelink($more)
{
	global $post;
	return '... <a class="more_link" href="' . get_permalink($post->ID) . '">'.__('Read more', 'gamepress').' &raquo;</a>';
}

function gamepress_excerpt($length_callback='', $more_callback='') {
    global $post;
    if(function_exists($length_callback)){
        add_filter('excerpt_length', $length_callback);
    }
    if(function_exists($more_callback)){
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>'.$output.'</p>';
    echo $output;
}

/*-----------------------------------------------------------------------------------*/
/* Homepage main loop mods
/*-----------------------------------------------------------------------------------*/

function gamepress_home_pre_get_post ($query) {

    if (!is_admin() && is_home() && $query->is_main_query()) {
        $hp_include = of_get_option('gamepress_hp_include',array());
		$include_reviews = '';
		$include_videos = '';
		
		if(is_array($hp_include) && !empty($hp_include['gamepress_reviews'])) { $include_reviews = "gamepress_reviews"; }
		if(is_array($hp_include) && !empty($hp_include['gamepress_video'])) { $include_videos = "gamepress_video"; }

        $query->set( 'post_type', array(
            'post', $include_reviews, $include_videos
		));
        
        return $query;
    
    }
}
 
add_action('pre_get_posts', 'gamepress_home_pre_get_post');

/*-----------------------------------------------------------------------------------*/
/* Create custom post types
/*-----------------------------------------------------------------------------------*/
add_action( 'init', 'gamepress_register_reviews_post_type' );  

function gamepress_register_reviews_post_type() { 
	// Game reviews
    register_post_type( 'gamepress_reviews',  
        array(  
            'labels' => array(  
                'name' => __( 'Game Reviews', 'gamepress' ),  
                'singular_name' => __( 'Game Review', 'gamepress' ),  
                'add_new' => __('Add new review', 'gamepress'),  
                'edit_item' => __('Edit review', 'gamepress'),  
                'new_item' => __('New review', 'gamepress'),  
                'view_item' => __('View review', 'gamepress'),  
                'search_items' => __('Search reviews', 'gamepress'),  
                'not_found' => __('No reviews found', 'gamepress'),  
                'not_found_in_trash' => __('No reviews found in Trash', 'gamepress'),
            ),  
            'public' => true,  
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'exclude_from_search' => false,
			'hierarchical' => false,
            'menu_position' => 20, 
			'has_archive' => true,	
            'rewrite' => array('slug' => 'game-review'),  
            'supports' => array('title','editor','author','thumbnail','excerpt','trackbacks','comments','revisions'),  
            'taxonomies' => array('post_tag')  
        )  
    );
flush_rewrite_rules();
}

add_action( 'init', 'gamepress_register_videos_post_type' );

function gamepress_register_videos_post_type() {
	// Video
	register_post_type( 'gamepress_video',  
		array(
			'labels' => array(
				'name' => __( 'Videos','gamepress' ),
				'singular_name' => __( 'Video','gamepress' ),
				'add_new' => __( 'Add New','gamepress' ),
				'add_new_item' => __( 'Add New Video','gamepress' ),
				'edit' => __( 'Edit','gamepress' ),
				'edit_item' => __( 'Edit Video','gamepress' ),
				'new_item' => __( 'New Video','gamepress' ),
				'view' => __( 'View Video','gamepress' ),
				'view_item' => __( 'View Video','gamepress' ),
				'search_items' => __( 'Search Videos','gamepress' ),
				'not_found' => __( 'No videos found','gamepress' ),
				'not_found_in_trash' => __( 'No videos found in Trash','gamepress' ),
				'parent' => __( 'Parent Video','gamepress' ),
			), 
            'public' => true,  
			'publicly_queryable' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'exclude_from_search' => false,
			'hierarchical' => false,
            'menu_position' => 22, 
			'has_archive' => true,	
            'rewrite' => array('slug' => 'video'),  
            'supports' => array('title','editor','author','thumbnail','excerpt','comments'),  
            'taxonomies' => array('post_tag')  
        )  
    ); 
flush_rewrite_rules();
} 
/*-----------------------------------------------------------------------------------*/
/* Register custom taxonomies
/*-----------------------------------------------------------------------------------*/
add_action( 'init', 'gamepress_create_review_category', 0 );

function gamepress_create_review_category() 
{

  $labels = array(
    'name' => _x( 'Review Categories', 'taxonomy general name','gamepress' ),
    'singular_name' => _x( 'Review Category', 'taxonomy singular name','gamepress' ),
    'search_items' =>  __( 'Search Review Categories','gamepress' ),
    'all_items' => __( 'All Review Categories','gamepress' ),
    'parent_item' => __( 'Parent Review Category','gamepress' ),
    'parent_item_colon' => __( 'Parent Review Category:','gamepress' ),
    'edit_item' => __( 'Edit Review Category' ,'gamepress'), 
    'update_item' => __( 'Update Review Category','gamepress' ),
    'add_new_item' => __( 'Add New Review Category','gamepress' ),
    'new_item_name' => __( 'New Review Category Name','gamepress' ),
    'menu_name' => __( 'Categories','gamepress' ),
  ); 	

  register_taxonomy('gamepress_review_category',array('gamepress_reviews'), array(
	'public' => true,
	'show_in_nav_menus' => true,
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
	'rewrite' => array( 'slug' => 'reviews', 'with_front' => false ),
  ));
flush_rewrite_rules();
}

add_action( 'init', 'gamepress_create_video_category', 0 );

function gamepress_create_video_category() 
{

  $labels = array(
    'name' => _x( 'Video Categories', 'taxonomy general name','gamepress' ),
    'singular_name' => _x( 'Video Category', 'taxonomy singular name','gamepress' ),
    'search_items' =>  __( 'Search Video Categories','gamepress'),
    'all_items' => __( 'All Video Categories','gamepress' ),
    'parent_item' => __( 'Parent Video Category','gamepress' ),
    'parent_item_colon' => __( 'Parent Video Category:', 'gamepress' ),
    'edit_item' => __( 'Edit Video Category','gamepress' ), 
    'update_item' => __( 'Update Video Category','gamepress' ),
    'add_new_item' => __( 'Add New Video Category','gamepress' ),
    'new_item_name' => __( 'New Video Category Name','gamepress' ),
    'menu_name' => __( 'Categories','gamepress' ),
  ); 	

  register_taxonomy('gamepress_video_category',array('gamepress_video'), array(
	'public' => true,
	'show_in_nav_menus' => true,
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
  ));

}

add_filter('pre_get_posts', 'gamepress_query_post_type');
function gamepress_query_post_type($query) {
  if(is_tag()) {
    $post_type = get_query_var('post_type');
	if($post_type)
	    $post_type = $post_type;
	else
	    $post_type = array('post','nav_menu_item', 'gamepress_reviews', 'gamepress_video');
    $query->set('post_type',$post_type);
	return $query;
    }
}
/*-----------------------------------------------------------------------------------*/
/* Function to display rating bar & box
/*-----------------------------------------------------------------------------------*/
function gamepress_rating($rating) {
	
	$class = explode(".", $rating);
	$output = '';
	
	if($class[0] >= '10') {
		$output .= '<span class="score s10">'.$rating.'</span><span class="desc">'.__('Perfect','gamepress').'</span>';
	} elseif($class[0] >= '9') {
		$output .= '<span class="score s9">'.$rating.'</span><span class="desc">'.__('Amazing','gamepress').'</span>';
	} elseif($class[0] >= '8') {
		$output .= '<span class="score s8">'.$rating.'</span><span class="desc">'.__('Great','gamepress').'</span>';
	} elseif($class[0] >= '7') {
		$output .= '<span class="score s7">'.$rating.'</span><span class="desc">'.__('Good','gamepress').'</span>';
	} elseif($class[0] >= '6') {
		$output .= '<span class="score s6">'.$rating.'</span><span class="desc">'.__('Fair','gamepress').'</span>';
	} elseif($class[0] >= '5') {
		$output .= '<span class="score s5">'.$rating.'</span><span class="desc">'.__('Mediocre','gamepress').'</span>';
	} elseif($class[0] >= '4') {
		$output .= '<span class="score s4">'.$rating.'</span><span class="desc">'.__('Poor','gamepress').'</span>';
	} elseif($class[0] >= '3') {
		$output .= '<span class="score s3">'.$rating.'</span><span class="desc">'.__('Bad','gamepress').'</span>';
	} elseif($class[0] >= '2') {
		$output .= '<span class="score s2">'.$rating.'</span><span class="desc">'.__('Terrible','gamepress').'</span>';
	} else {
		$output .= '<span class="score s1">'.$rating.'</span><span class="desc">'.__('Abysmal','gamepress').'</span>';
	}
	return $output;
}

/*-----------------------------------------------------------------------------------*/
/* Append rating to the end of the post
/*-----------------------------------------------------------------------------------*/
function gamepress_display_rating($content) {

	global $post;
	
	if($post->post_type == 'gamepress_reviews' && get_post_meta($post->ID, "gamepress_score", true)) {
	
		$content .= '<h2>'. __('The Verdict','gamepress') .'</h2>';
		$content .= '<div class="rating">';
		$content .= '<div class="rating-box">';
		$content .= gamepress_rating(get_post_meta($post->ID, "gamepress_score", true));
		$content .= '</div>';
		
		if(get_post_meta($post->ID, "gamepress_the_good", true)) {
			$content .= '<p><span class="label">'. __( 'The Good: ', 'gamepress' ).'</span>'. get_post_meta($post->ID, "gamepress_the_good", true).'</p>';
		}
		if(get_post_meta($post->ID, "gamepress_the_bad", true)) { 
			$content .= '<p><span class="label">'. __( 'The Bad: ', 'gamepress' ).'</span>'. get_post_meta($post->ID, "gamepress_the_bad", true) .'</p>';
		}
		$content .= '</div>';
	
	}

	return $content;
}
add_filter('the_content','gamepress_display_rating');

/*-----------------------------------------------------------------------------------*/
/* Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
/*-----------------------------------------------------------------------------------*/
function gamepress_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'gamepress_page_menu_args' );

/*-----------------------------------------------------------------------------------*/
/* Disable Auto Formating on Posts
/*-----------------------------------------------------------------------------------*/
function gamepress_formating($content) {
	$new_content = '';
	$pattern_full = '{(\[raw\].*?\[/raw\])}is';
	$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
	$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

	foreach ($pieces as $piece) {
		if (preg_match($pattern_contents, $piece, $matches)) {
			$new_content .= $matches[1];
		} else {
			$new_content .= wptexturize(wpautop($piece));
		}
	}

	return $new_content;
}
add_filter('the_content', 'gamepress_formating', 99);

/*-----------------------------------------------------------------------------------*/
/* Fix wp-caption width
/*-----------------------------------------------------------------------------------*/

add_filter( 'img_caption_shortcode', 'gamepress_caption', 10, 3 );

function gamepress_caption( $output, $attr, $content ) {

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

	/* Open the caption <div>. */
	$output = '<div' . $attributes .'>';

	/* Allow shortcodes for the content the caption was created for. */
	$output .= do_shortcode( $content );

	/* Append the caption text. */
	$output .= '<p class="wp-caption-text">' . $attr['caption'] . '</p>';

	/* Close the caption </div>. */
	$output .= '</div>';

	/* Return the formatted, clean caption. */
	return $output;
}


/*-----------------------------------------------------------------------------------*/
/* Register widgetized area and update sidebar with default widgets
/*-----------------------------------------------------------------------------------*/
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Main Sidebar',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => 'Footer 1',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => 'Footer 2',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => 'Footer 3',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
}
// Include functions
include(get_template_directory() . "/admin/widgets/recent-posts-widget.php");
include(get_template_directory() . "/admin/widgets/social-widget.php");

/*-----------------------------------------------------------------------------------*/
/* Comments
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'gamepress_comment' ) ) :

function gamepress_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="the-comment">

				<?php echo get_avatar( $comment, 60 ); ?>

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'gamepress' ); ?></em>
					<br />
				<?php endif; ?>
				<div class="comment-meta">
					<?php echo get_comment_author_link() ?>
					<span class="comment-time">
						<small>
							<?php comment_time( 'F j, Y g:i a' ); ?></small>&nbsp;<small>
							<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						</small>
					</span>
					<div class="comment-text"><p><?php comment_text(); ?></p></div>
				</div>				

		</article>

	<?php
}
endif;

/*-----------------------------------------------------------------------------------*/
/* Shortcodes
/*-----------------------------------------------------------------------------------*/		
// Button
add_shortcode('button', 'gamepress_shortcode_button');
	function gamepress_shortcode_button($atts, $content = null) {
		$atts = shortcode_atts(
			array(
				'color' => 'red',
				'url' => '#',
				'size' => '',
			), $atts);
			
			if($atts['size'] == 'default'){
				$atts['size'] = '';
			}
			
			return '<a class="button ' . $atts['color'] .' '. $atts['size'] .'" href="' . $atts['url'] . '" >' .do_shortcode($content). '</a>';
	}
		
// Unorderd List
add_shortcode('list', 'gamepress_shortcode_list');
	function gamepress_shortcode_list( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'type' => 'arrow',
			), $atts);
			
	$content = str_replace('<ul>', '<ul class="list ' . $atts['type'] . '">', do_shortcode($content));
	$content = str_replace('<li>', '<li>', do_shortcode($content));
	
	return $content;
	
}

// Tabs
add_shortcode('tabs', 'gamepress_shortcode_tabs');
	function gamepress_shortcode_tabs( $atts, $content = null ) {
	extract(shortcode_atts(array(
    ), $atts));

	$out = '';
	$out .= '[raw]<div class="tabs-wrapper">[/raw]';
	
	$out .= '<ul class="tabs tabs-nav">';
	foreach ($atts as $tab) {
		$out .= '<li><a href="#">' .$tab. '</a></li>';
	}
	$out .= '</ul>';

	$out .= do_shortcode($content) .'[raw]</div>[/raw]';
	
	return $out;
}

add_shortcode('tab', 'gamepress_shortcode_tab');
	function gamepress_shortcode_tab( $atts, $content = null ) {
	extract(shortcode_atts(array(
    ), $atts));
	
	$out = '';
	$out .= '[raw]<div class="pane">[/raw]' . do_shortcode($content) .'</div>';
	
	return $out;
}
	
// Accordion
add_shortcode('accordion', 'gamepress_shortcode_accordion');
	function gamepress_shortcode_accordion( $atts, $content = null ) {
	extract(shortcode_atts(array(
    ), $atts));

	$out = '';
	$out .= '<div class="accordion">'.do_shortcode($content).'</div>';
	
   return $out;
}
add_shortcode('pane', 'gamepress_shortcode_pane');
	function gamepress_shortcode_pane( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'title' => '',
    ), $atts));
	
	$out = '';
	foreach ($atts as $pane) {
		$out .= '<h4 class="accordion-header"><a href="#">' . $pane .'</a></h4>';
		$out .= '<div class="pane">'. do_shortcode($content) .'</div>';
	}
	return $out;
}

/*-----------------------------------------------------------------------------------*/
/*	Add buttons to tinyMCE
/*-----------------------------------------------------------------------------------*/		
add_action('init', 'gamepress_add_button');

function gamepress_add_button() {  
   if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )  
   {  
     add_filter('mce_external_plugins', 'gamepress_add_plugin');  
     add_filter('mce_buttons_3', 'gamepress_register_button');  
   }  
}  

function gamepress_register_button($buttons) {  
   array_push($buttons, "button", "list", "tabs", "accordion");  
   return $buttons;  
}  

function gamepress_add_plugin($plugin_array) {  
   $plugin_array['button'] = get_template_directory_uri().'/admin/tinymce/customcodes.js';
   $plugin_array['list'] = get_template_directory_uri().'/admin/tinymce/customcodes.js';
   $plugin_array['tabs'] = get_template_directory_uri().'/admin/tinymce/customcodes.js';
   $plugin_array['accordion'] = get_template_directory_uri().'/admin/tinymce/customcodes.js';
   return $plugin_array;  
}  


/*-----------------------------------------------------------------------------------*/
/*	Meta-boxes setup
/*-----------------------------------------------------------------------------------*/
// Re-define meta box path and URL
define( 'RWMB_DIR', trailingslashit( get_template_directory() . '/meta-box' ) );
define( 'RWMB_URL', trailingslashit( get_template_directory_uri() . '/meta-box' ) );

// Include the meta box script
require_once RWMB_DIR . 'meta-box.php';

// Include the meta box definition (the file where you define meta boxes, see `demo/demo.php`)
include 'meta-box/metabox-def.php';

/*-----------------------------------------------------------------------------------*/
/*	Options framework
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/admin/options-framework/' );
	require_once dirname( __FILE__ ) . '/admin/options-framework/options-framework.php';
}

/*
 * This is an example of how to add custom scripts to the options panel.
 * This one shows/hides the an option when a checkbox is clicked.
 */

add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery('#example_showhidden').click(function() {
  		jQuery('#section-example_text_hidden').fadeToggle(400);
	});

	if (jQuery('#example_showhidden:checked').val() !== undefined) {
		jQuery('#section-example_text_hidden').show();
	}

});
</script>

<?php
}

// Theme Options sidebar
add_action( 'optionsframework_after','gamepress_options_display_sidebar' );

function gamepress_options_display_sidebar() { ?>
	<div id="optionsframework-sidebar">
		<div class="metabox-holder">
			<div class="postbox">
				<h3><?php _e('Support','gamepress') ?></h3>
					<div class="inside">
                        <p><?php _e('The best way to contact me with <b>support questions</b> and <b>bug reports</b> is via the','gamepress') ?> <a href="http://wordpress.org/support/"><?php _e('WordPress support forums','gamepress') ?></a>.</p>
                        <p><?php _e('If you like this theme, I\'d appreciate any of the following:','gamepress') ?></p>
                        <ul>
                            <li><a href="http://wordpress.org/extend/themes/gamepress"><?php _e('Rate GamePress at WordPress.org','gamepress') ?></a></li>
                            <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8LRJAUNAPKJ9S"><?php _e('Donate a token of your appreciation','gamepress') ?></a></li>
                        </ul>
					</div>
			</div>
		</div>
	</div>
<?php }
