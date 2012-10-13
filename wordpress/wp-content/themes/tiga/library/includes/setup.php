<?php
/**
 * Define Theme setup
 * 
 * This file defines the setup functions for the Tiga Theme.
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		Tiga 0.0.1
 *
 */
 
add_action( 'after_setup_theme', 'tiga_setup' );
if ( ! function_exists( 'tiga_setup' ) ):

	function tiga_setup() {
		
		/**
		 * Set the content width based on the theme's design and stylesheet.
		 */
		global $content_width;
		if ( ! isset( $content_width ) ) $content_width = 620;
				
		// Theme styles for the visual editor.
		add_editor_style();
		
		// Add default posts and comments RSS feed links to <head>.
		add_theme_support( 'automatic-feed-links' );
		
		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus( 
			array(
				'primary' => __( 'Primary Navigation', 'tiga' ),
				'secondary' => __( 'Secondary Navigation', 'tiga' )
			) 
		);
		
		// This theme uses Featured Images (also known as post thumbnails)
		add_theme_support( 'post-thumbnails' );
		// Add custom image sizes
		add_image_size( 'tiga-140px' , 140, 140, true ); // 140px thumbnail
		add_image_size( 'tiga-300px' , 300, 130, true ); // 300px thumbnail
		add_image_size( 'tiga-700px' , 700, 300, true ); // 700px thumbnail
		add_image_size( 'tiga-620px' , 620, 350, true ); // 620px thumbnail
		
	}

endif; // end tiga_setup
?>