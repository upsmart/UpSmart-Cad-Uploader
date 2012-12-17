<?php

/*****************************************************************************\
	SITE OPTIONS
\*****************************************************************************/


add_action( 'network_admin_menu','murm_network_admin_menu_options' );


function murm_network_admin_menu_options() {
	add_submenu_page( 'settings.php', __( 'MURM', MURM_TEXTDOMAIN ), __( 'MURM', MURM_TEXTDOMAIN ),
		'manage_network_options', 'murm-site-settings', 'murm_site_settings_page' );
}


function murm_site_settings_page() {
	
	if( isset($_REQUEST['action']) ) {
        $action = $_REQUEST['action'];
    } else {
        $action = 'default';
    }
    
    switch( $action ) {
    case 'update-settings':
		murm_update_site_settings( $_POST['settings'] );
		//print_r( $_POST['settings'] );
		murm_site_settings_page_default();
    	break;
    case 'wls-register':
    	wls_register( MURM_LOG_NAME, __( 'Multisite User Registration Manager events.', MURM_TEXTDOMAIN ) ); //TODO return value check
    	murm_site_settings_page_default();
    	break;
    case 'wls-unregister':
    	wls_unregister( MURM_LOG_NAME ); //TODO return value check
    	murm_site_settings_page_default();
    	break;
    default:
    	murm_site_settings_page_default();
    }
}


function murm_site_settings_page_default() {

	extract( murm_get_site_settings() );
	
	?>
	<div class="wrap">
		<h2><?php _e( 'Nastavení Multisite User Registration Manager', MURM_TEXTDOMAIN ); ?></h2>
		<?php
			if( !$hide_donation_button ) {
				?>
				<h3><?php _e( 'Please consider a donation', MURM_TEXTDOMAIN ); ?></h3>
				<p>
					<?php _e( 'I spend quite a lot of my precious time working on opensource WordPress plugins. If you find this one useful, please consider helping me develop it further. Even the smallest amount of money you are willing to spend will be welcome.', MURM_TEXTDOMAIN ); ?>
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
            <h3><?php _e( 'Basic settings', MURM_TEXTDOMAIN ); ?></h3>
        	<table class="form-table">
            	<tr valign="top">
                	<th>
                		<label><?php _e( 'Auto-approve requests on Network admin level', MURM_TEXTDOMAIN ); ?></label><br />
                	</th>
                	<td>
                		<input type="checkbox" name="settings[superadmin_autoapprove]" 
                			<?php if( $superadmin_autoapprove ) echo 'checked="checked"'; ?>
                		/>
                	</td>
                	<td>
                		<small><?php _e( 'If checked, only blog admins decide about request processing and network admin doesn\'t have to do anything (is is as if he/she actually immediately approved the request). It is recommended to edit e-mail templates (below) accordingly.' ); ?></small>
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label><?php _e( 'Allow blog owners to delete requests', MURM_TEXTDOMAIN ); ?></label><br />
                	</th>
                	<td>
                		<input type="checkbox" name="settings[admin_can_delete_request]" 
                			<?php if( $admin_can_delete_request ) echo 'checked="checked"'; ?>
                		/>
                	</td>
                	<td>
                		<small><?php _e( 'If checked, they can delete a request without the request author being notified.' ); ?></small>
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label><?php _e( 'Extended debug mode', MURM_TEXTDOMAIN ); ?></label><br />
                	</th>
                	<td>
                		<input type="checkbox" name="settings[extended_debug_mode]" 
                			<?php if( $extended_debug_mode ) echo 'checked="checked"'; ?>
                		/>
                	</td>
                	<td>
                		<small><?php printf( __( 'After checking create a WLS log category named %s. All extra information will be logged here.' ), '<code>'.MURM_EXTENDED_LOG_NAME.'</code>' ); ?></small>
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label><?php _e( 'Allow fallback logging to a file if WLS logging is not possible', MURM_TEXTDOMAIN ); ?></label><br />
                	</th>
                	<td>
                		<input type="checkbox" name="settings[fallback_logging]" 
                			<?php checked( $fallback_logging ); ?>
                		/>
                	</td>
                	<td>
                		<small><?php _e( 'Log file will be created within plugin directory (you need appropriate file permissions for that).', MURM_TXD ); ?></small>
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label><?php _e( 'Hide donation button', MURM_TEXTDOMAIN ); ?></label><br />
                	</th>
                	<td>
                		<input type="checkbox" name="settings[hide_donation_button]" 
                			<?php if( $hide_donation_button ) echo 'checked="checked"'; ?>
                		/>
                	</td>
                </tr>
            </table>
            <h3><?php _e( 'Spam management', MURM_TEXTDOMAIN ); ?></h3>
        	<table class="form-table">
        		<tr valign="top">
                	<th>
                		<label><?php _e( 'Activate Akismet spam filter', MURM_TEXTDOMAIN ); ?></label><br />
                	</th>
                	<td>
                		<input type="checkbox" name="settings[activate_akismet]" <?php checked( $activate_akismet ); ?> />
                	</td>
                	<td>
                		<small><?php _e( 'For Akismet spam filter to work you need to enter a valid Akismet API key.', MURM_TXD ); ?></small>
                	</td>
                </tr>
	            <tr valign="top">
	            	<th>
	            		<label><?php _e( 'Akismet API key' , MURM_TEXTDOMAIN ); ?></label>
	            	</th>
	            	<td>
	            		<input type="text" name="settings[akismet_key]" value="<?php echo esc_attr( $akismet_key ); ?>" />
	            	</td>
	            </tr>
	            <tr valign="top">
                	<th>
                		<label><?php _e( 'Ban spamming IPs with Superadmin Helper', MURM_TEXTDOMAIN ); ?></label><br />
                	</th>
                	<td>
                		<input type="checkbox" name="settings[ban_spammer]" <?php checked( $ban_spammer ); ?> />
                	</td>
                	<td>
                		<small>
                			<?php _e( 'Superadmin Helper plugin with permban activated is neccessary.', MURM_TXD ); ?><br />
                			<?php 
                				printf( 
		            				__("Permban capability is %s", MURM_TXD ), 
		            				murm_can_ban() ? "<strong>".__( "ON", MURM_TXD )."</strong>" : "<strong>".__( "OFF", MURM_TXD )."</strong>" 
                				); 
                			?>
                		</small>
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label><?php _e( 'Log spam registration requests', MURM_TEXTDOMAIN ); ?></label><br />
                	</th>
                	<td>
                		<input type="checkbox" name="settings[log_spam_registration_requests]" <?php checked( $log_spam_registration_requests ); ?> />
                	</td>
                </tr>
        	</table>
            <h3><?php _e( 'E-mail templates' ); ?></h3>
            <table class="form-table">
                <tr valign="top">
                	<th>
                		<label><?php _e( 'For request author on denial by blog owner', MURM_TEXTDOMAIN ); ?></label>
                		<p style="font-size: x-small;"><?php _e( 'Within e-mail templates you can use following keywords:', MURM_TEXTDOMAIN ); ?></p>
                		<ol style="font-size: x-small;">
		            		<li><code>%SITE_NAME%</code>: <?php _e( 'Name of the blog network', MURM_TEXTDOMAIN ); ?></li>
							<li><code>%SITE_URL%</code>: <?php _e( 'Network base URL (usually same as network main blog URL)', MURM_TEXTDOMAIN ); ?></li>
							<li><code>%SUPERADMIN_EMAIL%</code>: <?php _e( 'E-mail address of the network administrator', MURM_TEXTDOMAIN ); ?></li>
							<li><code>%BLOG_NAME%</code>: <?php _e( 'Name of current blog', MURM_TEXTDOMAIN ); ?></li>
							<li><code>%BLOG_URL%</code>: <?php _e( 'URL of current blog', MURM_TEXTDOMAIN ); ?></li>
						</ol>
						<p style="font-size: x-small;">...</p>
                	</th>
                	<td>
                		<input type="checkbox" name="settings[to_user_deny_from_admin][enabled]" <?php if( $to_user_deny_from_admin['enabled'] ) echo 'checked="checked"'; ?> />&nbsp;<label><?php _e( 'Enabled', MURM_TEXTDOMAIN ); ?></label><br />
                		<label><?php _e( 'Subject:', MURM_TEXTDOMAIN ); ?></label>&nbsp;<input type="text" name="settings[to_user_deny_from_admin][subject]" value="<?php echo $to_user_deny_from_admin['subject']; ?>" size="60" /><br />
                		<textarea name="settings[to_user_deny_from_admin][message]" cols="80" rows="15"><?php 
            				echo stripslashes( esc_textarea( $to_user_deny_from_admin['message'] ) ); 
            			?></textarea>
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label><?php _e( 'For blog owner on incoming request', MURM_TEXTDOMAIN ); ?></label><br />
                		<ol start="6" style="font-size: x-small;">
		            		<li><code>%ADMIN_EMAIL%</code>: <?php _e( 'E-mail address of current blog administrator', MURM_TEXTDOMAIN ); ?></li>
							<li><code>%LOGIN_URL%</code>: <?php _e( 'Login page URL on current blog', MURM_TEXTDOMAIN ); ?></li>
							<li><code>%USER_NAME%</code>: <?php _e( 'User\'s name (only when logged in)', MURM_TEXTDOMAIN ); ?></li>
							<li><code>%USER_EMAIL%</code>: <?php _e( 'User\'s e-mail address (only when logged in)', MURM_TEXTDOMAIN ); ?></li>
							<li><code>%PASSWORD%</code>: <?php _e( 'New user\'s password (only when creating a new user account)', MURM_TEXTDOMAIN ); ?></li>
						</ol>
                	</th>
                	<td>
                		<input type="checkbox" name="settings[to_admin_new_request][enabled]" <?php if( $to_admin_new_request['enabled'] ) echo 'checked="checked"'; ?> /><label>&nbsp;<?php _e( 'Enabled', MURM_TEXTDOMAIN ); ?></label><br />
                		<label><?php _e( 'Subject:', MURM_TEXTDOMAIN ); ?></label>&nbsp;<input type="text" name="settings[to_admin_new_request][subject]" value="<?php echo $to_admin_new_request['subject']; ?>" size="60" /><br />
                		<textarea name="settings[to_admin_new_request][message]" cols="80" rows="15"><?php 
            				echo stripslashes( esc_textarea( $to_admin_new_request['message'] ) ); 
            			?></textarea>
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label><?php _e( 'For superadmin on incoming request', MURM_TEXTDOMAIN ); ?></label>
                	</th>
                	<td>
                		<input type="checkbox" name="settings[to_superadmin_new_request][enabled]" <?php if( $to_superadmin_new_request['enabled'] ) echo 'checked="checked"'; ?> />&nbsp;<label><?php _e( 'Enabled', MURM_TEXTDOMAIN ); ?></label><br />
                		<label><?php _e( 'Subject:', MURM_TEXTDOMAIN ); ?></label>&nbsp;<input type="text" name="settings[to_superadmin_new_request][subject]" value="<?php echo $to_superadmin_new_request['subject']; ?>" size="60" /><br />
                		<textarea name="settings[to_superadmin_new_request][message]" cols="80" rows="15"><?php 
            				echo stripslashes( esc_textarea( $to_superadmin_new_request['message'] ) ); 
            			?></textarea>
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label><?php _e( 'For request author on denial by superadmin', MURM_TEXTDOMAIN ); ?></label>
                	</th>
                	<td>
                		<input type="checkbox" name="settings[to_user_deny_from_superadmin][enabled]" <?php if( $to_user_deny_from_superadmin['enabled'] ) echo 'checked="checked"'; ?> />&nbsp;<label><?php _e( 'Enabled', MURM_TEXTDOMAIN ); ?></label><br />
                		<label><?php _e( 'Subject:', MURM_TEXTDOMAIN ); ?></label>&nbsp;<input type="text" name="settings[to_user_deny_from_superadmin][subject]" value="<?php echo $to_user_deny_from_superadmin['subject']; ?>" size="60"  /><br />
                		<textarea name="settings[to_user_deny_from_superadmin][message]" cols="80" rows="15"><?php 
            				echo stripslashes( esc_textarea( $to_user_deny_from_superadmin['message'] ) ); 
            			?></textarea>
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label><?php _e( 'For blog owner on denial by superadmin', MURM_TEXTDOMAIN ); ?></label>
                	</th>
                	<td>
                		<input type="checkbox" name="settings[to_admin_on_superadmin_deny][enabled]" <?php if( $to_admin_on_superadmin_deny['enabled'] ) echo 'checked="checked"'; ?> />&nbsp;<label><?php _e( 'Enabled', MURM_TEXTDOMAIN ); ?></label><br />
                		<label><?php _e( 'Subject:', MURM_TEXTDOMAIN ); ?></label>&nbsp;<input type="text" name="settings[to_admin_on_superadmin_deny][subject]" value="<?php echo $to_admin_on_superadmin_deny['subject']; ?>" size="60"  /><br />
                		<textarea name="settings[to_admin_on_superadmin_deny][message]" cols="80" rows="15"><?php 
            				echo stripslashes( esc_textarea( $to_admin_on_superadmin_deny['message'] ) ); 
            			?></textarea>
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label><?php _e( 'For blog owner on approval by superadmin', MURM_TEXTDOMAIN ); ?></label>
                	</th>
                	<td>
                		<input type="checkbox" name="settings[to_admin_on_superadmin_approve][enabled]" <?php if( $to_admin_on_superadmin_approve['enabled'] ) echo 'checked="checked"'; ?> />&nbsp;<label><?php _e( 'Enabled', MURM_TEXTDOMAIN ); ?></label><br />
                		<label><?php _e( 'Subject:', MURM_TEXTDOMAIN ); ?></label>&nbsp;<input type="text" name="settings[to_admin_on_superadmin_approve][subject]" value="<?php echo $to_admin_on_superadmin_approve['subject']; ?>" size="60"  /><br />
                		<textarea name="settings[to_admin_on_superadmin_approve][message]" cols="80" rows="15"><?php 
            				echo stripslashes( esc_textarea( $to_admin_on_superadmin_approve['message'] ) ); 
            			?></textarea>
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label><?php _e( 'For request author on approval (when creating new user account)', MURM_TEXTDOMAIN ); ?></label>
                	</th>
                	<td>
                		<input type="checkbox" name="settings[to_new_user_on_approve][enabled]" <?php if( $to_new_user_on_approve['enabled'] ) echo 'checked="checked"'; ?> />&nbsp;<label><?php _e( 'Enabled', MURM_TEXTDOMAIN ); ?></label>&nbsp;<small><?php _e( '(why would you ever uncheck that?)', MURM_TEXTDOMAIN ); ?></small><br />
                		<label><?php _e( 'Subject:', MURM_TEXTDOMAIN ); ?></label>&nbsp;<input type="text" name="settings[to_new_user_on_approve][subject]" value="<?php echo $to_new_user_on_approve['subject']; ?>" size="60" /><br />
                		<textarea name="settings[to_new_user_on_approve][message]" cols="80" rows="15"><?php 
            				echo stripslashes( esc_textarea( $to_new_user_on_approve['message'] ) ); 
            			?></textarea>
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label><?php _e( 'For request author on approval (when registering an existing user account)', MURM_TEXTDOMAIN ); ?></label>
                	</th>
                	<td>
                		<input type="checkbox" name="settings[to_existing_user_on_approve][enabled]" <?php if( $to_existing_user_on_approve['enabled'] ) echo 'checked="checked"'; ?> />&nbsp;<label><?php _e( 'Enabled', MURM_TEXTDOMAIN ); ?></label><br />
                		<label><?php _e( 'Subject:', MURM_TEXTDOMAIN ); ?></label>&nbsp;<input type="text" name="settings[to_existing_user_on_approve][subject]" value="<?php echo $to_existing_user_on_approve['subject']; ?>" size="60" /><br />
                		<textarea name="settings[to_existing_user_on_approve][message]" cols="80" rows="15"><?php 
            				echo stripslashes( esc_textarea( $to_existing_user_on_approve['message'] ) ); 
            			?></textarea>
                	</td>
                </tr>
			</table>
			<p class="submit">
	            <input type="submit" class="button-primary" value="<?php _e( 'Save', MURM_TEXTDOMAIN ); ?>" />    
	        </p>
		</form>
		<?php 
        	if( defined( 'WLS' ) && is_super_admin() ) {
        		?>
        		<h3><?php _e( 'Registration with WLS' , MURM_TEXTDOMAIN ); ?></h3>
        		<p><?php _e( 'WordPress Logging Service has been detected.' , MURM_TEXTDOMAIN ); ?></p>
        		<?php
        			if( !wls_is_registered( MURM_LOG_NAME ) ) {
        				?>
						<form method="post">
							<input type="hidden" name="action" value="wls-register" />
							<p class="submit">
								<input type="submit" value="<?php _e( 'Register MURM with WLS' , MURM_TEXTDOMAIN ); ?>" />    
							</p> 
						</form>
						<?php
					} else {
						?>
						<form method="post">
							<input type="hidden" name="action" value="wls-unregister" />
							<p class="submit">
								<input type="submit" style="color:red;" value="<?php _e( 'Unregister MURM and delete all log entries' , MURM_TEXTDOMAIN ); ?>" />    
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


define( 'MURM_SITE_SETTINGS', 'murm_site_settings' );


function murm_update_site_settings( $settings ) {
	$settings["activate_akismet"] = isset( $_REQUEST["settings"]["activate_akismet"] );
	$settings["ban_spammer"] = isset( $_REQUEST["settings"]["ban_spammer"] );
	$settings["log_spam_registration_requests"] = isset( $_REQUEST["settings"]["log_spam_registration_requests"] );
	update_site_option( MURM_SITE_SETTINGS, $settings );
}


function murm_get_site_settings() {
	$defaults = array(
		'superadmin_autoapprove' => false,
		'admin_can_delete_request' => false, 
		'extended_debug_mode' => false,
		'hide_donation_button' => false,
		"fallback_logging" => true,
		"activate_akismet" => true,
		"akismet_key" => "",
		"ban_spam" => false,
		"log_spam_registration_requests" => true,
		'to_user_deny_from_admin' => array(
			'enabled' => true,
			'subject' => '[%BLOG_NAME%] Registration request denial',
			'message' => "We are sorry, but your request for registration on blog %BLOG_NAME% with user name %USER_NAME% was denied by this blog's owner. For more information you can contact them via %ADMIN_EMAIL%.\n\n--\n%SITE_NAME%\nMultisite User Registration Manager"
		),
		'to_admin_new_request' => array(
			'enabled' => true,
			'subject' => '[%BLOG_NAME%] New registration request',
			'message' => "There is a new registration request from %USER_NAME% (%USER_EMAIL%) on your blog. For processing it please go to %BLOG_URL%/wp-admin/users.php?page=murm-moderation.\n\n--\n%SITE_NAME%\nMultisite User Registration Manager"
		),
		'to_superadmin_new_request' => array(
			'enabled' => true,
			'subject' => '[%BLOG_NAME%] New registration request',
			'message' => "The admin of %BLOG_NAME% requests registration of user %USER_NAME% (%USER_EMAIL%) on it's blog. For processing it please go to %SITE_URL%/wp-admin/network/users.php?page=murm-superadmin.\n\n--\n%SITE_NAME%\nMultisite User Registration Manager"
		),
		'to_user_deny_from_superadmin' => array(
			'enabled' => true,
			'subject' => '[%BLOG_NAME%] Registration request denial',
			'message' => "We are sorry, but your request for registration on blog %BLOG_NAME% with user name %USER_NAME% was denied by network administrator. For more information you can contact them via %SUPERADMIN_EMAIL%.\n\n--\n%SITE_NAME%\nMultisite User Registration Manager"
		),
		'to_admin_on_superadmin_deny' => array(
			'enabled' => true,
			'subject' => '[%BLOG_NAME%] Registration request from user %USER_NAME% denied',
			'message' => "We are sorry, but your registration request for user %USER_NAME% on your blog %BLOG_NAME% was denied by network administrator. The requesting user has been notified via e-mail. For more information you can the network admin via %SUPERADMIN_EMAIL%.\n\n--\n%SITE_NAME%\nMultisite User Registration Manager"
		),
		'to_admin_on_superadmin_approve' => array(
			'enabled' => true,
			'subject' => '[%BLOG_NAME%] Registration request from user %USER_NAME% approved',
			'message' => "Network administrator has approved your registration request for user %USER_NAME% on your blog %BLOG_NAME%. The requesting user has been notified via e-mail. In case of any problems please contact the network admin via %SUPERADMIN_EMAIL%.\n\n--\n%SITE_NAME%\nMultisite User Registration Manager"
		),
		'to_new_user_on_approve' => array(
			'enabled' => true,
			'subject' => '[%BLOG_NAME%] New user account: %USER_NAME%',
			'message' => "Your registration on the blog %BLOG_NAME% has been approved. Below you can find your login data.\n\nImportant information\n\n(1) This data is assigned to you and to you only. Do no share it with anyone!\n(2) If you request registration on another blog on site %SITE_NAME% in the future, use exactly the same combination of user name and e-mail (%USER_NAME%, %USER_EMAIL%).\n\nIn case of any problems or questions please contact the network administrator via  %SUPERADMIN_EMAIL%.\n\nUser name: %USER_NAME%\nE-mail: %USER_EMAIL%\nPassword: %PASSWORD%\n\nYou can log in here: %LOGIN_URL%.\n\nWelcome!\n\n--\n%SITE_NAME%\nMultisite User Registration Manager"
		),
		'to_existing_user_on_approve' => array(
			'enabled' => true,
			'subject' => '[%BLOG_NAME%] Registration of user account %USER_NAME% approved',
			'message' => "Your registration on the blog %BLOG_NAME% has been approved. For logging in use data sent to you earlier during creation of this user account.\n\nImportant information: If you request registration on another blog on site %SITE_NAME% in the future, use exactly the same combination of user name and e-mail (%USER_NAME%, %USER_EMAIL%).\n\nIn case of any problems or questions please contact the network administrator via  %SUPERADMIN_EMAIL%.\n\nYou can log in here: %LOGIN_URL%.\n\nWelcome!\n\n--\n%SITE_NAME%\nMultisite User Registration Manager"
		)
	);
	
	
	$settings = get_site_option( MURM_SITE_SETTINGS, array(), false );
	return wp_parse_args( $settings, $defaults );
}


function murm_can_ban() {
	return function_exists( "suh_is_permban_active" ) && function_exists( "suh_add_permban" ) && function_exists( "suh_get_ip" ) && suh_is_permban_active();
}

?>
