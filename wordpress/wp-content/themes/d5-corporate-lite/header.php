<?php

/* 	D5 CORPORATE Theme's Header
	Copyright: 2012, D5 Creation, www.d5creation.com
	Based on the Simplest D5 Framework for WordPress
	Since D5 CORPORATE 1.0
*/

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title() ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php wp_enqueue_style('d5corporate-style', get_stylesheet_uri(), false, '1.5.02');?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php 

wp_head(); ?>

</head>

<body <?php body_class(); ?> >

  <div id="container">
  	  <div id="top-menu-container">
      
	  <nav id="d5corporate-top-menu"><?php if ( has_nav_menu( 'top-menu' ) ) {  wp_nav_menu( array( 'theme_location' => 'top-menu' )); } ?></nav>
	       
	  <?php get_search_form(); ?>  
      
      </div>
      <div id ="header">
      <div id ="header-content">
      
		<!-- Site Titele and Description Goes Here -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img class="site-logo" src="<?php header_image(); ?>"/></a>
        
		<h2 class="site-title-hidden"><?php bloginfo( 'description' ); ?></h2>
                
        <!-- Site Main Menu Goes Here -->
        <nav id="d5corporate-main-menu">
		<?php if ( has_nav_menu( 'main-menu' ) ) :  wp_nav_menu( array( 'theme_location' => 'main-menu' )); else: wp_page_menu(); endif; ?>
        </nav>
      
      </div><!-- header-content -->
      </div><!-- header -->
      
            
      <?php  if (!is_front_page()) { echo '<div id="headersep"> </div>'; } ?>
	  
	 
	  