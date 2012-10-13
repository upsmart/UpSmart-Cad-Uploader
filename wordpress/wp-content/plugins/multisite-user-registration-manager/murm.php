<?php
/*
Plugin Name: Multisite user registration manager
Plugin URI: http://wordpress.org/extend/plugins/multisite-user-registration-manager
Description: Provides a system for registration requests and their processing in multisite. Two-level moderation.
Version: 2.1.5
Author: Zaantar
Author URI: http://zaantar.eu
License: GPL2
*/

/*
    Copyright 2010-2011 Zaantar (email: zaantar@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// TODO sidebar registration widget
// TODO attach custom admin message to user on request approval/denial

require_once(ABSPATH . WPINC . '/registration.php');

require_once plugin_dir_path( __FILE__ ).'includes/Akismet.class.php';

require_once plugin_dir_path( __FILE__ ).'includes/blog-options.php';
require_once plugin_dir_path( __FILE__ ).'includes/site-options.php';
require_once plugin_dir_path( __FILE__ ).'includes/shortcode.php';
require_once plugin_dir_path( __FILE__ ).'includes/moderation.php';
require_once plugin_dir_path( __FILE__ ).'includes/mailing.php';
require_once plugin_dir_path( __FILE__ ).'includes/database.php';

/* ************************************************************************* *\
	ADMIN MENU
\* ************************************************************************* */


add_action( 'admin_menu','murm_admin_menu' );
add_action( 'network_admin_menu','murm_network_admin_menu' );

function murm_admin_menu() {
	add_submenu_page( 'options-general.php', __( 'Multisite User Registration Manager Settings' , MURM_TEXTDOMAIN  ), __( 'MURM Settings' , MURM_TEXTDOMAIN ), 'manage_options', 
		'murm-options', 'murm_options_page' );
	if( murm_is_active() ) {
		add_submenu_page( 'users.php', __( 'Registration requests on this site' , MURM_TEXTDOMAIN ), __( 'Registration requests' , MURM_TEXTDOMAIN ), 'manage_options', 
			'murm-moderation', 'murm_moderation_page' );
	}
}


function murm_network_admin_menu() {
	add_submenu_page( 'users.php', __( 'MURM registration requests' , MURM_TEXTDOMAIN ),  __( 'MURM registration requests' , MURM_TEXTDOMAIN ), 'manage_network_users', 
		'murm-superadmin', 'murm_superadmin_page' );
}


/* ************************************************************************* *\
	I18N
\* ************************************************************************* */


define( 'MURM_TEXTDOMAIN', 'multisite-user-registration-manager' );
define( 'MURM_TXD', MURM_TEXTDOMAIN );


add_action( 'init', 'murm_load_textdomain' );


function murm_load_textdomain() {
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain( MURM_TEXTDOMAIN, false, $plugin_dir.'/languages' );
}


/* ************************************************************************* *\
	MISCELLANEOUS & DEBUG
\* ************************************************************************* */


define( 'MURM_LOG_NAME', 'murm' );
define( 'MURM_EXTENDED_LOG_NAME', 'murm-extended' );


function murm_nag( $message ) {
	echo( '<div id="message" class="updated"><p>'.$message.'</p></div>' );
}

function murm_nagerr( $message ) {
	echo( '<div id="message" class="error"><p>'.$message.'</p></div>' );
}


function murm_log( $message, $category = 1 ) {
	if( defined( 'WLS' ) && wls_is_registered( 'murm' ) ) {
		wls_simple_log( 'murm', $message, $category );
	} else {
		$options = murm_get_site_settings();
		if( $options["fallback_logging"] ) {
			//fallback
			murm_log_to_file( $message );
		}
	}
}

function murm_log_to_file( $message ) {
	if( is_user_logged_in() ) {
		$user = wp_get_current_user();
		$user = $user->user_login;
	} else {
		$user = '*visitor*';
	}
	$date = date( 'Y-m-d H:i:s' );
	$blogid = get_current_blog_id();
	switch_to_blog( $blogid );
	$blogname = get_bloginfo( 'siteurl' );
	restore_current_blog();
	$fallback_message = "\n***  $date, $user @ $blogid ($blogname):: $message\n";
	$filename = dirname(__FILE__).'/log.txt';
	$file = fopen( $filename, 'a' );
	fwrite( $file, $fallback_message );
	fclose( $file );
}


function murm_extended_log( $message ) {
	if( defined( 'WLS' ) && wls_is_registered( MURM_EXTENDED_LOG_NAME ) ) {
		wls_simple_log( MURM_EXTENDED_LOG_NAME, $message, 1 );
	} else {
		$options = murm_get_site_settings();
		if( $options["fallback_logging"] ) {
			//fallback
			murm_log_to_file( $message );
		}
	}
}


function murm_dberror_description() {
	global $wpdb;
	return "\$wpdb info: query \"{$wpdb->last_query}\", result \"{$wpdb->last_result}\", error \"{$wpdb->last_error}\".";
}



/* ************************************************************************* *\
	NAG
\* ************************************************************************* */

add_action( 'admin_notices','murm_admin_notices' );

function murm_admin_notices() {
	if( !murm_is_active() ) {
		return;
	}
	
	if( current_user_can( 'manage_options') && murm_has_admin_requests() ) {
		murm_nag( sprintf( __( 'You have new registration requests for this blog. Please moderate them %shere%s.' , MURM_TEXTDOMAIN ), '<a href="users.php?page=murm-moderation">', '</a>' ) /*'Máte nevyřízené žádosti o registraci na tomto blogu. Pro jejich vyřízení pokračujte <a href="users.php?page=murm-moderation">zde</a>.'*/ );
	}
}


?>
