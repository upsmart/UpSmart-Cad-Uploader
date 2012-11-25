<?php
/**
 * Tiga Theme Custom Filters
 * 
 * This file defines custom filters for the Tiga Theme.
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		Tiga 0.0.1
 *
 */

/**
 * wp_title filter
 * Credit: Thematic theme
 *
 * @since Tiga 0.0.3
 */
add_filter( 'wp_title', 'tiga_title' );
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
	$elements = apply_filters('tiga_doctitle', $elements);
	
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
 * @since Tiga 0.0.1
 */
function tiga_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'tiga' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and tiga_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Tiga 0.0.1
 */
add_filter( 'excerpt_more', 'tiga_auto_excerpt_more' );
function tiga_auto_excerpt_more( $more ) {
	return ' &hellip;' . tiga_continue_reading_link();
}

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Tiga 0.0.1
 */
add_filter( 'get_the_excerpt', 'tiga_custom_excerpt_more' );
function tiga_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= tiga_continue_reading_link();
	}
	return $output;
}

/**
 * Remove gallery inline style
 *
 * @since Tiga 0.0.1
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Stop more link from jumping to middle of page
 *
 * @since Tiga 0.0.1
 */

add_filter('the_content_more_link', 'tiga_remove_more_jump_link');
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
 * @since Tiga 0.0.1
 */
add_filter( 'body_class', 'tiga_body_classes' );
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
 * @since Tiga 0.0.1
 */
add_filter( 'attachment_link', 'tiga_enhanced_image_navigation', 10, 2 );
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
 * @since Tiga 0.0.1
 */
add_filter( 'wp_page_menu_args', 'tiga_page_menu_args' );
function tiga_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}

/**
 * Remove div from wp_page_menu() and replace with ul
 *
 * @since Tiga 0.0.1
 */
add_filter('wp_page_menu', 'tiga_wp_page_menu');
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
 * @since Tiga 0.0.1
 */
add_filter( 'widget_tag_cloud_args', 'tiga_new_tag_cloud' );
function tiga_new_tag_cloud( $args ) {
	$args['largest'] 	= 12;
	$args['smallest'] 	= 12;
	$args['unit'] 		= 'px';
	return $args;
}

/**
 * Support shortcode for widget
 *
 * @since Tiga 0.2.1
 */
add_filter('widget_text', 'do_shortcode');

/**
 * HTML5 tag for image and caption
 *
 * @since Tiga 0.2.1
 */
add_filter( 'img_caption_shortcode', 'tiga_html5_caption', 10, 3 );
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
?>