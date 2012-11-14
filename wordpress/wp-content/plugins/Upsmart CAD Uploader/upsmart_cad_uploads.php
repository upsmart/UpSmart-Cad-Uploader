<?php
/*
Plugin Name: Upsmart CAD Uploader
Plugin URI: http://aarontobias.com
Description: This plugin intercepts cad files and converts them into XDom format
             to be used as emebddable media at upSmart LLC website via go-upsmart.com
             in addition to a companies site through wordpress multisite
Version: 1.0
Last Modified: November 7th 2012
Author: Aaron Tobias
Author URI: aarontobias.com
License: GNU GENERAL PUBLIC LICENSE
*/

//include("~/wp-admin/includes/file.php");
 
DEFINE( 'UPSMART_RS_PLUGIN_URL', trailingslashit( WP_PLUGIN_URL ) . basename( dirname( __FILE__ ) ) );

function  upsmart_cad_upload_110n_init(){
  load_plugin_textdomain( 'Upsmart Cad Uploader', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}

add_action( 'init','upsmart_cad_upload_110n_init' );


function cad_upload_media_menu($tabs) {
	$newtab = array('cadfile' => __('CAD Upload', 'cadupload'));
	return array_merge($tabs, $newtab);
}
add_filter('media_upload_tabs', 'cad_upload_media_menu');

/* Note this function must start with media so styling is triggered*/
function media_cad_upload_menu_process() {
	media_upload_header();
	
	error_reporting( -1 );
	ini_set('display_errors', 'on');
	include_once( 'CadUpload.php');
	
	if($_POST ){
		
		$uploader = new CadUpload();
		$a = $uploader->saveUpload($field_name='cad_file' );
		var_dump($a);
		die();
	}
	
	echo '
	<form action="" enctype="multipart/form-data" method="post">
		<input type="file" name="cad_file" />
		<p><input type="submit" name="action" value="Upload CAD File"/></p>
	</form>
	';
    
}

function cad_upload_media_menu_handle() {
	return wp_iframe( 'media_cad_upload_menu_process');
}

add_action('media_upload_cadfile', 'cad_upload_media_menu_handle');


function modify_post_mime_types($post_mime_types) {
    $post_mime_types['text/html'] = array('HTML', 'Manage HTML', 'HTML (%s)');
    return $post_mime_types;
}

add_filter('post_mime_types', 'modify_post_mime_types');

/*
function edit_cad_attachment($post, $attachment) {
	var_dump($post);
	
	if ( substr($post['post_mime_type'], 0, 5) == 'image' ) {
		if ( strlen(trim($post['post_title'])) == 0 ) {
			$post['post_title'] = preg_replace('/\.\w+$/', '', basename($post['guid']));
			$post['errors']['post_title']['errors'][] = __('Empty Title filled from filename.');
		}
		// captions are saved as the post_excerpt, so we check for it before overwriting
		// if no captions were provided by the user, we fill it with our default
		if ( strlen(trim($post['post_excerpt'])) == 0 ) {
			$post['post_excerpt'] = 'default caption';
		}
		
	}
	
return $post;
}

add_filter('attachment_fields_to_save', 'edit_cad_attachment', 1, 2);


function cad_upload_handler( $post, $url, $type){
	
	var_dump($post);
}

add_filter('wp_handle_upload', 'cad_upload_handler', 1 , 3);
*/

//add_filter( 'media_upload_tabs', 'media_upload_tabs'); //hide media tabs
//add_filter( 'media_send_to_editor', 'media_send_to_editor' ); //to modified the string send by javascript
//add_filter( 'media_upload_form_url', 'media_upload_form_url' ); //used to send new parameter


/*media_handle_upload( $file_id, $post_id, $post_data, $overrides );*/





function _upsmart_cad_upload_get_userID()
{
  global $current_user;
  $current_userIP = dechex( sanitize_key( $_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'] ) );
  $current_userID = ( is_user_logged_in() && get_option( 'upSmart_RS_logbyip' ) == '' ) ? $current_user->ID : $current_userIP;
  return $current_userID;
}

function upsmart_cad_upload_createXdom() {
global $post;
global $current_user;

$current_userID = _upsmart_cad_upload_get_userID();
return $xDom;
}
add_shortcode( 'upsmart_load_cad_file', 'upsmart_cad_upload_createXdom' );


function upsmart_cad_upload_default_values()
{
  load_plugin_textdomain( 'Upsmart CAD Uploader', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}
register_activation_hook( __FILE__, 'upsmart_cad_upload_default_values' );


function upsmart_check_versions() {
  global $wp_version;
	if ( version_compare( PHP_VERSION, '5.0.0', '<' ) ) {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die( __( 'This plugin requires PHP 5.0 or more', 'upsmart_cad_upload' ) );
	}
	if ( version_compare( $wp_version, '3.1', '<' ) ) {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die( __( 'This plugin requires WordPress 3.1 or more. Your version : ' . $wp_version, 'upsmart_cad_upload' ) );
	}
}
register_activation_hook( __FILE__, 'upsmart_check_versions' );


function upsmart_cad_upload_uninstaller(){
  global $wpdb;
}
register_uninstall_hook( __FILE__, 'upsmart_cad_upload_uninstaller' );

?>
