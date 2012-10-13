<?PHP
/*  
	Plugin Name: Company Profiles Admin
  Plugin URI: 
  Description: This plugin helps registered companies edit their profiles on UpSmart LLC
  Version: 1.0
	Author: Aaron Tobias
	Author URI: http://aarontobias.com
	License: GPL2  - most WordPress plugins are released under GPL2 license terms
*/

define( "CP_PROFILES_ADMIN_URL", WP_PLUGIN_URL . "/company-profiles-admin" );
define('PLUGIN_VERSION', 1);
define('PLUGIN_URL', rtrim(plugin_dir_url(__FILE__)));
define('PLUGIN_DIR', rtrim(plugin_dir_path(__FILE__)));
define("CP_PROFILES_ADMIN_CSS", PLUGIN_URL . "css/cp-profiles-admin.css");

function log_me($message) {
    if (WP_DEBUG === true) {
        if (is_array($message) || is_object($message)) {
            error_log(print_r($message, true));
        } else {
            error_log($message);
        }
    }
}


function cp_profiles_admin_head() { 
require ( PLUGIN_DIR . '/framework/company_profiles_admin.class.php');
$GLOBALS['databaseInterface'] = new Company_Profiles_Admin();
/*$cpProfileDatabaseInterface->get("Select * from game_types");
	if($result = $cpProfileDatabaseInterface->execute_stored($this->get_stored_query(""), ...variables...) ){
	}
*/
?>
<link rel="stylesheet" type="text/css" href="<?PHP echo CP_PROFILES_ADMIN_CSS ?>">
 
<?php 
}

add_action('admin_head', 'cp_profiles_admin_head');
	 
function company_profiles_menu() {
	add_menu_page( __('Company Profile','cp-menu'), __('Company Profile','cp-menu'), 'manage_options', 'cp-profile-admin', 'cp_companies_options', CP_PROFILES_ADMIN_URL .'/images/upsmart.png', 26);
	//remove top level menu from sub menu default-------------------------------------------------------------
	add_submenu_page('cp-profile-admin','','','manage_options','cp-profile-admin','cp_companies_options');
	//--------------------------------------------------------------------------------------------------------
  add_submenu_page( 'cp-profile-admin', __('Contact Info','cp-menu'), __('Contact Info','cp-menu'), 'manage_options', 'cp-contact-info', 'cp_contact_admin');
  $scriptPage = add_submenu_page( 'cp-profile-admin', __('The Team','cp-menu'), __('The Team','cp-menu'), 'manage_options', 'cp-the-team', 'cp_team_admin');
  add_submenu_page( 'cp-profile-admin', __('About Us','cp-menu'), __('About Us','cp-menu'), 'manage_options', 'cp-about-us', 'cp_about_admin');
  add_submenu_page( 'cp-profile-admin', __('Media','cp-menu'), __('Media','cp-menu'), 'manage_options', 'cp-media-center', 'cp_media_center_admin');
  add_action("admin_print_scripts-".$scriptPage, 'add_my_scripts');
}

add_action( 'admin_menu', 'company_profiles_menu' );

function add_my_scripts()
{
	 //We can include as many Javascript files as we want here.
	 wp_enqueue_script('pluginscript', plugins_url('/js/cp-profiles-admin.js', __FILE__), array('jquery'));
}
	 

function cp_companies_options() {
  global $current_user;
  get_currentuserinfo();
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	?>
	<?  
	$databaseInterface = new Company_Profiles_Admin();
	$result = $databaseInterface->get("
		SELECT *
		FROM cp_admins as ca, cp_employees as em, cp_companies as comp 
		WHERE ca.employeeId = em.id 
		AND em.companyId = comp.id 
		AND ca.admin_id=373;
		"); 
	 echo '<br/> Current User ID: ' . $current_user->ID . "\n";
	?>
	
<?PHP } 

function cp_contact_admin(){
  global $current_user;
  get_currentuserinfo();
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	$databaseInterface = new Company_Profiles_Admin();
	$userId = $current_user->ID;
	$userId=373;
	$result = $databaseInterface->get("
		SELECT *
		FROM cp_admins as ca, cp_employees as em, cp_companies as comp 
		WHERE ca.employeeId = em.id 
		AND em.companyId = comp.id 
		AND ca.admin_id=" . $userId
		); 
	$compId = $result[0]['companyId'];
	$emplId = $result[0]['employeeId'];
	?>
	<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2> <?PHP echo __('Contact Info','cp-menu') ?> </h2>
	
	<?PHP require_once(dirname(__FILE__) . "/process/process-contact.php"); ?>
	
	<form action=<?php echo '"'. $_SERVER['REQUEST_URI'] .'"'; ?> method="post" id="cp_contact_admin">	
		
		<input type="hidden" name="company_id" value= <?php echo '"'. $compId .'"';?> />
		<input type="hidden" name="employee_id" value= <?php echo '"'. $emplId .'"';?> />
		<input type="hidden" name="process-form" value="1"/>
		
		<label>Contact Last Name</label><input type="text" name="last" value=<?PHP echo '"'. $result[0]['lastname'] . '"' ?> autofocus="autofocus"/>
		<label>Contact First Name</label><input type="text" name="first" value=<?PHP echo '"'. $result[0]['firstname'] . '"' ?>/>
		<label>Contact Email</label><input type="text" name="email" value=<?PHP echo '"'. $result[0]['email'] . '"' ?>/>
		
		<h3>keep email address Confidential?</h3>
		<input type="radio" name="addConfidential" value="1"/>Yes
		<input type="radio" name="addConfidential" value="0"/>No
		
		<label>Phone Number</label><input type="text" name="phone" value=<?PHP echo '"'. $result[0]['phone'] . '"' ?>/>
		
		<h3>Has your company been incorporated yet?</h3>
		<?PHP if($result[0]['incorporated']){
		echo'
		<input type="radio" name="incorporated" value="1" checked/>Yes
		<input type="radio" name="incorporated" value="0"/>No
		';
		}
		else{
		echo'
		<input type="radio" name="incorporated" value="1"/>Yes
		<input type="radio" name="incorporated" value="0" checked/>No
		';
		}
		?>
		<label>Company Name</label><input type="text" name="companyName" value=<?PHP echo '"'. $result[0]['name'] . '"' ?>/>
		
		<h3>Does your company have a website?</h3>
		<?PHP if($result[0]['website']){
		echo'
		<input type="radio" name="hasWebsite" value="1" checked/>Yes
		<input type="radio" name="hasWebsite" value="0" />No
		<label>Website URL</label><input type="text" name="website" value= "' .$result[0]["website"] .'"/>
		';
		}
		else{
		echo'
		<input type="radio" name="hasWebsite" value="1"/>Yes
		<input type="radio" name="hasWebsite" value="0" checked/>No
		';
		}
		?>
		
		<label>Street Address</label><input type="text" name="address" value=<?PHP echo '"'. $result[0]['address'] . '"' ?>/>
		<label>City</label><input type="text" name="city" value=<?PHP echo '"'. $result[0]['city'] . '"' ?>/>
		<label>State</label><input type="text" name="state" value=<?PHP echo '"'. $result[0]['state'] . '"' ?>/>
		<label>Zip Code</label><input type="text" name="zip" value=<?PHP echo '"'. $result[0]['zip'] . '"' ?>/>
		<input type="submit" name="submit" value="Update Profile"/>
	</form>
	</div>
<?PHP }

function cp_team_admin(){
  global $current_user;
  get_currentuserinfo();
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	$databaseInterface = new Company_Profiles_Admin();
	$userId = $current_user->ID;
	$userId=373;
	$result = $databaseInterface->get("
		SELECT *
		FROM cp_admins as ca, cp_employees as em, cp_positions as pos, cp_personbio as pbio
		WHERE ca.employeeId = em.id 
		AND ca.admin_id=" .$userId. ";
		"); 
		$compId = $result[0]['companyId'];
		

		$result = $databaseInterface->get("
		SELECT *
		FROM cp_employees as em, cp_positions as pos, cp_personbio as pbio 
		WHERE companyId = " . $compId . "
		AND pos.id = em.positionId
		AND pbio.employeeId = em.id;
		"); 

	?>
	<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2> <?PHP echo __('The Team','cp-menu') ?> </h2>
	
	<?PHP require_once(dirname(__FILE__) . "/process/process-team.php"); ?>
		
	<form action=<?php echo '"'. $_SERVER['REQUEST_URI'] .'"'; ?> method="post" id="cp_the_team_admin">	
	  <input type="hidden" name="company_id" value=<?php echo '"'. $compId .'"';?> />
		<input type="hidden" name="process-form" value="1"/>
		
	  <?PHP 
		$length = count($result);
		for($ct = 1; $ct <= $length; $ct++){
		
	  echo '<fieldset id="' . $ct . '">';
		  echo '<input type="hidden" name="entry' . $ct .'" value="' . $result[$ct-1]['employeeId'] . '"/>';
			echo '<label>Position ' . $ct .' Title</label><input type="text" name="title' . $ct. '" autofocus="autofocus" value="' . $result[$ct-1]['title'] .'"/>';
			echo '<label>Position ' . $ct .' First Name</label><input type="text" name="first' . $ct. '"value="' . $result[$ct-1]['firstname'] .'"/>';
			echo '<label>Position ' . $ct .' Last Name</label><input type="text" name="last' . $ct. '" value="' . $result[$ct-1]['lastname'] .'"/>';
			echo '<label>Position ' . $ct .' Picture</label><input type="text" name="pic' . $ct. '"/>';
			echo '<label for="file"><input type="file" name="file' . $ct. '" value="Upload Picture"/> </label> ';
			echo '<label>Position ' . $ct .' Bio</label>';
			echo '<textarea name="bio' . $ct .'" rows=20 cols=100>' . $result[$ct-1]['bio'] . '</textarea>';
	  echo '</fieldset>';
		}
		?>
		<button type = "button" id="addMember" name="addTeamMember"><span>+</span> Add Team Member</button>
		<input type="submit" name="submit" value="Update Profile"/>
	</form>
	</div>
<?PHP }

function cp_about_admin(){
  global $current_user;
  get_currentuserinfo();
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	$databaseInterface = new Company_Profiles_Admin();
	$userId = $current_user->ID;
	$userId=373; 
	$result = $databaseInterface->get(
	 "SELECT *
		FROM cp_admins as ca, cp_employees as em, cp_companies as comp, cp_siteinfo_what as w, cp_siteinfo_about as a
		WHERE ca.employeeId = em.id 
		AND em.companyId = comp.id 
		AND ca.admin_id=" .$userId. "
		AND w.companyId = comp.id
		AND a.companyId = comp.id
		;
		"); 
		$compId = $result[0]['companyId'];
	?>  
	<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2> <?PHP echo __('About Us','cp-menu') ?> </h2>
	
	<?PHP require_once(dirname(__FILE__) . "/process/process-company.php"); ?>
	
	<form action=<?php echo '"'. $_SERVER['REQUEST_URI'] .'"'; ?> method="post" id="cp_about_us_admin">	
		<input type="hidden" name="company_id" value=<?php echo '"'. $compId .'"';?> />
		<input type="hidden" name="process-form" value="1"/>
		
		<h3>Mission Statement</h3> 
		<textarea name="mission" rows=20 cols=100 autofocus="autofocus"><?PHP echo $result[0]['missionStatement'];?></textarea>
		<label for="file">Logo</label>
		<input type="file" name="logo" id="file" />	
		
		<h3>About Us</h3>
		<textarea name="about" rows=20 cols=100><?PHP echo  trim($result[0]['about']); ?></textarea>
		
		<label>Name of Main Product</label><input type="text" name="mainProduct"value=""/>
		<label>Name of Secondary Product </label><input type="text" name="secProduct" value=""/>
		
		<h3>What are you doing?</h3>
		<textarea name="what" rows=20 cols=100><?PHP echo $result[0]['what'];?></textarea>
		
		<h3>How are you doing it?</h3>
		<textarea name="how" rows=20 cols=100><?PHP echo $result[0]['how']; ?></textarea>
		
		<h3>Why are you doing it?</h3>
		<textarea name="why" rows=20 cols=100><?PHP echo $result[0]['why']; ?></textarea>
		
		<input type="submit" name="submit" value="Update Profile"/>
	</form>
	</div>
<?PHP }

function cp_media_center_admin(){
	?>
<?php
/**
 * Media settings administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */

if ( ! current_user_can( 'manage_options' ) )
	wp_die( __( 'You do not have sufficient permissions to manage options for this site.' ) );

$title = __('Media Settings');
$parent_file = 'options-general.php';

$media_options_help = '<p>' . __('You can set maximum sizes for images inserted into your written content; you can also insert an image as Full Size.') . '</p>' .
	'<p>' . __('The Embed option allows you embed a video, image, or other media content into your content automatically by typing the URL (of the web page where the file lives) on its own line when you create your content.');

if ( ! empty( $content_width ) )
	$media_options_help .= ' ' . __( 'If you do not set the maximum embed size, it will be automatically sized to fit into your content area.' );

$media_options_help .= '</p>'; 

if ( ! is_multisite() ) {
	$media_options_help .= '<p>' . __('Uploading Files allows you to choose the folder and path for storing your uploaded files.') . '</p>';
}

$media_options_help .= '<p>' . __('You must click the Save Changes button at the bottom of the screen for new settings to take effect.') . '</p>';

get_current_screen()->add_help_tab( array(
	'id'      => 'overview',
	'title'   => __('Overview'),
	'content' => $media_options_help,
) );

get_current_screen()->set_help_sidebar(
	'<p><strong>' . __('For more information:') . '</strong></p>' .
	'<p>' . __('<a href="http://codex.wordpress.org/Settings_Media_Screen" target="_blank">Documentation on Media Settings</a>') . '</p>' .
	'<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>') . '</p>'
);

?>

<div class="wrap">
<?php screen_icon(); ?>
<h2><?php echo esc_html( $title ); ?></h2>

<form action="options.php" method="post">
<?php settings_fields('media'); ?>

<h3><?php _e('Image sizes') ?></h3>
<p><?php _e('The sizes listed below determine the maximum dimensions in pixels to use when inserting an image into the body of a post.'); ?></p>

<table class="form-table">
<tr valign="top">
<th scope="row"><?php _e('Thumbnail size') ?></th>
<td>
<label for="thumbnail_size_w"><?php _e('Width'); ?></label>
<input name="thumbnail_size_w" type="number" step="1" min="0" id="thumbnail_size_w" value="<?php form_option('thumbnail_size_w'); ?>" class="small-text" />
<label for="thumbnail_size_h"><?php _e('Height'); ?></label>
<input name="thumbnail_size_h" type="number" step="1" min="0" id="thumbnail_size_h" value="<?php form_option('thumbnail_size_h'); ?>" class="small-text" /><br />
<input name="thumbnail_crop" type="checkbox" id="thumbnail_crop" value="1" <?php checked('1', get_option('thumbnail_crop')); ?>/>
<label for="thumbnail_crop"><?php _e('Crop thumbnail to exact dimensions (normally thumbnails are proportional)'); ?></label>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Medium size') ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Medium size'); ?></span></legend>
<label for="medium_size_w"><?php _e('Max Width'); ?></label>
<input name="medium_size_w" type="number" step="1" min="0" id="medium_size_w" value="<?php form_option('medium_size_w'); ?>" class="small-text" />
<label for="medium_size_h"><?php _e('Max Height'); ?></label>
<input name="medium_size_h" type="number" step="1" min="0" id="medium_size_h" value="<?php form_option('medium_size_h'); ?>" class="small-text" />
</fieldset></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Large size') ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Large size'); ?></span></legend>
<label for="large_size_w"><?php _e('Max Width'); ?></label>
<input name="large_size_w" type="number" step="1" min="0" id="large_size_w" value="<?php form_option('large_size_w'); ?>" class="small-text" />
<label for="large_size_h"><?php _e('Max Height'); ?></label>
<input name="large_size_h" type="number" step="1" min="0" id="large_size_h" value="<?php form_option('large_size_h'); ?>" class="small-text" />
</fieldset></td>
</tr>

<?php do_settings_fields('media', 'default'); ?>
</table>

<h3><?php _e('Embeds') ?></h3>

<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Auto-embeds'); ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('When possible, embed the media content from a URL directly onto the page. For example: links to Flickr and YouTube.'); ?></span></legend>
<label for="embed_autourls"><input name="embed_autourls" type="checkbox" id="embed_autourls" value="1" <?php checked( '1', get_option('embed_autourls') ); ?>/> <?php _e('When possible, embed the media content from a URL directly onto the page. For example: links to Flickr and YouTube.'); ?></label>
</fieldset></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Maximum embed size') ?></th>
<td>
<label for="embed_size_w"><?php _e('Width'); ?></label>
<input name="embed_size_w" type="number" step="1" min="0" id="embed_size_w" value="<?php form_option('embed_size_w'); ?>" class="small-text" />
<label for="embed_size_h"><?php _e('Height'); ?></label>
<input name="embed_size_h" type="number" step="1" min="0" id="embed_size_h" value="<?php form_option('embed_size_h'); ?>" class="small-text" />
<?php if ( ! empty( $content_width ) )
	echo '<p class="description">' . __( 'If the width value is left blank, embeds will default to the max width of your theme.' ) . '</p>';
?>
</td>
</tr>

<?php do_settings_fields('media', 'embeds'); ?>
</table>

<?php if ( !is_multisite() ) : ?>
<h3><?php _e('Uploading Files'); ?></h3>
<table class="form-table">
<tr valign="top">
<th scope="row"><label for="upload_path"><?php _e('Store uploads in this folder'); ?></label></th>
<td><input name="upload_path" type="text" id="upload_path" value="<?php echo esc_attr(get_option('upload_path')); ?>" class="regular-text code" />
<p class="description"><?php _e('Default is <code>wp-content/uploads</code>'); ?></p>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="upload_url_path"><?php _e('Full URL path to files'); ?></label></th>
<td><input name="upload_url_path" type="text" id="upload_url_path" value="<?php echo esc_attr( get_option('upload_url_path')); ?>" class="regular-text code" />
<p class="description"><?php _e('Configuring this is optional. By default, it should be blank.'); ?></p>
</td>
</tr>

<tr>
<th scope="row" colspan="2" class="th-full">
<label for="uploads_use_yearmonth_folders">
<input name="uploads_use_yearmonth_folders" type="checkbox" id="uploads_use_yearmonth_folders" value="1"<?php checked('1', get_option('uploads_use_yearmonth_folders')); ?> />
<?php _e('Organize my uploads into month- and year-based folders'); ?>
</label>
</th>
</tr>

<?php do_settings_fields('media', 'uploads'); ?>
</table>
<?php endif; ?>

<?php do_settings_sections('media'); ?>

<?php submit_button(); ?>

</form>

</div>

<?PHP } ?>