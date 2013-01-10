<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php wp_title(); ?></title>
    <?php if(of_get_option('gamepress_favicon_radio') == 1) : ?>
	<link rel="shortcut icon" href="<?php echo of_get_option('gamepress_favicon_url'); ?>" type="image/x-icon" />
	<?php endif; ?>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<!-- End Stylesheets -->
	<!--[if lt IE 8]>
	<link rel="stylesheet" href="css/style_ie.css" type="text/css" media="all"  />
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<!-- PAGE -->
<div id="page">
	<!-- HEADER -->
	<header id="header">
        <div id="header-inner">
		<div id="logo">
			<?php if (of_get_option('gamepress_logo_image')) : ?>
				<h1><a href="<?php echo home_url(); ?>"><img src="<?php echo of_get_option('gamepress_logo_image'); ?>" alt="<?php bloginfo( 'name' ); ?>" /></a></h1>
			<?php else : ?>
				<h1 id="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?> - <?php bloginfo('description'); ?>" rel="home"><?php bloginfo('name'); ?></a></h1><p><?php bloginfo('description'); ?></p>
			<?php endif; ?>		
		</div>
		<div class="clear"></div>
		<nav<?php if (!of_get_option('gamepress_search')) : ?> class="nosearch"<?php endif; ?>>
			<?php
			if(has_nav_menu('primary-menu')){
				 wp_nav_menu(array(
					'theme_location' => 'primary-menu',
					'container' => '',
					'menu_id' => 'primary-nav',
					'container_class' => 'main-menu',
					'menu_class' => 'nav'
				 ));
			}else {
			?>
				<ul class="nav" id="primary-nav">
					<?php wp_list_pages('title_li='); ?>
				</ul>
			<?php
			}
			?>
            <?php if (of_get_option('gamepress_search')) : ?>
			<div id="search">
				<?php get_search_form(); ?>
			</div>
            <?php endif; ?>
		</nav>
		</div>
		<!-- END HEADER-INNER -->
	</header>
	<div id="content-wrapper">
	<div id="content-inner">