<?php
/**
 * Header Template Part
 * 
 * Template part file that contains the HTML document head and 
 * opening HTML body elements, as well as the site header
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		Tiga 0.0.1
 *
 */
?>
<!DOCTYPE html>
<!--[if IE 7]>    <html class="no-js ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>

<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title><?php wp_title(); ?></title>

<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<div id="page" class="hfeed site">
	<?php tiga_before(); ?>

	<header id="masthead" class="site-header" role="banner">
		<div id="main-header" class="clearfix">
		
			<div class="site-branding">
				<?php if(of_get_option('tiga_custom_logo')) :
					
					$logotag  = (is_home() || is_front_page())? 'h1':'div'; // only display h1 tag in home page, SEO reason ?>
						<<?php echo $logotag; ?> class="site-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" src="<?php echo esc_url( of_get_option('tiga_custom_logo') ); ?>"><span><?php bloginfo('name'); ?></span></a></<?php echo $logotag; ?>>
					<?php
				else :
					$titletag  = (is_home() || is_front_page())? 'h1':'div'; // only display h1 tag in home page, SEO reason ?>
						<<?php echo $titletag; ?> class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></<?php echo $titletag; ?>>
						<div class="site-description"><?php bloginfo( 'description' ); ?></div>
				<?php endif; ?>
			</div> <!-- end .site-branding -->

			<nav class="site-navigation main-navigation" role="navigation">
				<h5 class="assistive-text"><?php _e( 'Menu', 'tiga' ); ?></h5>
				<div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'tiga' ); ?>"><?php _e( 'Skip to content', 'tiga' ); ?></a></div>

				<?php 
				if (has_nav_menu('primary'))
				wp_nav_menu( array(  
						'container' => '',
						'menu_class' => 'main-nav',
						'theme_location' => 'primary') 
					); ?>
			</nav> <!-- end .site-navigation -->
			
		</div> <!-- end #main-header -->
		
		<nav class="site-navigation secondary-navigation clearfix" role="navigation">
			<?php 
			if (has_nav_menu('secondary'))
			wp_nav_menu( array(  
					'container' => '',
					'menu_class' => 'secondary-nav',
					'theme_location' => 'secondary' ) 
				); ?>
		</nav> <!-- end .site-navigation -->

		<?php tiga_header(); ?>
	</header><!-- #masthead .site-header -->
	
	<?php tiga_main_before(); ?>

	<div id="main">
		<?php tiga_main(); ?>