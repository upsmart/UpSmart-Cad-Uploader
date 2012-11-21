<?php

/* ************************************************************************* *\
	OPTIONS
\* ************************************************************************* */

// TODO custom shortcode css (error/ok) + information
// TODO save all settings as a single wp option

define( 'MURM_OPT_ACTIVE', 'murm_is_active' );
define( 'MURM_OPT_SHOWNAG', 'murm_show_nag' );
define( 'MURM_OPT_INSTALLED', 'murm_installed' );
define( 'MURM_OPT_MAIL_ADMIN_ON_REQUEST', 'murm_mail_admin_on_request' );
define( 'MURM_OPT_ANTISPAM', 'murm_antispam' );
define( 'MURM_OPT_AKISMET_KEY', 'murm_akismet_key' );
define( 'MURM_OPT_AUTOAPPROVE', 'murm_autoapprove' );


function murm_options_page() {
	
	if( isset($_REQUEST['action']) ) {
        $action = $_REQUEST['action'];
    } else {
        $action = 'default';
    }
    
    switch( $action ) {
    case 'uninstall':
    	murm_options_page_uninstall();
    	murm_options_page_default();
    	break;
    case 'update':
    	murm_options_page_update();
    	murm_options_page_default();
    	break;
    default:
    	murm_options_page_default();
    	break;
    }
}


function murm_options_page_default() {
	?>
	<div class="wrap">
        <h2><?php _e( 'Multisite User Registration Manager Settings' , MURM_TEXTDOMAIN ); ?></h2>
		<p><?php printf( __( 'Here you can activate the system for registration request processing on your blog. After that, insert a shortcode %s into your page or post in order to display a registration form. If your blog is closed for example with Members Only plugin, it is suitable to place this shortcode on a \'welcome\' page for non-registered visitors.' , MURM_TEXTDOMAIN ), '<code>[murm-form]</code>' ); ?></p>
		<p>
			<?php printf( __( 'In case of any questions, problems or feature requests kindly contact the %splugin developer%s.' , MURM_TEXTDOMAIN ), '<a href="mailto:zaantar@zaantar.eu?subject=[murm]">', '</a>' ); ?></p>
		<form method="post">
            <input type="hidden" name="action" value="update" />
            <table class="form-table">
                <tr valign="top">
                	<th><label for="murm_active"><?php _e( 'Activate on this blog' , MURM_TEXTDOMAIN ); ?></label></th>
                	<td>
                		<input type="checkbox" name="murm_active" <?php if( murm_is_active() ) { echo( 'checked="checked"' ); } ?> />
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label><?php _e( 'Auto-approve requests', MURM_TEXTDOMAIN ); ?></label><br />
                	</th>
                	<td>
                		<input type="checkbox" name="murm_autoapprove" <?php checked( murm_is_autoapprove() ); ?> />
                	</td>
                	<td>
                		<small><?php _e( 'If checked, only network admins decide about request processing and you don\'t have to do anything (is is as if you actually immediately approved the request).' ); ?></small>
                	</td>
                </tr>
              	<tr valign="top">
                	<th><label for="murm_shownag"><?php _e( 'Show information nag if there are requests waiting for your moderation' , MURM_TEXTDOMAIN ); ?></label></th>
                	<td>
                		<input type="checkbox" name="murm_shownag" <?php if( murm_is_nag() ) { echo( 'checked="checked"' ); } ?> />
                	</td>
                </tr>
                <tr valign="top">
                	<th><label for="murm_mail_admin_on_request"><?php _e( 'Send me an e-mail on incoming registration request' , MURM_TEXTDOMAIN ); ?></label></th>
                	<td>
                		<input type="checkbox" name="murm_mail_admin_on_request" <?php if( murm_is_mail_admin_on_request() ) { echo( 'checked="checked"' ); } ?> />
                	</td>
                </tr>
                <tr valign="top">
                	<th><label for="murm_antispam"><?php _e( 'Antispam' , MURM_TEXTDOMAIN ); ?></label></th>
                	<td>
                		<input type="checkbox" name="murm_antispam" <?php if( murm_is_antispam() ) { echo( 'checked="checked"' ); } ?> />
                	</td>
                </tr>
                <tr valign="top">
                	<th><label for="murm_akismet_key"><?php _e( 'Akismet API key for antispam functionality' , MURM_TEXTDOMAIN ); ?></label></th>
                	<td>
                		<input type="text" name="murm_akismet_key" value="<?php echo get_option( MURM_OPT_AKISMET_KEY, '' ); ?>" />
                	</td>
                </tr>
			</table>
			<p class="submit">
	            <input type="submit" class="button-primary" value="<?php _e( 'Save' , MURM_TEXTDOMAIN ); ?>" />    
	        </p>        
        </form>
        <h3><?php _e( 'Removal from this blog' , MURM_TEXTDOMAIN ); ?></h3>
        <p><?php _e( 'Clicking the button below will deactivate MURM and delete all requests waiting for moderation. It doesn\'t affect requests already passed on to superadmin in any way.' , MURM_TEXTDOMAIN ); ?></p>
        <form method="post">
        	<input type="hidden" name="action" value="uninstall" />
        	<p class="submit">
	            <input type="submit" value="<?php _e( 'Remove MURM from this blog' , MURM_TEXTDOMAIN ); ?>" />    
	        </p> 
        </form>
	</div>
	<?php
}


function murm_options_page_uninstall() {

	murm_log( 'Uninstalling MURM from this blog.', 2 );
	
	$dbok = murm_drop_blog_table();
	
	if( !$dbok ) {
		murm_nagerr( __( 'Error while removing MURM from your blog.' , MURM_TEXTDOMAIN ) );
		murm_log( 'ERROR while uninstalling MURM', 4 );
		return;
	}
	
	update_option( MURM_OPT_INSTALLED, false );
	update_option( MURM_OPT_ACTIVE, false );
	
	murm_nag( __( 'MURM has been successfully removed from your blog.' , MURM_TEXTDOMAIN ) );
	murm_log( 'Successfully uninstalled.' );
}


function murm_options_page_update() {

	murm_log( 'murm_options_page_update() begin' );
	
	$active = isset( $_POST['murm_active'] );
	$shownag = isset( $_POST['murm_shownag'] );
	$mail_admin_on_request = isset( $_POST['murm_mail_admin_on_request'] );
	$antispam = isset( $_POST['murm_antispam'] );
	$akismet_key = $_POST['murm_akismet_key'];
	
	update_option( MURM_OPT_ACTIVE, $active );
	update_option( MURM_OPT_SHOWNAG, $shownag );
	update_option( MURM_OPT_MAIL_ADMIN_ON_REQUEST, $mail_admin_on_request );
	update_option( MURM_OPT_ANTISPAM, $antispam );
	update_option( MURM_OPT_AKISMET_KEY, $akismet_key );
	update_option( MURM_OPT_AUTOAPPROVE, isset( $_POST["murm_autoapprove"] ) );

	if( $active && !murm_is_installed() ) {
		murm_install_on_blog();
	}

	murm_log( 'murm_options_page_update() end' );
	
	murm_nag( __( 'Settings saved.' , MURM_TEXTDOMAIN ) );
}



function murm_is_active() {
	if( get_option( MURM_OPT_ACTIVE, false ) ) {
		return true;
	} else {
		return false;
	}
}


function murm_is_nag() {
	if( get_option( MURM_OPT_SHOWNAG, false ) ) {
		return true;
	} else {
		return false;
	}
}


function murm_is_installed() {
	if( get_option( MURM_OPT_INSTALLED, false ) ) {
		return true;
	} else {
		return false;
	}
}


function murm_is_mail_admin_on_request() {
	if( get_option( MURM_OPT_MAIL_ADMIN_ON_REQUEST, false ) ) {
		return true;
	} else {
		return false;
	}
}


function murm_is_antispam() {
	if( get_option( MURM_OPT_ANTISPAM, false ) ) {
		return true;
	} else {
		return false;
	}
}


function murm_is_autoapprove() {
	if( get_option( MURM_OPT_AUTOAPPROVE, false ) ) {
		return true;
	} else {
		return false;
	}
}


function murm_install_on_blog() {
	murm_log( 'installing MURM on this blog' );
	
	$dbok = murm_create_tables();
	
	if( $dbok ) {
		update_option( MURM_OPT_INSTALLED, true );
		murm_nag( __( 'MURM has been successfully installed on your blog.' , MURM_TEXTDOMAIN ) );
		murm_log( 'installed successfully', 2 );
	} else {
		murm_nagerr( __( 'Error while installing MURM on your blog.' , MURM_TEXTDOMAIN ) );
		murm_log( 'error while installing', 4 );
	}
}



?>
