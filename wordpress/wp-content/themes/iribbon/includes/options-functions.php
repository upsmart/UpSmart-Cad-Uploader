<?php
/**
* Functions related to the Response Theme Options.
*
* Author: Tyler Cunningham
* Copyright: © 2011
* {@link http://cyberchimps.com/ CyberChimps LLC}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package Response
* @since 1.0
*/

/* Standard Web Layout*/

function response_content_layout() {
	global $options, $ir_themeslug, $post;
	
	if (is_single()) {
	$sidebar = $options->get($ir_themeslug.'_single_sidebar');
	}
	elseif (is_archive()) {
	$sidebar = $options->get($ir_themeslug.'_archive_sidebar');
	}
	elseif (is_404()) {
	$sidebar = $options->get($ir_themeslug.'_404_sidebar');
	}
	elseif (is_search()) {
	$sidebar = $options->get($ir_themeslug.'_search_sidebar');
	}
	elseif (is_page()) {
	$sidebar = get_post_meta($post->ID, 'page_sidebar' , true);
	}
	else {
	$sidebar = $options->get($ir_themeslug.'_blog_sidebar');
	}
	
	if ($sidebar == 'two-right' OR $sidebar == '3' ) {
		echo '<style type="text/css">';
		echo "#content.six.columns {width: 52.8%;  margin-right: 2%}";
		echo "#content.six.columns {width: 52.8%;  margin-right: 1.9%\9;}";
		echo "#sidebar-right.three.columns {margin-left: 0%; width: 21.68%;}";
		echo "#sidebar-left.three.columns {margin-left: 0%; width: 21.68%; margin-right:2%}";
		echo "#sidebar-left.three.columns {margin-left: 0%; width: 21.68%; margin-right:1.9%\9;}";
		echo "@-moz-document url-prefix() {#content.six.columns {width: 52.8%;  margin-right: 1.9%} #sidebar-left.three.columns {margin-left: 0%; width: 21.68%; margin-right:1.9%}}";
		echo '</style>';
	}
	if ($sidebar == 'right-left' OR $sidebar == '2' ) {
		echo '<style type="text/css">';
		echo "#content.six.columns {width: 52.8%; margin-left: 2%; margin-right: 2%}";
		echo "#content.six.columns {width: 52.8%; margin-left: 1.9%\9; margin-right: 1.9%\9;}";
		echo "#sidebar-right.three.columns {margin-left: 0%; width: 21.68%;}";
		echo "#sidebar-left.three.columns {margin-left: 0%; width: 21.68%;}";
		echo "@-moz-document url-prefix() {#content.six.columns {width: 52.8%; margin-left: 1.9%; margin-right: 1.9%}}";
		echo '</style>';
	}

}
add_action( 'wp_head', 'response_content_Layout' );

/* Widget Title Background*/

function custom_row_width() {
	global $options, $ir_themeslug;
	$maxwidth = $options->get($ir_themeslug.'_row_max_width');
	
	if ($maxwidth != '0' OR $maxwidth =='1020px' ) {
		echo '<style type="text/css">';
		echo ".row {max-width: $maxwidth;}";
		echo '</style>';
	}	
}
add_action( 'wp_head', 'custom_row_width' );

/* Widget Title Background*/

function custom_text_color() {
	global $options, $ir_themeslug;
	$color = $options->get($ir_themeslug.'_text_color');
	
	if ($options->get($ir_themeslug.'_text_color') != '' ) {
		echo '<style type="text/css">';
		echo "body {color: $color;}";
		echo '</style>';
	}

}
add_action( 'wp_head', 'custom_text_color' );

/* Adjust postbar width for full width and 2 sidebar configs*/

function postbar_option() {
	global $options, $ir_themeslug;
	
	if ($options->get($ir_themeslug.'_blog_sidebar') == 'two-right' OR $options->get($ir_themeslug.'_blog_sidebar') == 'right-left') {
		echo '<style type="text/css">';
		echo ".postbar {width: 95.4%;}";
		echo '</style>';
	}
	
	if ($options->get($ir_themeslug.'_blog_sidebar') == 'none') {
		echo '<style type="text/css">';
		echo ".postbar {width: 97.8%;}";
		echo '</style>';
	}
}
add_action( 'wp_head', 'postbar_option');


/* Featured Image Alignment */

function featured_image_alignment() {

	global $ir_themename, $ir_themeslug, $options;
	
	if ($options->get($ir_themeslug.'_featured_image_align') == "key3" ) {
	
		echo '<style type="text/css">';
		echo ".featured-image {float: right;}";
		echo '</style>';
			
	}
	elseif ($options->get($ir_themeslug.'_featured_image_align') == "key2" ) {

		echo '<style type="text/css">';
		echo ".featured-image {text-align: center;}";
		echo '</style>';
		
	}
	else {
		
		echo '<style type="text/css">';
		echo ".featured-image {float: left;}";
		echo '</style>';
	}
}
add_action( 'wp_head', 'featured_image_alignment');

/* Post Meta Data width */

function post_meta_data_width() {

	global $ir_themename, $ir_themeslug, $options;

	if ($options->get($ir_themeslug.'_blog_sidebar') == "two-right" OR $options->get($ir_themeslug.'_blog_sidebar') == "right-left") {

		echo '<style type="text/css">';
		echo ".postmetadata {width: 480px;}";
		echo '</style>';
		
	}
	
}
add_action( 'wp_head', 'post_meta_data_width');

/* Site Title Color */

function add_sitetitle_color() {

	global $ir_themename, $ir_themeslug, $options;

	if ($options->get($ir_themeslug.'_sitetitle_color') == "") {
		$sitetitle = '#717171';
	}
	
	else {
		$sitetitle = $options->get($ir_themeslug.'_sitetitle_color'); 
	}		
	
		echo '<style type="text/css">';
		echo ".sitename a {color: $sitetitle;}";
		echo '</style>';
		
}
add_action( 'wp_head', 'add_sitetitle_color');

/* Link Color */

function add_link_color() {

	global $ir_themename, $ir_themeslug, $options;

	if ($options->get($ir_themeslug.'_link_color') != '') {
		$link = $options->get($ir_themeslug.'_link_color'); 
	

		echo '<style type="text/css">';
		echo "a {color: $link;}";
		echo ".meta a {color: $link;}";
		echo '</style>';
	}
}
add_action( 'wp_head', 'add_link_color');

/* Link Hover Color */

function add_link_hover_color() {

	global $ir_themename, $ir_themeslug, $options;

	if ($options->get($ir_themeslug.'_link_hover_color') != '') {
		$link = $options->get($ir_themeslug.'_link_hover_color'); 
	

		echo '<style type="text/css">';
		echo "a:hover {color: $link;}";
		echo ".meta a:hover {color: $link;}";
		echo '</style>';
	}
}
add_action( 'wp_head', 'add_link_hover_color');

/* Tagline Color */

function add_tagline_color() {

	global $ir_themename, $ir_themeslug, $options;

	if ($options->get($ir_themeslug.'_tagline_color') != '') {
		$color = $options->get($ir_themeslug.'_tagline_color'); 

		echo '<style type="text/css">';
		echo ".description {color: $color;}";
		echo '</style>';

	}	
}
add_action( 'wp_head', 'add_tagline_color');

/* Post Title Color */

function add_posttitle_color() {

	global $ir_themename, $ir_themeslug, $options;

	if ($options->get($ir_themeslug.'_posttitle_color') != '') {
		$posttitle = $options->get($ir_themeslug.'_posttitle_color'); 
			
		echo '<style type="text/css">';
		echo ".posts_title a {color: $posttitle;}";
		echo '</style>';
	}
}
add_action( 'wp_head', 'add_posttitle_color');

/* Menu Font */
 
function add_menu_font() {
		
	global $ir_themename, $ir_themeslug, $options;	
		
	if ($options->get($ir_themeslug.'_menu_font') == "") {
		$font = 'Georgia';
	}		
		
	elseif ($options->get($ir_themeslug.'_menu_font') == 'custom' && $options->get($ir_themeslug.'_custom_menu_font') != "") {
		$font = $options->get($ir_themeslug.'_custom_menu_font');	
	}
	
	else {
		$font = $options->get($ir_themeslug.'_menu_font'); 
	}
	
		$fontstrip =  str_replace("+", " ", $font );
	
		// register font stylesheet
		if( $font == 'Actor' ||
			$font == 'Coda' ||
			$font == 'Maven Pro' ||
			$font == 'Metrophobic' ||
			$font == 'News Cycle' ||
			$font == 'Nobile' ||
			$font == 'Tenor Sans' ||
			$font == 'Quicksand' ||
			$font == 'Ubuntu') {
			
			// Check if SSL is present, if so then use https othereise use http
			$protocol = is_ssl() ? 'https' : 'http';
			echo "<link href='$protocol://fonts.googleapis.com/css?family=$font' rel='stylesheet' type='text/css' />";
		}
		
		echo '<style type="text/css">';
		echo "#nav ul li a {font-family: $fontstrip;}";
		echo '</style>';
}
add_action( 'wp_head', 'add_menu_font'); 

/* Secondary Font */
 
function add_secondary_font() {
		
	global $ir_themename, $ir_themeslug, $options;	
		
	if ($options->get($ir_themeslug.'_secondary_font') == "") {
		$font = 'Lobster';
	}		
		
	elseif ($options->get($ir_themeslug.'_secondary_font') == 'custom' && $options->get($ir_themeslug.'_custom_secondary_font') != "") {
		$font = $options->get($ir_themeslug.'_custom_secondary_font');	
	}
	
	else {
		$font = $options->get($ir_themeslug.'_secondary_font'); 
	}
	
		$fontstrip =  ereg_replace("[^A-Za-z0-9]", " ", $font );
	
		// Check if SSL is present, if so then use https othereise use http
		$protocol = is_ssl() ? 'https' : 'http';
		
		echo "<link href='$protocol://fonts.googleapis.com/css?family=$font' rel='stylesheet' type='text/css' />";
		echo '<style type="text/css">';
		echo "#callout_text, .posts_title a, .posts_title, .sitename, .widget-title, .box-widget-title, .carousel_caption, .footer-widget-title, .commentsh2{font-family: '$fontstrip', cursive;}";
		echo '</style>';
}
add_action( 'wp_head', 'add_secondary_font'); 

/* Custom CSS */

function custom_css() {

	global $ir_themename, $ir_themeslug, $options;
	
	$custom =$options->get($ir_themeslug.'_css_options');
	echo '<style type="text/css">' . "\n";
	echo  $custom  . "\n";
	echo '</style>' . "\n";
}

function custom_css_filter($_content) {
	$_return = preg_replace ( '/@import.+;( |)|((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/))/i', '', $_content );
	$_return = htmlspecialchars ( strip_tags($_return), ENT_NOQUOTES, 'UTF-8' );
	return $_return;
}
		
add_action ( 'wp_head', 'custom_css' );

?>