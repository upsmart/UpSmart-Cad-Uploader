<?php
/**
* header template used by SpringFestival
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
*/?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title>
<?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( 'Page %s', max( $paged, $page ) );

	?>
</title>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	wp_head();
?>
</head>
<body <?php body_class(); ?>>
<div id="top"></div>
<div id="header">
  <div class="logoblock">
    <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'SpringFestival' ) ); ?>" rel="home" id="logo">
      <?php bloginfo('name'); ?>
      </a></h1>
    <?php bloginfo( 'description' ); ?>
  </div>
  <div class="searchtop">
    <?php get_search_form(); ?>
  </div>
  <div class="cb"></div>
</div>

<div id="nav">
 <div id="nav_bg"></div>
 <?php wp_nav_menu( array( 'container' => '', 'theme_location' => 'primary' ,'show_home'=>'1') ); ?>
 <div class="cb"></div>
</div>
<?php
					// Check if this is a post or page, if it has a thumbnail, and if it's a big one
					if ( is_singular() && current_theme_supports( 'post-thumbnails' ) &&
							has_post_thumbnail( $post->ID ) &&
							( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' ) ) &&
							$image[1] >= HEADER_IMAGE_WIDTH ) :
						// Houston, we have a new header image!
						echo get_the_post_thumbnail( $post->ID );
					elseif ( get_header_image() ) : ?>
<div id="banner">
  <div id="img_bg_bg">
    <div id="img_bg" style="max-width:<?php echo HEADER_IMAGE_WIDTH; ?>px;height:<?php echo HEADER_IMAGE_HEIGHT; ?>px;background-image:url(<?php esc_url ( header_image() ) ?>);"> </div>
  </div>
</div>
<?php else:?>
<?php endif; ?>
<div id="main">
