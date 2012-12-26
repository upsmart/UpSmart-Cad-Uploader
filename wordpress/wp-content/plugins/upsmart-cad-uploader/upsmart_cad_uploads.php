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
DEFINE( 'UPSMART_RS_PLUGIN_URL', trailingslashit( WP_PLUGIN_URL ) . basename( dirname( __FILE__ ) ) );


function  upsmart_cad_upload_110n_init(){
  load_plugin_textdomain( 'Upsmart Cad Uploader', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );
  wp_register_style( 'cadPluginStylesheet', plugins_url('css/main.css', __FILE__) );
  wp_register_script( 'cadPluginScript', plugins_url('js/main.js', __FILE__), array('jquery'), '1.0.0', true);
}

add_action( 'init','upsmart_cad_upload_110n_init' );


function cad_upload_media_menu($tabs) {
	$newtab = array('cadfile' => __('CAD Upload', 'cadupload'));
	return array_merge($tabs, $newtab);
}
add_filter('media_upload_tabs', 'cad_upload_media_menu');

function cad_load_styles_and_scripts(){
	wp_enqueue_style('cadPluginStylesheet');
	wp_enqueue_script('cadPluginScript');	
}

add_action( 'admin_enqueue_scripts', 'cad_load_styles_and_scripts' );

/* Note this function must start with media so styling is triggered*/
function media_cad_upload_menu_process() {
	media_upload_header();
	
	error_reporting( -1 );
	ini_set('display_errors', 'on');
	include_once( 'CadUpload.php');
	
    echo '
	<section id="loading-process-area" class="load-off">
		<h1>Embeddable CAD Media 1.0</h1>
		<h2>By Upsmart LLC</h2>
	';
	
	if($_POST){
        
		$uploader = new CadUpload();
		$a = $uploader->saveUpload($field_name='cad_file');
	}
	else{
	echo '
		<p>In less than a minute your innnovative CAD creation will be viewable by the public. Just some notes before you get too excited:
			<ul>
			<li>This plugin is experimental and supports only valid STL files.</li>
			<li>Lighting on the CAD models is limited and will be improved in the near future.</li>
			<li>Resizing and further customization will be intergrated in the future.</li>
			</ul> 
		</p>
		<br/>
		<form id="loadCad" action="" enctype="multipart/form-data" method="post">
			<label class="visuallyhidden" for="realupload">CAD File to Upload: </label>
			<div class="fakeupload">
				<input type="text" name="fakeupload" /> <!-- browse button is here as background -->
			</div>
			<input type="file" name="cad_file" id="realupload" class="realupload" onchange="this.form.fakeupload.value = this.value;" />
			<input class="cad-button-link" type="submit" name="action" value="Upload CAD File"/>
		</form>
	';
	}
	echo '</section>';
	
			/*
		echo '<a href="/wp-admin/media-upload.php?post_id=346&tab=library">To Media Library</a>';
		echo '<a href="/wp-admin/media-upload.php?post_id=346&tab=cadfile">Upload More</a>';
	    */
	
}


function cad_upload_media_menu_handle() {
	return wp_iframe( 'media_cad_upload_menu_process');
}

add_action('media_upload_cadfile', 'cad_upload_media_menu_handle');



function my_attachment_fields_to_edit( $form_fields, $post ) {
    $supported_exts = get_allowed_mime_types(); // array of mime types to show checkbox for

    if ( in_array( $post->post_mime_type, $supported_exts ) && $post->post_mime_type == "text/html") {
        // file is supported, show fields
        $use_sc = true; // check box by default (false not to, or use other criteria)

        $checked = ( $use_sc ) ? 'checked' : '';

        $form_fields['use_sc'] = array(
            'label' =>  'Use Shortcode',
            'input' =>  'html',
            'html'  =>  "<input type='checkbox' {$checked} name='attachments[{$post->ID}][use_sc]' id='attachments[{$post->ID}][use_sc]' /> " . __("Insert shortcode instead of link", "txtdomain"),
            'value' =>  $use_sc
        );
    }

    return $form_fields;
}

function my_media_insert( $html, $id, $attachment ) {
    if ( isset( $attachment['use_sc'] ) && $attachment['use_sc'] == "on" ) {
        $output = '[upsmart_load_cad_file url="'.$attachment['url'].'"]';
        return $output;
    } else {
        return $html;
    }
}

// add checkbox to attachment fields
add_filter( 'attachment_fields_to_edit', 'my_attachment_fields_to_edit', null, 2 );

// insert shortcode if checkbox checked, otherwise default (link to file)
add_filter( 'media_send_to_editor', 'my_media_insert', 20, 3 );



function modify_post_mime_types( $post_mime_types ) {  
	// select the mime type, here: 'application/pdf'  
	// then we define an array with the label values  
	$post_mime_types['text/html'] = array( __( 'CAD' ), __( 'Manage CADs' ), _n_noop( 'CAD <span class="count">(%s)</span>', 'CAD <span class="count">(%s)</span>' ) );  
	// then we return the $post_mime_types variable  
	return $post_mime_types;  
}  
// Add Filter Hook  
add_filter('post_mime_types', 'modify_post_mime_types');


function _upsmart_cad_upload_get_userID()
{
  global $current_user;
  $current_userIP = dechex( sanitize_key( $_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'] ) );
  $current_userID = ( is_user_logged_in() && get_option( 'upSmart_RS_logbyip' ) == '' ) ? $current_user->ID : $current_userIP;
  return $current_userID;
}


function upsmart_cad_upload_createXdom($attr = array()) {
	global $post;
	global $current_user;
	
	if(count($attr) == 0 )
		wp_die( __( 'Shortcode was formulated incorrectly ') );
	
	$fileUrl = $attr['url'];
	

	//$current_userID = _upsmart_cad_upload_get_userID();
	if(!$fileUrl){
		wp_die( __( 'CAD upload file url is invalid: ' . $fileUrl ) );
	}

	
	$xDom = file_get_contents($fileUrl, FILE_USE_INCLUDE_PATH);
	return $xDom;
}
add_shortcode( 'upsmart_load_cad_file', 'upsmart_cad_upload_createXdom' );

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
  /* Need to remove CAD directory */
}
register_uninstall_hook( __FILE__, 'upsmart_cad_upload_uninstaller' );

?>
