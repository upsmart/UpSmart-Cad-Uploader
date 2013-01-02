<?php
/*
Plugin Name: Multisite user registration manager
Plugin URI: http://wordpress.org/extend/plugins/multisite-user-registration-manager
Description: Provides a system for registration requests and their processing in multisite. Two-level moderation.
Version: 3.1
Network: true
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
// TODO correct i18n, custom blog admin messages
// TODO custom shortcode css (error/ok) + information
// TODO save all settings as a single wp option

require_once(ABSPATH . WPINC . '/registration.php');

require_once plugin_dir_path( __FILE__ ).'includes/Akismet.class.php';
require_once plugin_dir_path( __FILE__ ).'includes/options.php';
require_once plugin_dir_path( __FILE__ ).'includes/database.php';



// legacy definitions
define( 'MURM_TEXTDOMAIN', 'multisite-user-registration-manager' );
define( 'MURM_TXD', 'multisite-user-registration-manager');

define( 'MURM_LOG_NAME', 'murm' );
define( 'MURM_EXTENDED_LOG_NAME', 'murm-extended' );

define( 'MURM_DEBUG', true );


class Murm {

	var $o;
	var $d;

	function __construct() {
		$this->o = new Murm_Options( $this );
		$this->d = new Murm_Database( $this );
		
		add_action( 'init', array( &$this, 'load_plugin_textdomain' ) );
		add_action( 'admin_menu', array( &$this, "admin_menu" ) );
		add_action( 'network_admin_menu', array( &$this, "network_admin_menu" ) );
		add_action( 'admin_notices', array( &$this, 'admin_notices' ) );
		add_action( 'wp_head', array( &$this, 'wp_head' ) );

		add_shortcode( 'murm-form', array( &$this, 'shortcode_handler' ) );
	}
	
	
	// i18n
	
	const txd = 'multisite-user-registration-manager';
	
	function load_plugin_textdomain() {
		$plugin_dir = basename( dirname( __FILE__ ) );
		load_plugin_textdomain( self::txd, false, $plugin_dir.'/languages' );
	}

	
	// often used strings
	
	const plugin_name = "Multisite User Registration Manager";
	const rr_title = "User registration requests";


	// constants
	
	const blog_options_page_slug = "murm-blog-options";
	const blog_moderation_page_slug = "murm-blog-moderation";
	const network_options_page_slug = "murm-network-options";
	const network_moderation_page_slug = "murm-network-moderation";
	const wls_log_name = "murm";
	const wls_extended_log_name = "murm-extended";


	// admin menu functions

	function admin_menu() {
		add_submenu_page( 'options-general.php', __( self::plugin_name, self::txd ), __( self::plugin_name, self::txd ), 
			'manage_options', self::blog_options_page_slug, array( &$this, 'blog_options_page' ) );
		if( $this->o->is_active ) {
			add_submenu_page( 'users.php', __( self::rr_title, self::txd ), __( self::rr_title, self::txd ), 
				'manage_options', self::blog_moderation_page_slug, array( &$this, 'blog_moderation_page' ) );
		}
	}


	function network_admin_menu() {
		add_submenu_page( 'users.php', __( self::rr_title, self::txd ),  __( self::rr_title, self::txd ), 
			'manage_network_users', self::network_moderation_page_slug, array( &$this, 'network_moderation_page' ) );
		add_submenu_page( 'settings.php', __( self::plugin_name, self::txd ), __( self::plugin_name, self::txd ),
			'manage_network_options', self::network_options_page_slug, array( &$this, 'network_options_page' ) );
	}
	
	
	// logging
	
	function log( $message, $category = 1 ) {
		if( defined( 'WLS' ) && wls_is_registered( self::wls_log_name ) ) {
			wls_simple_log( self::wls_log_name, $message, $category );
		} else if( $this->o->fallback_logging ) {
			//fallback
			$this->log_to_file( $message );
		}
	}

	function log_to_file( $message ) {
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


	function extended_log( $message ) {
		if( defined( 'WLS' ) && wls_is_registered( self::wls_extended_log_name ) ) {
			wls_simple_log( self::wls_extended_log_name, $message, 1 );
		} else if( $this->o->fallback_logging ) {
			//fallback
			$this->log_to_file( $message );
		}
	}


	function dberror_description() {
		global $wpdb;
		return "\$wpdb info: query \"{$wpdb->last_query}\", result \"{$wpdb->last_result}\", error \"{$wpdb->last_error}\".";
	}
	
	
	function ds( $message ) {
		if( is_super_admin() && defined( 'MURM_DEBUG' ) ) {
			$this->extended_log( "debug: $message" );
		}
	}
	
	
	// misc
	
	function nag( $message ) {
		echo( '<div id="message" class="updated"><p>'.$message.'</p></div>' );
	}


	function nagerr( $message ) {
		echo( '<div id="message" class="error"><p>'.$message.'</p></div>' );
	}
	

	function admin_notices() {
		if( !$this->o->is_active ) {
			return;
		}
	
		if( current_user_can( 'manage_options' ) && $this->d->has_blog_requests() ) {
			$this->nag( sprintf( 
				__( 'You have new registration requests for this blog. Please moderate them %shere%s.' , self::txd ), 
				"<a href=\"users.php?page=".self::blog_moderation_page_slug."\">", 
				'</a>' 
			) );
		}
	}
	
	
	function can_ban() {
		return function_exists( "suh_is_permban_active" ) && function_exists( "suh_add_permban" ) && function_exists( "suh_get_ip" ) && suh_is_permban_active();
	}
	
	
	function wp_head() {
		?>
		<style type='text/css' media='screen'>
			#murm p.error {
				font-weight: bold;
				color: red;
			}
		</style>
		<?php
	}


	function fix_blog_url( $blog ) {
		if( substr( $blog, 0, 7 ) != 'http://' ) {
			return 'http://'.$blog;
		} else {
			return $blog;
		}
	}
	
	
	// blog options page
	
	function blog_options_page() {
		$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'default';  

		switch( $action ) {
		case 'uninstall':		
			$this->log( 'Uninstalling MURM from this blog.', 2 );
			$db_ok = $this->d->drop_blog_table(); 
			if( !$db_ok ) {
				$this->nagerr( __( 'Error while removing MURM from your blog.' , self::txd ) );
				$this->log( 'ERROR while uninstalling MURM', 4 );
			} else {	
				$this->o->is_active = false;
				$this->o->is_installed_on_blog = false;				
			}
			$this->nag( __( 'MURM has been successfully removed from your blog.' , self::txd ) );
			$this->log( 'Successfully uninstalled.' );
			
			$this->blog_options_page_default();
			break;

		case 'update':
			$this->log( 'murm_options_page_update begin' );
			$this->o->is_active = isset( $_POST['murm_active'] );
			$this->o->show_nag = isset( $_POST['murm_shownag'] );
			$this->o->notify_by_mail = isset( $_POST['murm_mail_admin_on_request'] );
			$this->o->is_antispam_active = isset( $_POST['murm_antispam'] );
			$this->o->akismet_key = $_POST['murm_akismet_key'];

			if( $this->o->is_active && !$this->o->is_installed_on_blog ) {
				$this->log( 'installing MURM on this blog' );
	
				$db_ok = $this->d->create_tables();
	
				if( $db_ok ) {
					$this->o->is_installed_on_blog = true;
					$this->nag( __( 'MURM has been successfully installed on your blog.' , self::txd ) );
					$this->log( 'installed successfully', 2 );
				} else {
					$this->nagerr( __( 'Error while installing MURM on your blog.' , self::txd ) );
					$this->log( 'error while installing', 4 );
				}
			}

			$this->log( 'murm_options_page_update end' );
			$this->nag( __( 'Settings saved.' , self::txd ) );
		
			$this->blog_options_page_default();
			break;
		default:
			$this->blog_options_page_default();
			break;
		}
	}


	function blog_options_page_default() {
		?>
		<div class="wrap">
		    <h2><?php _e( 'Multisite User Registration Manager Settings' , self::txd ); ?></h2>
			<p><?php printf( __( 'Here you can activate the system for registration request processing on your blog. After that, insert a shortcode %s into your page or post in order to display a registration form. If your blog is closed for example with Members Only plugin, it is suitable to place this shortcode on a \'welcome\' page for non-registered visitors.' , self::txd ), '<code>[murm-form]</code>' ); ?></p>
			<p>
				<?php printf( __( 'In case of any questions, problems or feature requests kindly contact the %splugin developer%s.' , self::txd ), '<a href="mailto:zaantar@zaantar.eu?subject=[murm]">', '</a>' ); ?></p>
			<form method="post">
		        <input type="hidden" name="action" value="update" />
		        <table class="form-table">
		            <tr valign="top">
		            	<th><label for="murm_active"><?php _e( 'Activate on this blog' , self::txd ); ?></label></th>
		            	<td>
		            		<input type="checkbox" name="murm_active" <?php checked( $this->o->is_active ); ?> />
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th>
		            		<label><?php _e( 'Auto-approve requests', self::txd ); ?></label><br />
		            	</th>
		            	<td>
		            		<input type="checkbox" name="murm_autoapprove" <?php checked( $this->o->blog_autoapprove ); ?> />
		            	</td>
		            	<td>
		            		<small><?php _e( 'If checked, only network admins decide about request processing and you don\'t have to do anything (is is as if you actually immediately approved the request).' ); ?></small>
		            	</td>
		            </tr>
		          	<tr valign="top">
		            	<th><label for="murm_shownag"><?php _e( 'Show information nag if there are requests waiting for your moderation' , self::txd ); ?></label></th>
		            	<td>
		            		<input type="checkbox" name="murm_shownag" <?php checked( $this->o->show_nag ); ?> />
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th><label for="murm_mail_admin_on_request"><?php _e( 'Send me an e-mail on incoming registration request' , self::txd ); ?></label></th>
		            	<td>
		            		<input type="checkbox" name="murm_mail_admin_on_request" <?php checked( $this->o->notify_by_mail ); ?> />
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th><label for="murm_antispam"><?php _e( 'Antispam' , self::txd ); ?></label></th>
		            	<td>
		            		<input type="checkbox" name="murm_antispam" <?php checked( $this->o->is_antispam_active ); ?> />
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th><label for="murm_akismet_key"><?php _e( 'Akismet API key for antispam functionality' , self::txd ); ?></label></th>
		            	<td>
		            		<input type="text" name="murm_akismet_key" value="<?php echo $this->o->akismet_key; ?>" />
		            	</td>
		            	<td>
		            		<small>
		            			<?php printf(
		            				__( "If you encounter a problem with Akismet being used in MURM, please do not contact Akismet support - they probably cannot help you. Instead use the %splugin support forum%s.", self::txd ),
		            				"<a href=\"http://wordpress.org/support/plugin/multisite-user-registration-manager\">",
		            				"</a>"
		            			); ?>
		            		</small>
		            	</td>
		            </tr>
				</table>
				<p class="submit">
			        <input type="submit" class="button-primary" value="<?php _e( 'Save' , self::txd ); ?>" />    
			    </p>        
		    </form>
		    <h3><?php _e( 'Removal from this blog' , self::txd ); ?></h3>
		    <p><?php _e( 'Clicking the button below will deactivate MURM and delete all requests waiting for moderation. It doesn\'t affect requests already passed on to superadmin in any way.' , self::txd ); ?></p>
		    <form method="post">
		    	<input type="hidden" name="action" value="uninstall" />
		    	<p class="submit">
			        <input type="submit" value="<?php _e( 'Remove MURM from this blog' , self::txd ); ?>" />    
			    </p> 
		    </form>
		</div>
		<?php
	}
	
	
	// network options page
	
	function network_options_page() {
		$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'default';
		switch( $action ) {
		case 'update-settings':
			$options = $_POST['settings'];
			$options["superadmin_autoapprove"] = isset( $_POST["settings"]["superadmin_autoapprove"] );
			$options["admin_can_delete_request"] = isset( $_POST["settings"]["admin_can_delete_request"] );
			$options["extended_debug_mode"] = isset( $_POST["settings"]["extended_debug_mode"] );
			$options["fallback_logging"] = isset( $_POST["settings"]["fallback_logging"] );
			$options["hide_donation_button"] = isset( $_POST["settings"]["hide_donation_button"] );
			$options["to_user_deny_from_admin"]["enabled"] = isset( $_POST["settings"]["to_user_deny_from_admin"]["enabled"] );
			$options["to_admin_new_request"]["enabled"] = isset( $_POST["settings"]["to_admin_new_request"]["enabled"] );
			$options["to_superadmin_new_request"]["enabled"] = isset( $_POST["settings"]["to_superadmin_new_request"]["enabled"] );
			$options["to_user_deny_from_superadmin"]["enabled"] = isset( $_POST["settings"]["to_user_deny_from_superadmin"]["enabled"] );
			$options["to_admin_on_superadmin_deny"]["enabled"] = isset( $_POST["settings"]["to_admin_on_superadmin_deny"]["enabled"] );
			$options["to_admin_on_superadmin_approve"]["enabled"] = isset( $_POST["settings"]["to_admin_on_superadmin_approve"]["enabled"] );
			$options["to_new_user_on_approve"]["enabled"] = isset( $_POST["settings"]["to_new_user_on_approve"]["enabled"] );
			$options["to_existing_user_on_approve"]["enabled"] = isset( $_POST["settings"]["to_existing_user_on_approve"]["enabled"] );
			$options["activate_akismet"] = isset( $_POST["settings"]["activate_akismet"] );
			$options["ban_spammer"] = isset( $_POST["settings"]["ban_spammer"] );
			$options["log_spam_registration_requests"] = isset( $_POST["settings"]["log_spam_registration_requests"] );
			$this->o->update_network_options( $options );
			$this->network_options_page_default();
			break;
		case 'wls-register':
			wls_register( MURM_LOG_NAME, __( 'Multisite User Registration Manager events.', self::txd ) ); //TODO return value check
			$this->network_options_page_default();
			break;
		case 'wls-unregister':
			wls_unregister( MURM_LOG_NAME ); //TODO return value check
			$this->network_options_page_default();
			break;
		default:
			$this->network_options_page_default();
		}
	}
	
	
	function network_options_page_default() {	
		?>
		<div class="wrap">
			<h2><?php _e( 'Nastavení Multisite User Registration Manager', self::txd ); ?></h2>
			<?php
				if( !$this->o->hide_donation_button ) {
					?>
					<h3><?php _e( 'Please consider a donation', self::txd ); ?></h3>
					<p>
						<?php _e( 'I spend quite a lot of my precious time working on opensource WordPress plugins. If you find this one useful, please consider helping me develop it further. Even the smallest amount of money you are willing to spend will be welcome.', self::txd ); ?>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="39WB3KGYFB3NA">
							<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" style="border:none;" >
							<img style="display:none;" alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
						</form>
					</p>
					<?php
				}
			?>
			<form method="post">
		        <input type="hidden" name="action" value="update-settings" />
		        <h3><?php _e( 'Basic settings', self::txd ); ?></h3>
		    	<table class="form-table">
		        	<tr valign="top">
		            	<th>
		            		<label><?php _e( 'Auto-approve requests on Network admin level', self::txd ); ?></label><br />
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[superadmin_autoapprove]" 
		            			<?php checked( $this->o->superadmin_autoapprove ); ?>
		            		/>
		            	</td>
		            	<td>
		            		<small><?php _e( 'If checked, only blog admins decide about request processing and network admin doesn\'t have to do anything (is is as if he/she actually immediately approved the request). It is recommended to edit e-mail templates (below) accordingly.' ); ?></small>
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th>
		            		<label><?php _e( 'Allow blog owners to delete requests', self::txd ); ?></label><br />
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[admin_can_delete_request]" 
		            			<?php checked( $this->o->admin_can_delete_request ); ?>
		            		/>
		            	</td>
		            	<td>
		            		<small><?php _e( 'If checked, they can delete a request without the request author being notified.' ); ?></small>
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th>
		            		<label><?php _e( 'Extended debug mode', self::txd ); ?></label><br />
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[extended_debug_mode]" 
		            			<?php checked( $this->o->extended_debug_mode ); ?>
		            		/>
		            	</td>
		            	<td>
		            		<small><?php printf( __( 'After checking create a WLS log category named %s. All extra information will be logged here.' ), '<code>'.MURM_EXTENDED_LOG_NAME.'</code>' ); ?></small>
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th>
		            		<label><?php _e( 'Allow fallback logging to a file if WLS logging is not possible', self::txd ); ?></label><br />
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[fallback_logging]" 
		            			<?php checked( $this->o->fallback_logging ); ?>
		            		/>
		            	</td>
		            	<td>
		            		<small><?php _e( 'Log file will be created within plugin directory (you need appropriate file permissions for that).', MURM_TXD ); ?></small>
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th>
		            		<label><?php _e( 'Hide donation button', self::txd ); ?></label><br />
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[hide_donation_button]" 
		            			<?php checked( $this->o->hide_donation_button ); ?>
		            		/>
		            	</td>
		            </tr>
		        </table>
		        <h3><?php _e( 'Spam management', self::txd ); ?></h3>
		    	<table class="form-table">
		    		<tr valign="top">
		            	<th>
		            		<label><?php _e( 'Activate Akismet spam filter', self::txd ); ?></label><br />
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[activate_akismet]" <?php checked( $this->o->activate_akismet ); ?> />
		            	</td>
		            	<td>
		            		<small><?php _e( 'For Akismet spam filter to work you need to enter a valid Akismet API key.', MURM_TXD ); ?></small>
		            	</td>
		            </tr>
			        <tr valign="top">
			        	<th>
			        		<label><?php _e( 'Akismet API key' , self::txd ); ?></label>
			        	</th>
			        	<td>
			        		<input type="text" name="settings[akismet_key]" value="<?php echo esc_attr( $this->o->akismet_key ); ?>" />
			        	</td>
			        	<td>
		            		<small>
		            			<?php printf(
		            				__( "If you encounter a problem with Akismet being used in MURM, please do not contact Akismet support - they probably cannot help you. Instead use the %splugin support forum%s.", self::txd ),
		            				"<a href=\"http://wordpress.org/support/plugin/multisite-user-registration-manager\">",
		            				"</a>"
		            			); ?>
		            		</small>
		            	</td>
			        </tr>
			        <tr valign="top">
		            	<th>
		            		<label><?php _e( 'Ban spamming IPs with Superadmin Helper', self::txd ); ?></label><br />
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[ban_spammer]" <?php checked( $this->o->ban_spammer ); ?> />
		            	</td>
		            	<td>
		            		<small>
		            			<?php _e( 'Superadmin Helper plugin with permban activated is neccessary.', MURM_TXD ); ?><br />
		            			<?php 
		            				printf( 
				        				__("Permban capability is %s", MURM_TXD ), 
				        				$this->can_ban() ? "<strong>".__( "ON", MURM_TXD )."</strong>" : "<strong>".__( "OFF", MURM_TXD )."</strong>" 
		            				); 
		            			?>
		            		</small>
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th>
		            		<label><?php _e( 'Log spam registration requests', self::txd ); ?></label><br />
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[log_spam_registration_requests]" <?php checked( $this->o->log_spam_registration_requests ); ?> />
		            	</td>
		            </tr>
		    	</table>
		        <h3><?php _e( 'E-mail templates' ); ?></h3>
		        <table class="form-table">
		            <tr valign="top">
		            	<th>
		            		<label><?php _e( 'For request author on denial by blog owner', self::txd ); ?></label>
		            		<p style="font-size: x-small;"><?php _e( 'Within e-mail templates you can use following keywords:', self::txd ); ?></p>
		            		<ol style="font-size: x-small;">
				        		<li><code>%SITE_NAME%</code>: <?php _e( 'Name of the blog network', self::txd ); ?></li>
								<li><code>%SITE_URL%</code>: <?php _e( 'Network base URL (usually same as network main blog URL)', self::txd ); ?></li>
								<li><code>%SUPERADMIN_EMAIL%</code>: <?php _e( 'E-mail address of the network administrator', self::txd ); ?></li>
								<li><code>%BLOG_NAME%</code>: <?php _e( 'Name of current blog', self::txd ); ?></li>
								<li><code>%BLOG_URL%</code>: <?php _e( 'URL of current blog', self::txd ); ?></li>
							</ol>
							<p style="font-size: x-small;">...</p>
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[to_user_deny_from_admin][enabled]" <?php checked( $this->o->to_user_deny_from_admin['enabled'] ); ?> />&nbsp;<label><?php _e( 'Enabled', self::txd ); ?></label><br />
		            		<label><?php _e( 'Subject:', self::txd ); ?></label>&nbsp;<input type="text" name="settings[to_user_deny_from_admin][subject]" value="<?php echo $this->o->to_user_deny_from_admin['subject']; ?>" size="60" /><br />
		            		<textarea name="settings[to_user_deny_from_admin][message]" cols="80" rows="15"><?php 
		        				echo stripslashes( esc_textarea( $this->o->to_user_deny_from_admin['message'] ) ); 
		        			?></textarea>
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th>
		            		<label><?php _e( 'For blog owner on incoming request', self::txd ); ?></label><br />
		            		<ol start="6" style="font-size: x-small;">
				        		<li><code>%ADMIN_EMAIL%</code>: <?php _e( 'E-mail address of current blog administrator', self::txd ); ?></li>
								<li><code>%LOGIN_URL%</code>: <?php _e( 'Login page URL on current blog', self::txd ); ?></li>
								<li><code>%USER_NAME%</code>: <?php _e( 'User\'s name (only when logged in)', self::txd ); ?></li>
								<li><code>%USER_EMAIL%</code>: <?php _e( 'User\'s e-mail address (only when logged in)', self::txd ); ?></li>
								<li><code>%PASSWORD%</code>: <?php _e( 'New user\'s password (only when creating a new user account)', self::txd ); ?></li>
							</ol>
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[to_admin_new_request][enabled]" <?php checked( $this->o->to_admin_new_request['enabled'] ); ?> /><label>&nbsp;<?php _e( 'Enabled', self::txd ); ?></label><br />
		            		<label><?php _e( 'Subject:', self::txd ); ?></label>&nbsp;<input type="text" name="settings[to_admin_new_request][subject]" value="<?php echo $this->o->to_admin_new_request['subject']; ?>" size="60" /><br />
		            		<textarea name="settings[to_admin_new_request][message]" cols="80" rows="15"><?php 
		        				echo stripslashes( esc_textarea( $this->o->to_admin_new_request['message'] ) ); 
		        			?></textarea>
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th>
		            		<label><?php _e( 'For superadmin on incoming request', self::txd ); ?></label>
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[to_superadmin_new_request][enabled]" <?php checked( $this->o->to_superadmin_new_request['enabled'] ); ?> />&nbsp;<label><?php _e( 'Enabled', self::txd ); ?></label><br />
		            		<label><?php _e( 'Subject:', self::txd ); ?></label>&nbsp;<input type="text" name="settings[to_superadmin_new_request][subject]" value="<?php echo $this->o->to_superadmin_new_request['subject']; ?>" size="60" /><br />
		            		<textarea name="settings[to_superadmin_new_request][message]" cols="80" rows="15"><?php 
		        				echo stripslashes( esc_textarea( $this->o->to_superadmin_new_request['message'] ) ); 
		        			?></textarea>
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th>
		            		<label><?php _e( 'For request author on denial by superadmin', self::txd ); ?></label>
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[to_user_deny_from_superadmin][enabled]" <?php if( $this->o->to_user_deny_from_superadmin['enabled'] ) echo 'checked="checked"'; ?> />&nbsp;<label><?php _e( 'Enabled', self::txd ); ?></label><br />
		            		<label><?php _e( 'Subject:', self::txd ); ?></label>&nbsp;<input type="text" name="settings[to_user_deny_from_superadmin][subject]" value="<?php echo $this->o->to_user_deny_from_superadmin['subject']; ?>" size="60"  /><br />
		            		<textarea name="settings[to_user_deny_from_superadmin][message]" cols="80" rows="15"><?php 
		        				echo stripslashes( esc_textarea( $this->o->to_user_deny_from_superadmin['message'] ) ); 
		        			?></textarea>
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th>
		            		<label><?php _e( 'For blog owner on denial by superadmin', self::txd ); ?></label>
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[to_admin_on_superadmin_deny][enabled]" <?php checked( $this->o->to_admin_on_superadmin_deny['enabled'] ); ?> />&nbsp;<label><?php _e( 'Enabled', self::txd ); ?></label><br />
		            		<label><?php _e( 'Subject:', self::txd ); ?></label>&nbsp;<input type="text" name="settings[to_admin_on_superadmin_deny][subject]" value="<?php echo $this->o->to_admin_on_superadmin_deny['subject']; ?>" size="60"  /><br />
		            		<textarea name="settings[to_admin_on_superadmin_deny][message]" cols="80" rows="15"><?php 
		        				echo stripslashes( esc_textarea( $this->o->to_admin_on_superadmin_deny['message'] ) ); 
		        			?></textarea>
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th>
		            		<label><?php _e( 'For blog owner on approval by superadmin', self::txd ); ?></label>
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[to_admin_on_superadmin_approve][enabled]" <?php checked( $this->o->to_admin_on_superadmin_approve['enabled'] ); ?> />&nbsp;<label><?php _e( 'Enabled', self::txd ); ?></label><br />
		            		<label><?php _e( 'Subject:', self::txd ); ?></label>&nbsp;<input type="text" name="settings[to_admin_on_superadmin_approve][subject]" value="<?php echo $this->o->to_admin_on_superadmin_approve['subject']; ?>" size="60"  /><br />
		            		<textarea name="settings[to_admin_on_superadmin_approve][message]" cols="80" rows="15"><?php 
		        				echo stripslashes( esc_textarea( $this->o->to_admin_on_superadmin_approve['message'] ) ); 
		        			?></textarea>
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th>
		            		<label><?php _e( 'For request author on approval (when creating new user account)', self::txd ); ?></label>
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[to_new_user_on_approve][enabled]" <?php checked( $this->o->to_new_user_on_approve['enabled'] ); ?> />&nbsp;<label><?php _e( 'Enabled', self::txd ); ?></label>&nbsp;<small><?php _e( '(why would you ever uncheck that?)', self::txd ); ?></small><br />
		            		<label><?php _e( 'Subject:', self::txd ); ?></label>&nbsp;<input type="text" name="settings[to_new_user_on_approve][subject]" value="<?php echo $this->o->to_new_user_on_approve['subject']; ?>" size="60" /><br />
		            		<textarea name="settings[to_new_user_on_approve][message]" cols="80" rows="15"><?php 
		        				echo stripslashes( esc_textarea( $this->o->to_new_user_on_approve['message'] ) ); 
		        			?></textarea>
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th>
		            		<label><?php _e( 'For request author on approval (when registering an existing user account)', self::txd ); ?></label>
		            	</th>
		            	<td>
		            		<input type="checkbox" name="settings[to_existing_user_on_approve][enabled]" <?php checked( $this->o->to_existing_user_on_approve['enabled'] ); ?> />&nbsp;<label><?php _e( 'Enabled', self::txd ); ?></label><br />
		            		<label><?php _e( 'Subject:', self::txd ); ?></label>&nbsp;<input type="text" name="settings[to_existing_user_on_approve][subject]" value="<?php echo $this->o->to_existing_user_on_approve['subject']; ?>" size="60" /><br />
		            		<textarea name="settings[to_existing_user_on_approve][message]" cols="80" rows="15"><?php 
		        				echo stripslashes( esc_textarea( $this->o->to_existing_user_on_approve['message'] ) ); 
		        			?></textarea>
		            	</td>
		            </tr>
				</table>
				<p class="submit">
			        <input type="submit" class="button-primary" value="<?php _e( 'Save', self::txd ); ?>" />    
			    </p>
			</form>
			<?php 
		    	if( defined( 'WLS' ) && is_super_admin() ) {
		    		?>
		    		<h3><?php _e( 'Registration with WLS' , self::txd ); ?></h3>
		    		<p><?php _e( 'WordPress Logging Service has been detected.' , self::txd ); ?></p>
		    		<?php
		    			if( !wls_is_registered( MURM_LOG_NAME ) ) {
		    				?>
							<form method="post">
								<input type="hidden" name="action" value="wls-register" />
								<p class="submit">
									<input type="submit" value="<?php _e( 'Register MURM with WLS' , self::txd ); ?>" />    
								</p> 
							</form>
							<?php
						} else {
							?>
							<form method="post">
								<input type="hidden" name="action" value="wls-unregister" />
								<p class="submit">
									<input type="submit" style="color:red;" value="<?php _e( 'Unregister MURM and delete all log entries' , self::txd ); ?>" />    
								</p> 
							</form>
							<?php
						}
					?>
				
					<?php
		    	}
		    ?>		
		</div>
		<?php
	}

	
	
	// mailing
	

	function parse_mail( $message, $blog_id, $request_data, $password = '[not available]' ) {
	
		switch_to_blog( $blog_id );
	
		$patterns[0] = '/%BLOG_NAME%/';
		$replacements[0] = get_bloginfo( 'name' );
	
		$patterns[1] = '/%ADMIN_EMAIL%/';
		$replacements[1] = get_bloginfo( 'admin_email' );
	
		$patterns[2] = '/%LOGIN_URL%/';
		$replacements[2] = wp_login_url();
	
		restore_current_blog();
	
		$patterns[3] = '/%USER_EMAIL%/';
		$replacements[3] = $request_data->email;

		$patterns[4] = '/%SITE_NAME%/';
		$replacements[4] = get_site_option( 'site_name' );
	
		$patterns[5] = '/%SITE_URL%/';
		$replacements[5] = get_site_url( 1 );
	
		$patterns[6] = '/%SUPERADMIN_EMAIL%/';
		$replacements[6] = get_site_option( 'admin_email' );
	
		$patterns[7] = '/%PASSWORD%/';
		$replacements[7] = $password;
	
		$patterns[8] = '/%USER_NAME%/';
		$replacements[8] = $request_data->username;	
	
		$patterns[9] = '/%BLOG_URL%/';
		$replacements[9] = get_site_url( $blog_id );
	
		$result = preg_replace( $patterns, $replacements, $message );
		return $result;
	}


	function is_current_user_email( $email ) {
		$userdata = get_userdata( get_current_user_id() );
		return ( $userdata->email == $email );
	}
	
	
	// shortcode handler
	
	function shortcode_handler( $action = "" ) {
		if( !$this->o->is_active ) {
			$this->log( 'shortcode used while murm inactive', 3 );
			return;
		}

		if( empty( $action ) ) {
			$action = isset($_REQUEST['murmaction']) ? $_REQUEST['murmaction'] : 'default';
		}
		
		switch( $action ) {
		case 'commit':
			return $this->shortcode_handler_commit();
			break;
		default:
			if( is_user_logged_in() ) {
				return $this->shortcode_handler_logged();
			} else {
				return $this->shortcode_handler_new();
			}
		}
	}
	
	
	function get_shortcode_args() {
		if( isset( $_POST['murmdata'] ) ) {
			return $_POST['murmdata'];
		} else {
			return array(
				'username' => '',
				'email' => '',
				'displayname' => '',
				'url' => '',
				'message' => ''
			);
		}
	}


	function shortcode_handler_new() {	
		extract( $this->get_shortcode_args() );
	
		$code = '
		<div id="murm">
			<a name="murm"></a>
			<form method="post" action="'.get_permalink().'#murm">
		        <input type="hidden" name="murmaction" value="commit" />
		        <table class="form-table">
		            <tr>
		            	<th><label for="murmdata[username]">'.__( 'Username' , self::txd ).'</label></th>
		            	<td>
		            		<input type="text" name="murmdata[username]" value="'.$username.'" />
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<td colspan="2"><small>'.__( 'Mandatory entry. Can contain only small letters without diacritics and numbers, must be at least four characters long.' , self::txd ).'</small></td>
		            </tr>
		            <tr>
		            	<th><label for="murmdata[email]">'.__( 'E-mail address' , self::txd ).'</label></th>
		            	<td>
		            		<input type="text" name="murmdata[email]" value="'.$email.'" />
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<td colspan="2"><small>'.__( 'Mandatory entry' , self::txd ).'</small></td>
		            </tr>
		            <tr>
		            	<th><label for="murmdata[displayname]">'.__( 'User name to be displayed publicly' , self::txd ).'</label></th>
		            	<td>
		            		<input type="text" name="murmdata[displayname]" value="'.$displayname.'" />
		            	</td>
		            </tr>
		            <tr>
		            	<th><label for="murmdata[url]">'.__( 'Your web URL' , self::txd ).'</label></th>
		            	<td>
		            		<input type="text" name="murmdata[url]" value="'.$url.'" />
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<th><label for="murmdata[message]">'.__( 'Message for the blog owner' , self::txd ).'</label></th>
		            	<td>
		            		<textarea name="murmdata[message]" wrap="soft">'.$message.'</textarea>
		            	</td>
		            </tr>
		            <tr valign="top">
		            	<td colspan="2"><small>'.__( 'If you and blog owner don\'t know each other, it may help if you write something about yourself. Information about how you found this blog may be also useful.' , self::txd ).'</small></td>
		            </tr>
				</table>
				<p class="submit">
			        <input type="submit" value="'.__( 'Submit' , self::txd ).'" />    
			    </p> 
			</form>
		</div>';
		return $code;
	}


	function shortcode_handler_logged() {
		$user = wp_get_current_user();
		extract( $this->get_shortcode_args() );
		$code = '
			<div id="murm">
				<p>'.sprintf( __( 'You are currently logged in as user %s. If you intend to request registration on this blog with this user account, just click the submit button below. In the other case you have to %slog out%s first.' , self::txd ), '<strong>'.$user->user_login.'</strong>', '<a href="'.wp_logout_url( get_permalink() ).'">', '</a>' ).'<!--
					Momentálně jste přihlášeni jako uživatel <strong>'.$user->user_login.'</strong>. Chcete-li pod tímto jménem požádat o registraci na tomto blogu, stačí kliknout tlačíto pro odeslání žádosti. V opačném případě je nutno se nejdříve <a href="'.wp_logout_url( get_permalink() ).'">odhlásit</a>. -->
				</p>
				<form method="post" action="'.get_permalink().'">
				    <input type="hidden" name="murmaction" value="commit" />
				    <input type="hidden" name="murmdata[username]" value="'.$user->user_login.'" />
				    <input type="hidden" name="murmdata[email]" value="'.$user->user_email.'" />
				    <table class="form-table">
						<tr valign="top">
		            	<th><label for="murmdata[message]">'.__( 'Message for the blog owner' , self::txd ).'</label></th>
		            	<td>
		            		<textarea name="murmdata[message]" wrap="soft">'.$message.'</textarea>
		            	</td>
				        </tr>
				        <tr valign="top">
				        	<td colspan="2"><small>'.__( 'If you and blog owner don\'t know each other, it may help if you write something about yourself. Information about how you found this blog may be also useful.' , self::txd ).'</small></td>
				        </tr>
		            </table>
					<p class="submit">
					    <input type="submit" value="'.__( 'Submit' , self::txd ).'" />
					</p> 
				</form>
			</div>';
		return $code;
	}
	
	
	function shortcode_handler_commit() {	
		extract( $this->get_shortcode_args() );
	
		$username = strtolower( $username );
	
		if( $this->is_spam_registration( $username, $email, $url, $message ) ) {
			if( $log_spam_registration_requests ) {
				$this->log( "Denied registration request - recognized as spam", 2 );
				$this->log( "Registration request data: \"$username\", \"$displayname\", \"$email\", \"$url\", \"$message\"", 1 );
			}
			return $this->shortcode_error( __( 'Error! Your request has been evaluated as spam by Akismet. If this is a mistake, please contact blog owner or site network administrator.' , self::txd ) );
		}
	
		$this->log( "Incoming registration request: \"$username\", \"$displayname\", \"$email\", \"$url\", \"$message\"", 2 );

		if( !$this->is_username_correct( $username ) ) {
			$this->log( 'rr denied - incorrect username', 3 );
			return $this->shortcode_error( __( 'Error! User login name must be at least four characters long and can contain only small letters without diacritics or numbers.' , self::txd ) );
		}
	
		if( !$this->is_email_correct( $email ) ) {
			$this->log( 'rr denied - incorrect email', 3 );
			return $this->shortcode_error( __( 'Error! Your e-mail address seems to be invalid.' , self::txd ) );
		}
	
		if( !$this->is_valid_combination( $username, $email ) ) {
			$this->log( 'rr denied - invalid combination', 3 );
			return $this->shortcode_error( __( 'Error! The combination of username and e-mail address is not valid (at least one of them has already been used in this site network.' , self::txd ) );
		}
	
		if( $this->d->is_duplicate_request( $username, $email ) ) {
			$this->log( 'rr denied - duplicate request', 3 );
			return $this->shortcode_error( __( 'Error! Another request with this username or e-mail address has already been submitted.' , self::txd ) );
		}
	
		if( $extended_debug_mode ) {
	
		}
	
		$rid = $this->d->create_blog_request( $username, $displayname, $email, $url, $message );		
	
		if( $rid == 0 ) {
			$this->log( 'error while commiting rr', 4 );
			$this->extended_log( $this->dberror_description() );
			return $this->shortcode_error( sprintf( __( 'Database error when submitting your request. Please contact the %splugin developer%s.' , self::txd ), '<a href="mailto:zaantar@zaantar.eu">', '</a>' ) );
		} 
	
		if( $this->o->notify_by_mail ) {
			$this->log( 'Sending notification about new request to blog admin' );
			$request = $this->d->get_blog_request( $rid );
			$subject = $this->parse_mail( $this->o->to_admin_new_request['subject'], $blog_id, $request );
			$message = $this->parse_mail( $this->o->to_admin_new_request['message'], $blog_id, $request );
			$mail = get_bloginfo( 'admin_email' );
			$sent = wp_mail( $mail , $subject, $message );
			$this->extended_log( "Message to '$mail' with subject '$subject': $message; wp_mail(...) = $sent" );
		} else {
			$this->extended_log( "o->notify_by_mail == '{$this->o->notify_by_mail}', not sending notification to blog admin." );
		}
	
		$this->log( 'RR successfully commited to database with id=='.$rid );
	
		if( $this->o->blog_autoapprove ) {
			$this->log( "Autoapprove on admin level is enabled - approving request." );
			$this->blog_approve_request( $rid );
		} else {
			$this->extended_log( "Autoapprove on admin level is disabled." );
		}
	
		return '<p>'.__( 'Your request has been submitted. Now please be patient, we will inform you about any further development via e-mail.' , self::txd ).'</p>';
	}
	
	
	function is_spam_registration( $username, $email, $url, $content ) {
		if( $this->o->is_antispam_active ) {
			$key = $this->o->akismet_key;
			$this->extended_log( "Antispam activated for this blog - checking RR. Akismet key is $key." );
		} else if( $this->o->activate_akismet ) {
			$key = $this->o->akismet_key;
			$this->extended_log( "Antispam activated for whole network - checking RR. Akismet key is $key." );
		} else {
			$this->extended_log( "Antispam is not activated." );
			return false;
		}
	
		$akismet = new MurmAkismet( home_url() ,$key);
		$akismet->setCommentAuthor( $username );
		$akismet->setCommentAuthorEmail( $email );
		$akismet->setCommentAuthorURL( $url );
		$akismet->setCommentContent( $content );
		$akismet->setPermalink( get_permalink() );

		$is_spam = $akismet->isCommentSpam();
	
		if( $is_spam && $ban_spammer && $this->can_ban() ) {
			$ip = suh_get_ip();
			if( $log_spam_registration_requests ) {
				$this->log( "Banning spammer IP \"$ip\"." );
			}
			suh_add_permban( $ip );
		}
	
		return $is_spam;
	}


	function is_username_correct( $username ) {
		$lenok = ( strlen( $username ) >= 4 );
		$charsok = preg_match( '/^[a-z0-9]+$/u', $username );
		return $lenok && $charsok && validate_username( $username );
	}


	function is_email_correct( $email ) {
		return is_email( $email );
	}


	function shortcode_error( $message ) {
		return '<div id="murm"><a name="murm"></a><p class="error">'.$message.'</p></div>'.$this->shortcode_handler( "default" );
	}
	
	
	function is_valid_combination( $username, $email ) {
		$uex = username_exists( $username );
		$eex = email_exists( $email );
	
		if( ($uex && !$eex) or (!$uex && $eex) ) {
			return false;
		} else if( $uex ) {
			$user = get_userdatabylogin( $username );
			if( $user->user_email != $email ) {
				return false;
			}
		}
	
		return true;
	}
	
	
	// blog moderation

	function blog_moderation_page() {
		if( !$this->o->is_active ) {
			return;
		}
	
		$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'default';

		switch( $action ) {
		case 'approve':
			$ret = $this->blog_approve_request( $_REQUEST['id'] );
			$this->blog_moderation_page_default( $ret );
			break;
		case 'deny':
			$ret = $this->blog_deny_request( $_REQUEST['id'] );
			$this->blog_moderation_page_default( $ret );
			break;
		case 'confirm-delete':
			if( $this->o->admin_can_delete_request or is_super_admin() ) {
				$this->blog_moderation_page_confirm_delete();
			} else {
				$this->blog_moderation_page_default();
			}
			break;
		case 'delete':
			$ret = array();
			if( $this->o->admin_can_delete_request or is_super_admin() ) {
				$ret = $this->blog_delete_request( $_REQUEST['id'] );
			}
			$this->blog_moderation_page_default( $ret );
			break;
		default:
			$this->blog_moderation_page_default();
		}
	}


	function blog_moderation_page_default( $ret = array() ) {

		if( !empty( $ret ) ) {
			if( $ret["result"] ) {
				$this->nag( $ret["message"] );
			} else {
				$this->nagerr( $ret["message"] );
			}
		}

		$requests = $this->d->get_blog_requests();
	
		?>
		<div class="wrap">
			<h2><?php _e( 'Registration requests on this blog' , self::txd ); ?>
			<table class="widefat" cellspacing="0">
				<thead>
				    <tr>
				        <th scope="col" class="manage-column"><?php _e( 'id' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'User name' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'E-mail' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Displayed name' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Web page' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Message for blog owner' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Action' , self::txd ); ?></th>
					</tr>
				</thead>
				<tfoot>
				    <tr>
				        <th scope="col" class="manage-column"><?php _e( 'id' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'User name' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'E-mail' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Displayed name' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Web page' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Message for blog owner' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Action' , self::txd ); ?></th>
					</tr>
				</tfoot>
				<?php
					foreach( $requests as $request ) {
						?>
						<tr>
							<td><code><small><?php echo $request->id; ?></small></code></td>
							<td><?php echo $request->username; ?></td>
							<td><a href="mailto:<?php echo $request->email; ?>?subject=[<?php bloginfo( 'name '); ?>] <?php _e( 'Registration request', self::txd ); ?>"><?php echo $request->email; ?></a></td>
							<td><?php echo $request->displayname; ?></td>
						
							<td><a href="<?php echo $this->fix_blog_url( $request->url ); ?>"><?php echo $request->url; ?></a></td>
							<td><?php echo $request->message; ?></td>
							<td>
								<a href="users.php?page=<?php echo self::blog_moderation_page_slug; ?>&action=approve&id=<?php echo $request->id; ?>" style="color: green;"><?php _e( 'Approve' , self::txd ); ?></a><br />
								<a href="users.php?page=<?php echo self::blog_moderation_page_slug; ?>&action=deny&id=<?php echo $request->id; ?>" style="color: red;"><?php _e( 'Deny' , self::txd ); ?></a><br />
								<?php
									if( $this->o->admin_can_delete_request or is_super_admin() ) {
										?>
										<a href="users.php?page=<?php echo self::blog_moderation_page_slug; ?>&action=confirm-delete&id=<?php echo $request->id; ?>" 
											style="color: grey;"
										>
											<?php _e( 'Delete' , self::txd ); ?>
										</a>
										<?php
									}
								?>
							</td>
						</tr>
				
						<?php
					}
				?>
			</table>
		</div>
		<?php
	}
	
	
	function blog_moderation_page_confirm_delete() {
		$rid = $_REQUEST['id'];
		$request = $this->d->get_blog_request( $rid );
		?>
		<div class="wrap">
			<h2><?php _e( 'Registration requests on this blog' , self::txd ); ?></h2>
			<p><?php printf( __( 'You are about to delete reqistration request no.%d (user %s with e-mail %s). Do you really want to proceed?' , self::txd ), $rid, '<em>'.$request->username.'</em>', '<em>'.$request->email.'</em>' ); ?></p>
			<p><?php _e( 'When deleting a request, the requesting person is not informed about it (on the contrary to denying the request). You should use this function only to delete spam or otherwise processed requests.' , self::txd ); ?></p>
			<form method="post" action="users.php?page=<?php echo self::blog_moderation_page_slug; ?>">
				<input type="hidden" name="action" value="delete" />
				<input type="hidden" name="id" value="<?php echo $rid; ?>" />
				<p class="submit">
			        <input type="submit" value="<?php _e( 'Delete request' , self::txd ); ?>" />    
			    </p> 
			</form>
			<p>
				&laquo; <a href="users.php?page=<?php echo self::blog_moderation_page_slug; ?>"><?php _e( 'Back to request overview' , self::txd ); ?></a> &laquo;
			</p>
		</div>
		<?php
	}



	function blog_approve_request( $rid ) {
		global $blog_id;
	
		$this->log( 'admin approving registration request '.$rid );
	
		$request = $this->d->get_blog_request( $rid );
	
		if( $request == NULL ) {
			$this->log( 'error - rr '.$rid.' doesnt exist', 4 );
			return array( 
				"result" => false,
				"message" => sprintf( __( 'Request no.%d was not found in the database, so it cannot be approved. If you think this is an error, please contact the plugin developer.' , self::txd ), $rid )
			);
		}
	
		$insert_data = array(
			'blog_id' => $blog_id,
			'approved_by' => get_current_user_id(),
			'username' => $request->username,
			'email' => $request->email,
			'displayname' => $request->displayname,
			'url' => $request->url,
			'message' => $request->message
		);
	
		$sup_req_id = $this->d->create_network_request( $insert_data );
	
		if( $sup_req_id == 0 ) {
			$this->log( "Error while inserting to superadmin moderation table: ".print_r( $insert_data, TRUE ), 4 );
			$this->extended_log( $this->dberror_description() );
			return array(
				"result" => false,
				"message" => sprintf( __( 'Database error, request no.%d could not be processed. Please contact the plugin developer.' , self::txd ), $rid )
			);
		}
		$this->log( sprintf( 'Request no.%d forwarded into network admin level with id==%d.', $rid, $sup_req_id ), 1 );

		$superadmin_email = get_site_option( 'admin_email' );
		$superadmin_is_current_user = false;
		if( $this->is_current_user_email( $superadmin_email ) or is_super_admin() ) {
			$this->log( 'skipping mail to superadmin, because it\'s the current user', 2 );
			$superadmin_is_current_user = true;
		} else {
			if( $this->o->to_superadmin_new_request['enabled'] ) {
				$this->log( 'sending mail to superadmin' );
				$subject = $this->parse_mail( $this->o->to_superadmin_new_request['subject'], $blog_id, $request );
				$message = $this->parse_mail( $this->o->to_superadmin_new_request['message'], $blog_id, $request );
				wp_mail( $this->o->superadmin_email, $subject, $message );
			}
		}
	
		$dok = $this->d->delete_blog_request( $rid );
	
		$ret = array( "result" => true, "message" => "" );
	
		if( $dok ) {
			$this->log( 'rr approved by admin', 2 );
			if( $this->o->superadmin_autoapprove ) {
				$this->log( '$superadmin_autoapprove==true', 1 );
				$ret = $this->network_approve_request( $sup_req_id );
			} else if ( $superadmin_is_current_user ) {
				$ret["message"] = sprintf( __( 'Request no.%d has been approved on the single blog level. Now please continue with the approval process as the network administrator via %sthis link%s.' , self::txd ), $rid, '<a href="/wp-admin/network/users.php?page='.self::network_moderation_page_slug.'&action=approve&id='.$sup_req_id.'">', '</a>' );
			} else {
				$ret["message"] = sprintf( __( 'Request no.%d has been approved and forwarded to superadmin for moderation. Now please be patient, we will inform you about further development via e-mail.' , self::txd ), $rid );
			}
		
		} else {
			$ret["message"] = sprintf( __( 'Database error, request no.%d could not be fully processed. Please contact the plugin developer.' , self::txd ), $rid );
			$ret["result"] = false;
			$this->log( 'Error while removing rr from db', 4 );
			$this->extended_log( $this->dberror_description() );
		}
		return $ret;
	}
	
	
	function blog_deny_request( $rid ) {
		$this->log( 'admin denying registration request '.$rid );
		$request = $this->d->get_blog_request( $rid );
		if( $request == NULL ) {
			$this->log( 'error - rr '.$rid.' doesnt exist', 4 );
			return array(
				"result" => false,
				"message" => sprintf( __( 'Request no.%d was not found in the database, so it cannot be denied. If you think this is an error, please contact the plugin developer.' , self::txd ), $rid )
			);
		}
	
		if( $this->o->to_user_deny_from_admin['enabled'] ) {
			$this->log( 'sending mail to requesting user' );
			$subject = $this->parse_mail( $this->o->to_user_deny_from_admin['subject'], $blog_id, $request );
			$message = $this->parse_mail( $this->o->to_user_deny_from_admin['message'], $blog_id, $request );	
			wp_mail( $request->email, $subject, $message );
		}
	
		$dok = $this->d->delete_blog_request( $rid );
	
		if( $dok ) {
			$this->log( 'rr successfully denied', 2 );
			return array(
				"result" => true,
				"message" => sprintf( __( 'Request no.%d has been denied and an information e-mail has been sent to the request author.' , self::txd ), $rid )
			);
		} else {
			$this->log( 'Error while removing rr from db', 4 );
			$this->extended_log( $this->dberror_description() );
			return array(
				"result" => false,
				"message" => sprintf( __( 'Database error, request no.%d could not be fully processed. Please contact the plugin developer.' , self::txd ), $rid )
			);		
		}
	}


	function blog_delete_request( $rid ) {
		$this->log( 'admin deleting registration request '.$rid );
		$dok = $this->d->delete_blog_request( $rid );
		if( $dok ) {
			$this->log( 'rr '.$rid.' successfully deleted', 2 );
			return array(
				"result" => true,
				"message" => sprintf( __( 'Request no.%d has been deleted and an information e-mail has <strong>not</strong> been sent to the request author.' , self::txd ), $rid )
			);
		} else {
			$this->log( 'Error while deleting rr '.$rid.' from db', 4 );
			$this->extended_log( $this->dberror_description() );
			return array(
				"result" => false,
				"message" => sprintf( __( 'Database error, request no.%d could not be fully processed. Please contact the plugin developer.' , self::txd ), $rid )
			);
		}

	}
	
	
	// network moderation
	
	function network_moderation_page() {	
		$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'default';

		switch( $action ) {
		case 'deny':
			$ret = $this->network_deny_request();
			$this->network_moderation_page_default( $ret );
			break;
		case 'approve':
			$ret = $this->network_approve_request( $_REQUEST['id'] );
			$this->network_moderation_page_default( $ret );
			break;
		case 'confirm-delete':
			$this->network_moderation_page_confirm_delete();
			break;
		case 'delete':
			$ret = $this->network_delete_request();
			$this->network_moderation_page_default( $ret );
			break;
		default:
			$this->network_moderation_page_default();
		}
	}


	function network_moderation_page_default( $ret = array() ) {
		if( !empty( $ret ) ) {
			if( $ret["result"] ) {
				$this->nag( $ret["message"] );
			} else {
				$this->nagerr( $ret["message"] );
			}
		}
	
		global $wpdb;
	
		$query = '
			SELECT requests.id AS id, requests.username AS username, requests.email AS email, 
				requests.displayname AS displayname, requests.url AS url, requests.message AS message, 
				requests.blog_id AS blog_id, users.user_login AS approved_by, blogs.domain AS domain
			FROM ( '.$wpdb->base_prefix.'murm_requests AS requests
				JOIN  '.$wpdb->users.' AS users
				ON requests.approved_by = users.ID )
				JOIN '.$wpdb->base_prefix.'blogs AS blogs
				ON requests.blog_id = blogs.blog_id
			ORDER BY
				domain ASC,
				username ASC';
		
		$requests = $wpdb->get_results( $query );
	
		?>
		<div class="wrap">
			<h2><?php _e( 'MURM Network Registration Request' , self::txd ); ?></h2>
			<table class="widefat" cellspacing="0">
				<thead>
				    <tr>
				        <th scope="col" class="manage-column"><?php _e( 'id' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Blog' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'User name' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'E-mail' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Displayed name' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Web page' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Message for blog owner' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Approved by' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Action' , self::txd ); ?></th>
					</tr>
				</thead>
				<tfoot>
				    <tr>
				        <th scope="col" class="manage-column"><?php _e( 'id' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Blog' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'User name' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'E-mail' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Displayed name' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Web page' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Message for blog owner' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Approved by' , self::txd ); ?></th>
				        <th scope="col" class="manage-column"><?php _e( 'Action' , self::txd ); ?></th>
					</tr>
				</tfoot>
				<?php
					foreach( $requests as $request ) {
						?>
						<tr>
							<td><code><small><?php echo $request->id; ?></small></code></td>
							<td><?php echo $request->domain; ?></td>
							<td><?php echo $request->username; ?></td>
							<td><a href="mailto:<?php echo $request->email; ?>?subject=[<?php echo $request->domain; ?>] <?php _e( 'Registration request', self::txd ); ?>"><?php echo $request->email; ?></a></td>
							<td><?php echo $request->displayname; ?></td>
							<td><a href="<?php echo $this->fix_blog_url( $request->url ); ?>"><?php echo $request->url; ?></a></td>
							<td><?php echo $request->message; ?></td>
							<td><?php echo $request->approved_by; ?></td>
							<td>
								<a href="users.php?page=<?php echo self::network_moderation_page_slug; ?>&action=approve&id=<?php echo $request->id; ?>" style="color: green;"><?php _e( 'Approve' , self::txd ); ?></a><br />
								<a href="users.php?page=<?php echo self::network_moderation_page_slug; ?>&action=deny&id=<?php echo $request->id; ?>" style="color: red;"><?php _e( 'Deny' , self::txd ); ?></a><br />
								<a href="users.php?page=<?php echo self::network_moderation_page_slug; ?>&action=confirm-delete&id=<?php echo $request->id; ?>" style="color: grey;" ><?php _e( 'Delete' , self::txd ); ?></a>
							</td>
						</tr>
				
						<?php
					}
				?>
			</table>
		</div>
		<?php
	}
	
	
	function network_moderation_page_confirm_delete() {
		$rid = $_REQUEST['id'];
		$request = $this->d->get_network_request( $rid );
		?>
		<div class="wrap">
			<h2><?php _e( 'MURM Network Registration Request' , self::txd ); ?></h2>
			<p><?php printf( __( 'You are about to delete reqistration request no.%d (user %s with e-mail %s). Do you really want to proceed?' , self::txd ), $rid, '<em>'.$request->username.'</em>', '<em>'.$request->email.'</em>' ); ?></p>
			<p><?php _e( 'When deleting a request, the requesting person is not informed about it (on the contrary to denying the request). You should use this function only to delete spam or otherwise processed requests.' , self::txd ); ?></p>
			<form method="post" action="users.php?page=<?php echo self::network_moderation_page_slug; ?>">
				<input type="hidden" name="action" value="delete" />
				<input type="hidden" name="id" value="<?php echo $rid; ?>" />
				<p class="submit">
			        <input type="submit" value="<?php _e( 'Delete request' , self::txd ); ?>" />    
			    </p> 
			</form>
			<p>
				&laquo; <a href="users.php?page=<?php echo self::network_moderation_page_slug; ?>"><?php _e( 'Back to reguest overview' , self::txd ); ?></a> &laquo;
			</p>
		</div>
		<?php
	}



	function network_approve_request( $rid ) {

		$this->log( 'superadmin approving request '.$rid );

		$request = $this->d->get_network_request( $rid );
	
		if( $request == NULL ) {
			$this->log( 'error - rr '.$rid.' doesnt exist', 4 );
			return array(
				"result" => false,
				"message" => sprintf( __( 'Request no.%d was not found in the database, so it cannot be approved. If you think this is an error, please contact the plugin developer.' , self::txd ), $rid )
			);
		}
	
		// vytvořit uživatele
		if( !$this->is_valid_combination( $request->username, $request->email ) ) {
			$this->log( 'error - invalid username and email combination', 4 );
			return array(
				"result" => false,
				"message" => sprintf( __( 'Cannot create the user account - combination of username and e-mail (%s, %s) is already taken.' , self::txd ), $request->username , $request->email ) 
			);
		}
		
		if( username_exists( $request->username ) ) {
			$this->log( 'user already exists - adding to blog' );
			$user = get_userdatabylogin( $request->username );
		
			switch_to_blog( $request->blog_id );
			if( user_can( $user->ID, 'read' ) ) {
				$this->log( 'error - user '.$request->username.' already registered on blog '.$request->blog_id, 4 );
				return array(
					"result" => false,
					"message" => sprintf( __( 'User %s is already registered on this blog. Cannot continue.' , self::txd ), $request->username ) 
				);
			} 
			restore_current_blog();
		
			add_user_to_blog( $request->blog_id, $user->ID, 'subscriber' );
		
			if( $this->o->to_existing_user_on_approve['enabled'] ) {
				$user_subject = $this->parse_mail( $this->o->to_existing_user_on_approve['subject'], $request->blog_id, $request );
				$user_message = $this->parse_mail( $this->o->to_existing_user_on_approve['message'], $request->blog_id, $request );	
			}
		
		} else {
			$password = wp_generate_password( 12, false );
			$uid = wp_create_user( $request->username, $password, $request->email );
			$userdata = array( 
				'ID' => $uid,
				'user_url' => $request->url,
				'display_name' => $request->displayname,
			);
			wp_update_user( $userdata );
			add_user_to_blog( $request->blog_id, $uid, 'subscriber' );
			$this->log( 'creating new user '.$request->username.', '.$request->email.': '.print_r( $userdata, TRUE ).'; password: '.$password );
		
			if( $this->o->to_new_user_on_approve['enabled'] ) {
				$user_subject = $this->parse_mail( $this->o->to_new_user_on_approve['subject'], $request->blog_id, $request, $password );
				$user_message = $this->parse_mail( $this->o->to_new_user_on_approve['message'], $request->blog_id, $request, $password );	
			} else {
				$this->log( 'NOT sending an e-mail to newly created user. It\'s password must be delivered manually.', 3 );
			}
		}
	
		// mail uživateli
		$this->log( 'sending mail to user' );
		wp_mail( $request->email, $user_subject, $user_message );	
	
		// mail adminovi
		$admin = get_userdata( $request->approved_by );
		if( $this->is_current_user_email( $admin->user_email ) ) {
			$this->log( 'skipping mail to admin because it\'s current user', 2 );
		} elseif( $this->o->to_admin_on_superadmin_approve['enabled'] ) {
			$this->log( 'sending mail to admin' );
			$admin_subject = $this->parse_mail( $this->o->to_admin_on_superadmin_approve['subject'], $request->blog_id, $request );
			$admin_message = $this->parse_mail( $this->o->to_admin_on_superadmin_approve['message'], $request->blog_id, $request );	
			wp_mail( $admin->user_email, $admin_subject, $admin_message );
		}
	
		// odstranit žádost
		$dok = $this->d->delete_network_request( $rid );
	
		if( $dok ) {
			$this->log( 'rr successfully approved', 2 );
			return array(
				"result" => true,
				"message" => sprintf( __( 'Request no.%d has been approved.' , self::txd ), $rid ) 
			);
		} else {
			$this->log( 'Error while deleting rr from db', 4 );
			$this->extended_log( $this->dberror_description() );
			return array(
				"result" => false,
				"message" => sprintf( __( 'Database error, request no.%d could not be fully processed. Please contact the plugin developer.' , self::txd ), $rid ) 
			);
		}
	}


	function network_deny_request() {
		$rid = $_REQUEST['id'];
	
		$this->log( 'superadmin denying registration request '.$rid );
	
		$request = $this->d->get_network_request( $rid );
	
		if( $request == NULL ) {
			$this->log( 'error - rr '.$rid.' doesnt exist', 4 );
			return array(
				"result" => false,
				"message" => sprintf( __( 'Request no.%d was not found in the database, so it cannot be denied. If you think this is an error, please contact the plugin developer.' , self::txd ), $rid ) 
			);
		}
	
		// mail adminovi
		$admin = get_userdata( $request->approved_by );
		if( $this->is_current_user_email( $admin->user_email ) ) {
			$this->log( 'skipping mail to admin because it\'s the current user.',  2 );
		} elseif( $this->o->to_admin_on_superadmin_deny['enabled'] ) {
			$this->log( 'sending mail to admin' );
			$admin_subject = $this->parse_mail( $this->o->to_admin_on_superadmin_deny['subject'], $request->blog_id, $request );
			$admin_message = $this->parse_mail( $this->o->to_admin_on_superadmin_deny['message'], $request->blog_id, $request );	
			wp_mail( $admin->user_email, $admin_subject, $admin_message );
		}

		// mail žádajícímu
		if( $this->o->to_user_deny_from_superadmin['enabled'] ) {
			$this->log( 'sending mail to user' );
			$user_subject = $this->parse_mail( $this->o->to_user_deny_from_superadmin['subject'], $request->blog_id, $request );
			$user_message = $this->parse_mail( $this->o->to_user_deny_from_superadmin['message'], $request->blog_id, $request );	
			wp_mail( $request->email, $user_subject, $user_message );
		}
	
		$dok = $this->d->delete_network_request( $rid );
	
		if( $dok ) {
			$this->log( 'rr successfully denied', 2 );
			return array(
				"result" => true,
				"message" => sprintf( __( 'Request no.%d has been denied and an information e-mail has been sent to the request author and blog owner.' , self::txd ), $rid ) 
			);
		} else {
			$this->log( 'Error while removing rr from db', 4 );
			$this->extended_log( $this->dberror_description() );
			return array(
				"result" => false,
				"message" => sprintf( __( 'Database error, request no.%d could not be fully processed. Please contact the plugin developer.' , self::txd ), $rid ) 
			);
		}
	}
	
	
	function network_delete_request() {
		$rid = $_REQUEST['id'];
		$this->log( 'superadmin deleting registration request '.$rid );
	
		$dok = $this->d->delete_network_request( $rid );
	
		if( $dok ) {
			$this->log( 'rr successfully deleted', 2 );
			return array(
				"result" => true,
				"message" => sprintf( __( 'Request no.%d has been deleted and no information e-mail has <strong>not</strong> been sent to the request author.' , self::txd ), $rid ) 
			);
		} else {
			$this->log( 'Error while removing rr from db', 4 );
			$this->extended_log( $this->description() );
			return array(
				"result" => false,
				"message" => sprintf( __( 'Database error, request no.%d could not be fully processed. Please contact the plugin developer.' , self::txd ), $rid ) 
			);
		}

	}



}

new Murm;


?>
