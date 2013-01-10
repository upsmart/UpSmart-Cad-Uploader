<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
        <title>
            <?php wp_title('&#124;', true, 'right'); ?><?php bloginfo('name'); ?>
        </title>       	
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" /> 
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
<div class="wrapper">
  <div class="container_24">
    <div class="grid_24">
      <div class="header">
        <div class="grid_8 alpha">
          <div class="logo">
		  <a href="<?php echo home_url(); ?>"><img src="<?php if (poloray_get_option('poloray_logo') != '') { ?><?php echo poloray_get_option('poloray_logo'); ?><?php } else { ?><?php echo get_template_directory_uri(); ?>/images/logo.png<?php } ?>" alt="<?php bloginfo('name'); ?>" alt="logo"/></a>
		  </div>
        </div>
        <div class="grid_16 omega">
          <div class="menu_container">
                            <div class="menu_bar">
                                <div id="MainNav">
                                    <a href="#" class="mobile_nav closed"><?php _e( 'Pages Navigation Menu', 'poloray' ); ?><span></span></a>
<?php poloray_nav(); ?> 
                                </div>
                            </div>
                        </div>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>