<?php

/* ************************************************************************* *\
	SHORTCODE
\* ************************************************************************* */

add_shortcode( 'murm-form', 'murm_shortcode_handler' );

function murm_shortcode_handler() {
	if( !murm_is_active ) {
		//TODO
		murm_log( 'shortcode used while murm inactive', 3 );
		return;
	}

	if( isset($_REQUEST['murmaction']) ) {
        $action = $_REQUEST['murmaction'];
    } else {
        $action = 'default';
    }
    
    switch( $action ) {
    case 'commit':
    	return murm_shortcode_handler_commit();
    	break;
    default:
    	return murm_shortcode_handler_default();
    }
}


function murm_shortcode_handler_default() {
	if( is_user_logged_in() ) {
		return murm_shortcode_handler_logged();
	} else {
		return murm_shortcode_handler_new();
	}
}


function murm_shortcode_handler_get_args() {
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



function murm_shortcode_handler_new() {
	
	extract( murm_shortcode_handler_get_args() );
	
	$code = '
	<div id="murm">
		<a name="murm"></a>
		<form method="post" action="'.get_permalink().'#murm">
            <input type="hidden" name="murmaction" value="commit" />
            <table class="form-table">
                <tr>
                	<th><label for="murmdata[username]">'.__( 'Username' , MURM_TEXTDOMAIN ).'</label></th>
                	<td>
                		<input type="text" name="murmdata[username]" value="'.$username.'" />
                	</td>
                </tr>
                <tr valign="top">
                	<td colspan="2"><small>'.__( 'Mandatory entry. Can contain only small letters without diacritics and numbers, must be at least four characters long.' , MURM_TEXTDOMAIN ).'</small></td>
                </tr>
                <tr>
                	<th><label for="murmdata[email]">'.__( 'E-mail address' , MURM_TEXTDOMAIN ).'</label></th>
                	<td>
                		<input type="text" name="murmdata[email]" value="'.$email.'" />
                	</td>
                </tr>
                <tr valign="top">
                	<td colspan="2"><small>'.__( 'Mandatory entry' , MURM_TEXTDOMAIN ).'</small></td>
                </tr>
                <tr>
                	<th><label for="murmdata[displayname]">'.__( 'User name to be displayed publicly' , MURM_TEXTDOMAIN ).'</label></th>
                	<td>
                		<input type="text" name="murmdata[displayname]" value="'.$displayname.'" />
                	</td>
                </tr>
                <tr>
                	<th><label for="murmdata[url]">'.__( 'Your web URL' , MURM_TEXTDOMAIN ).'</label></th>
                	<td>
                		<input type="text" name="murmdata[url]" value="'.$url.'" />
                	</td>
                </tr>
                <tr valign="top">
                	<th><label for="murmdata[message]">'.__( 'Message for the blog owner' , MURM_TEXTDOMAIN ).'</label></th>
                	<td>
                		<textarea name="murmdata[message]" wrap="soft">'.$message.'</textarea>
                	</td>
                </tr>
                <tr valign="top">
                	<td colspan="2"><small>'.__( 'If you and blog owner don\'t know each other, it may help if you write something about yourself. Information about how you found this blog may be also useful.' , MURM_TEXTDOMAIN ).'</small></td>
                </tr>
			</table>
			<p class="submit">
	            <input type="submit" value="'.__( 'Submit' , MURM_TEXTDOMAIN ).'" />    
	        </p> 
		</form>
	</div>';
	return $code;
}


function murm_shortcode_handler_logged() {
	$user = wp_get_current_user();
	extract( murm_shortcode_handler_get_args() );
	$code = '
		<div id="murm">
			<p>'.sprintf( __( 'You are currently logged in as user %s. If you intend to request registration on this blog with this user account, just click the submit button below. In the other case you have to %slog out%s first.' , MURM_TEXTDOMAIN ), '<strong>'.$user->user_login.'</strong>', '<a href="'.wp_logout_url( get_permalink() ).'">', '</a>' ).'<!--
				Momentálně jste přihlášeni jako uživatel <strong>'.$user->user_login.'</strong>. Chcete-li pod tímto jménem požádat o registraci na tomto blogu, stačí kliknout tlačíto pro odeslání žádosti. V opačném případě je nutno se nejdříve <a href="'.wp_logout_url( get_permalink() ).'">odhlásit</a>. -->
			</p>
			<form method="post" action="'.get_permalink().'">
		        <input type="hidden" name="murmaction" value="commit" />
		        <input type="hidden" name="murmdata[username]" value="'.$user->user_login.'" />
		        <input type="hidden" name="murmdata[email]" value="'.$user->user_email.'" />
		        <table class="form-table">
					<tr valign="top">
                	<th><label for="murmdata[message]">'.__( 'Message for the blog owner' , MURM_TEXTDOMAIN ).'</label></th>
                	<td>
                		<textarea name="murmdata[message]" wrap="soft">'.$message.'</textarea>
                	</td>
		            </tr>
		            <tr valign="top">
		            	<td colspan="2"><small>'.__( 'If you and blog owner don\'t know each other, it may help if you write something about yourself. Information about how you found this blog may be also useful.' , MURM_TEXTDOMAIN ).'</small></td>
		            </tr>
                </table>
				<p class="submit">
			        <input type="submit" value="'.__( 'Submit' , MURM_TEXTDOMAIN ).'" />
			    </p> 
			</form>
		</div>';
	return $code;
}


function murm_shortcode_handler_commit() {
	global $wpdb;
	
	extract( murm_get_site_settings() );
	extract( murm_shortcode_handler_get_args() );
	
	$username = strtolower( $username );
	
	if( murm_is_spam( $username, $email, $url, $message ) ) {
		if( $log_spam_registration_requests ) {
			murm_log( "Denied registration request - recognized as spam", 2 );
			murm_log( "Registration request data: \"$username\", \"$displayname\", \"$email\", \"$url\", \"$message\"", 1 );
		}
		return murm_shortcode_error( __( 'Error! Your request has been evaluated as spam by Akismet. If this is a mistake, please contact blog owner or site network administrator.' , MURM_TEXTDOMAIN ) );
	}
	
	murm_log( "Incoming registration request: \"$username\", \"$displayname\", \"$email\", \"$url\", \"$message\"", 2 );

	if( !murm_is_username_correct( $username ) ) {
		murm_log( 'rr denied - incorrect username', 3 );
		return murm_shortcode_error( __( 'Error! User login name must be at least four characters long and can contain only small letters without diacritics or numbers.' , MURM_TEXTDOMAIN ) );
	}
	
	if( !murm_is_email_correct( $email ) ) {
		murm_log( 'rr denied - incorrect email', 3 );
		return murm_shortcode_error( __( 'Error! Your e-mail address seems to be invalid.' , MURM_TEXTDOMAIN ) );
	}
	
	if( !murm_is_valid_combination( $username, $email ) ) {
		murm_log( 'rr denied - invalid combination', 3 );
		return murm_shortcode_error( __( 'Error! The combination of username and e-mail address is not valid (at least one of them has already been used in this site network.' , MURM_TEXTDOMAIN ) );
	}
	
	if( murm_is_duplicate_request( $username, $email ) ) {
		murm_log( 'rr denied - duplicate request', 3 );
		return murm_shortcode_error( __( 'Error! Another request with this username or e-mail address has already been submitted.' , MURM_TEXTDOMAIN ) );
	}
	
	if( $extended_debug_mode ) {
	
	}
	
	
	$iok = $wpdb->insert( $wpdb->prefix.'murm_requests', 
		array(
			'username' => $username,
			'displayname' => $displayname,
			'email' => $email,
			'url' => $url,
			'message' => $message
		) );
	
	$rid = $wpdb->insert_id;
	
	if( !$iok ) {
		murm_log( 'error while commiting rr', 4 );
		murm_extended_log( 'last mysql query: '.$wpdb->last_query );
		murm_extended_log( 'last mysql result: '.$wpdb->last_result );
		murm_extended_log( 'last mysql error: '.$wpdb->last_error );
		return murm_shortcode_error( sprintf( __( 'Database error when submitting your request. Please contact the %splugin developer%s.' , MURM_TEXTDOMAIN ), '<a href="mailto:zaantar@zaantar.eu">', '</a>' ) );
	} 
	
	if( murm_is_mail_admin_on_request() ) {
		murm_log( 'sending information about new request to blog admin' );
		$request = murm_get_admin_request( $rid );
		//echo 'rid:'.$rid.';'.$request->email;
		$subject = murm_parse_mail( $to_admin_new_request['subject'], $blog_id, $request );
		$message = murm_parse_mail( $to_admin_new_request['message'], $blog_id, $request );	
		wp_mail( get_bloginfo( 'admin_email' ) , $subject, $message );
	}
	
	murm_log( 'RR successfully commited to database with id=='.$rid );
	
	if( murm_is_autoapprove() ) {
		murm_log( "Autoapprove on admin level is enabled - approving request." );
		murm_moderation_approve( $rid );
	} else {
		murm_extended_log( "Autoapprove on admin level is disabled." );
	}
	
	return '<p>'.__( 'Your request has been submitted. Now please be patient, we will inform you about any further development via e-mail.' , MURM_TEXTDOMAIN ).'</p>';
}


function murm_is_valid_combination( $username, $email ) {
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


function murm_is_duplicate_request( $username, $email ) {
	global $wpdb, $blog_id;
	
	$blog_query = '
		SELECT COUNT(*)
		FROM '.$wpdb->prefix.'murm_requests
		WHERE (
			username=\''.$username.'\' 
			OR email=\''.$email.'\'
			)';
	
	$blog_duplicates = $wpdb->get_var( $wpdb->prepare( $blog_query ) );
			
	$net_query = '
		SELECT COUNT(*)
		FROM '.$wpdb->base_prefix.'murm_requests
		WHERE (
			username=\''.$username.'\'
			OR email=\''.$email.'\'
			)
			AND blog_id = '.$blog_id;
	
	$net_duplicates = $wpdb->get_var( $wpdb->prepare( $net_query ) );
	
	return ( ( $blog_duplicates != 0 ) or ( $net_duplicates != 0 ) );
}



function murm_is_username_correct( $username ) {
	$lenok = ( strlen( $username ) >= 4 );
	$charsok = preg_match( '/^[a-z0-9]+$/u', $username );
	return $lenok && $charsok && validate_username( $username );
}


function murm_is_email_correct( $email ) {
	return is_email( $email );
}


function murm_is_spam( $username, $email, $url, $content ) {
	extract( murm_get_site_settings() );
	if( murm_is_antispam() ) {
		$key = get_option( MURM_OPT_AKISMET_KEY, '' );
	} else if( $activate_akismet ) {
		$key = $akismet_key;
	} else {
		return false;
	}
	
	$akismet = new MurmAkismet( home_url() ,$key);
	$akismet->setCommentAuthor( $username );
	$akismet->setCommentAuthorEmail( $email );
	$akismet->setCommentAuthorURL( $url );
	$akismet->setCommentContent( $content );
	$akismet->setPermalink( get_permalink() );

	$is_spam = $akismet->isCommentSpam();
	
	if( $is_spam && $ban_spammer && murm_can_ban() ) {
		$ip = suh_get_ip();
		if( $log_spam_registration_requests ) {
			murm_log( "Banning spammer IP \"$ip\"." );
		}
		suh_add_permban( $ip );
	}
	
	return $is_spam;
}

function murm_shortcode_error( $message ) {
	return '<div id="murm"><a name="murm"></a><p class="error">'.$message.'</p></div>'.murm_shortcode_handler_default();
}



add_action( 'wp_head', 'murm_header_content' );

function murm_header_content() {
	?>
	<style type='text/css' media='screen'>
		#murm p.error {
			font-weight: bold;
			color: red;
		}
	</style>
	<?php
}


?>
