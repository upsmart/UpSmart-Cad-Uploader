<?php
/**
* function template used by SpringFestival
*
* Authors: wpart
* Copyright: 2012
* {@link http://www.wpart.org/}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package SpringFestival
* @since 1.4
*/
function SpringFestival_widgets_init() {    
	register_sidebar(array(
	    'id' => 'sidebar',
		'name' =>'sidebar' ,
		'before_widget' => '<li id="%1$s" class="side widget %2$s">', 
		'after_widget' => '</li><div class="side_bottom"></div>',
		'before_title' => '<h3 class="title3">', 
		'after_title' => '</h3>' 
	));
}
add_action( 'widgets_init', 'SpringFestival_widgets_init');

if ( ! isset( $content_width ) )
	$content_width = 570;
	
function SpringFestival_setup() {
	require( get_template_directory() . '/inc/theme-options.php' );
	load_theme_textdomain( 'SpringFestival', get_template_directory() . '/languages' );
	global $wp_version;
if ( version_compare( $wp_version, '3.4', '>=' ) ) 
	add_theme_support( 'custom-background' ); 
else
	add_custom_background();
	
	add_editor_style();
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	register_nav_menus( array(
		'primary' => 'Primary Menu',
	) );		
	add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );
	defined( 'HEADER_IMAGE' );
	define( 'HEADER_IMAGE', '%s/images/banner.jpg' );
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'SpringFestival_header_image_width', 940 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'SpringFestival_header_image_height', 200 ) );
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );
	define( 'NO_HEADER_TEXT', true );
add_theme_support( 'custom-header' );
}
add_action( 'after_setup_theme', 'SpringFestival_setup' );

?>