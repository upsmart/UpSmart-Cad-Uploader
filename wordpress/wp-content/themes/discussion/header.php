<?php

/* 	DISCUSSION Theme's Header
	Copyright: 2012, D5 Creation, www.d5creation.com
	Based on the Simplest D5 Framework for WordPress
	Since DISCUSSION 1.0
*/

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title() ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php wp_enqueue_style('discussion-style', get_stylesheet_uri(), false, '1.2.01');?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php 

wp_head(); ?>

</head>

<body <?php body_class(); ?> >

  <div id="container">
  	        
	  <div id ="header">
      <h2 class="site-des"><?php bloginfo( 'description' ); ?></h2>
      <div id ="header-content">
      	
		<!-- Site Titele and Description Goes Here -->
        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php header_image(); ?>"/></a></h1>
        
		</div><!-- header-content -->
      
      <!-- Site Main Menu Goes Here -->
        <nav id="discussion-main-menu">
		<?php if ( has_nav_menu( 'main-menu' ) ) : wp_nav_menu( array('menu' => 'Main Menu' )); else : ?>
          <?php wp_page_menu(); ?>
        <?php endif; ?>
        </nav>
        
        <div id="thambslide">
        <ul>
		<?php 
  		$thumbnails = get_posts('numberposts=20');
  		foreach ($thumbnails as $thumbnail) {
    	if ( has_post_thumbnail($thumbnail->ID)) {
      	echo '<a href="' . get_permalink( $thumbnail->ID ) . '" title="' . esc_attr( $thumbnail->post_title ) . '">';
      	echo '<li>' . get_the_post_thumbnail($thumbnail->ID, 'thumbnail'). '</li>';
      	echo '</a>'; 
    	} }
		?>
        </ul>
        </div>
        </div><!-- header -->
              
      
	  
	 
	  