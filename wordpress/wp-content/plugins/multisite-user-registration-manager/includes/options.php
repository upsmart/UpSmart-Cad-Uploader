<?php


// legacy definitions

define( 'MURM_OPT_ACTIVE', 'murm_is_active' );
define( 'MURM_OPT_SHOWNAG', 'murm_show_nag' );
define( 'MURM_OPT_INSTALLED', 'murm_installed' );
define( 'MURM_OPT_MAIL_ADMIN_ON_REQUEST', 'murm_mail_admin_on_request' );
define( 'MURM_OPT_ANTISPAM', 'murm_antispam' );
define( 'MURM_OPT_AKISMET_KEY', 'murm_akismet_key' );
define( 'MURM_OPT_AUTOAPPROVE', 'murm_autoapprove' );

define( 'MURM_SITE_SETTINGS', 'murm_site_settings' );


class Murm_Options {

	var $p = NULL; // parent class
	var $blog_cache; // single blog options cache
	var $network_cache; // network options cache

	function __construct( $parent ) {
		$this->p = $parent;
		$this->blog_cache = array();
		$this->network_cache = array();
	}
	
	
	function __get( $key ) {
		if( array_key_exists( $key, $this->network_cache ) ) {
			$val = $this->network_cache[$key];
			//$this->p->extended_log( "Getting option $key from network cache: $val." );
		} else if( array_key_exists( $key, $this->blog_cache ) ) {
			$val = $this->blog_cache[$key];
			//$this->p->extended_log( "Getting option $key from blog cache: $val. Blog cache contains ".print_r( $this->blog_cache, true ) );
		} else if( $this->load_option( $key ) ) {
			$val = $this->blog_cache[$key];
			//$this->p->extended_log( "Loaded option $key from blog options: $val." );
		} else {
			$this->load_network_options();
			$val = $this->network_cache[$key];
			//$this->p->extended_log( "Loaded option $key from network options: $val." );
		}
		return $val;
	}
	
	
	function load_option( $key ) {
		switch( $key ) {
			// legacy options
			case "is_active":
				$val = ( get_option( MURM_OPT_ACTIVE, false ) == true );
				break;
			case "is_installed_on_blog":
				$val = ( get_option( MURM_OPT_INSTALLED, false ) == true );
				break;
			case "show_nag":
				$val = ( get_option( MURM_OPT_SHOWNAG, false ) == true );
				break;
			case "notify_by_mail":
				$val = ( get_option( MURM_OPT_MAIL_ADMIN_ON_REQUEST, false ) == true );
				break;
			case "is_antispam_active":
				$val = ( get_option( MURM_OPT_ANTISPAM, false ) == true );
				break;
			case "akismet_key":
				$val = get_option( MURM_OPT_AKISMET_KEY, "" );	
				break;
			case "blog_autoapprove":
				$val = ( get_option( MURM_OPT_AUTOAPPROVE, false ) == true );
				break;
			default:
				return false;
		}
		$this->blog_cache[$key] = $val;
		//$this->p->ds( "loading $key from database: \"$val\". current cache content: ".print_r( $this->blog_cache, true ) );
		return true;
	}
	
	
	function load_network_options() {
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
		$this->network_cache = wp_parse_args( $settings, $defaults );
	}

	
	
	function __set( $key, $value ) {
		switch( $key ) {
			case "is_active":
				update_option( MURM_OPT_ACTIVE, $value );
				break;
			case "is_installed_on_blog":
				update_option( MURM_OPT_INSTALLED, $value );
				break;
			case "show_nag":
				update_option( MURM_OPT_SHOWNAG, $value );
				break;
			case "notify_by_mail":
				update_option( MURM_OPT_MAIL_ADMIN_ON_REQUEST, $value );
				break;
			case "is_antispam_active":
				update_option( MURM_OPT_ANTISPAM, $value );
				break;
			case "akismet_key":
				update_option( MURM_OPT_AKISMET_KEY, $value );
				break;
			case "blog_autoapprove":
				update_option( MURM_OPT_AUTOAPPROVE, $value );
				break;
			default:
				$this->network_cache[$key] = $value;
				update_site_option( MURM_SITE_SETTINGS, $this->network_cache );
				return;
		}
		$this->blog_cache[$key] = $value;
	}
	
	
	function update_network_options( $options ) {
		update_site_option( MURM_SITE_SETTINGS, $options );
		$this->network_cache = $options;
	}

}

?>
