<?php
/**
 * Theme functions file
 *
 * Contains all of the Theme's setup functions, custom functions,
 * custom Widgets, custom hooks, and Theme settings.
 * 
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		Tiga 0.0.1
 *
 */

/**
 * Loads the Options Panel
 *
 * @since Tiga 0.0.1
 */
 
if ( !function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/library/admin/' );
	require_once dirname( __FILE__ ) . '/library/admin/options-framework.php';
}

/** 
 * Make tiga available for translation.
 * Translations can be added to the library/languages/ directory.
 * If you're building a theme based on tiga, use a find and replace
 * to change 'tiga' to the name of your theme in all the template files.
 *
 * @since Tiga 0.0.1
 */
load_theme_textdomain( 'tiga', get_template_directory() . '/library/languages' );

/**
 * Load all library files
 *
 * @since Tiga 0.0.1
 */
require( get_template_directory() . '/library/includes/setup.php' );
require( get_template_directory() . '/library/includes/enqueue.php' );
require( get_template_directory() . '/library/includes/extensions.php' );
require( get_template_directory() . '/library/includes/filters.php' );
require( get_template_directory() . '/library/includes/options-functions.php' );
require( get_template_directory() . '/library/includes/options-sidebar.php' ); // load sidebar for theme options
require( get_template_directory() . '/library/includes/templates.php' );
require( get_template_directory() . '/library/includes/widgets.php' );
require( get_template_directory() . '/library/includes/hooks.php' );
require( get_template_directory() . '/library/includes/metabox.php' );

?>