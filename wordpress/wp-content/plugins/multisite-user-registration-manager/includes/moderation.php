<?php

/* ************************************************************************* *\
	MODERATION
\* ************************************************************************* */


function murm_moderation_page() {
	if( !murm_is_active() ) {
		return;
	}
	
	if( isset($_REQUEST['action']) ) {
        $action = $_REQUEST['action'];
    } else {
        $action = 'default';
    }
    
    extract( murm_get_site_settings() );

	switch( $action ) {
	case 'approve':
		$ret = murm_moderation_approve( $_REQUEST['id'] );
		murm_moderation_page_default( $ret );
		break;
	case 'deny':
		$ret = murm_moderation_deny( $_REQUEST['id'] );
		murm_moderation_page_default( $ret );
		break;
	case 'confirm-delete':
		if( $admin_can_delete_request or is_super_admin() ) {
			murm_moderation_page_confirm_delete();
		} else {
			murm_moderation_page_default();
		}
		break;
	case 'delete':
		$ret = array();
		if( $admin_can_delete_request or is_super_admin() ) {
			$ret = murm_moderation_delete( $_REQUEST['id'] );
		}
		murm_moderation_page_default( $ret );
		break;
	default:
		murm_moderation_page_default();
	}
}


function murm_moderation_page_default( $ret = array() ) {

	if( !empty( $ret ) ) {
		if( $ret["result"] ) {
			murm_nag( $ret["message"] );
		} else {
			murm_nagerr( $ret["message"] );
		}
	}

	global $wpdb;	
	$query = '
		SELECT id, username, email, displayname, url, message
		FROM '.$wpdb->prefix.'murm_requests';
	$requests = $wpdb->get_results( $wpdb->prepare( $query ) );
	
	extract( murm_get_site_settings() );
	
	?>
	<div class="wrap">
		<h2><?php _e( 'Registration requests on this blog' , MURM_TEXTDOMAIN ); ?><!--Žádosti o registrace na tomto blogu --></h2>
		<table class="widefat" cellspacing="0">
		    <thead>
		        <tr>
		            <th scope="col" class="manage-column"><?php _e( 'id' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'User name' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'E-mail' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Displayed name' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Web page' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Message for blog owner' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Action' , MURM_TEXTDOMAIN ); ?></th>
				</tr>
			</thead>
			<tfoot>
		        <tr>
		            <th scope="col" class="manage-column"><?php _e( 'id' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'User name' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'E-mail' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Displayed name' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Web page' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Message for blog owner' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Action' , MURM_TEXTDOMAIN ); ?></th>
				</tr>
			</tfoot>
			<?php
				foreach( $requests as $request ) {
					?>
					<tr>
						<td><code><small><?php echo $request->id; ?></small></code></td>
						<td><?php echo $request->username; ?></td>
						<td><a href="mailto:<?php echo $request->email; ?>?subject=[<?php bloginfo( 'name '); ?>] <?php _e( 'Registration request', MURM_TEXTDOMAIN ); ?>"><?php echo $request->email; ?></a></td>
						<td><?php echo $request->displayname; ?></td>
						
						<td><a href="<?php echo murm_fix_blog_url( $request->url ); ?>"><?php echo $request->url; ?></a></td>
						<td><?php echo $request->message; ?></td>
						<td>
							<a href="users.php?page=murm-moderation&action=approve&id=<?php echo $request->id; ?>" style="color: green;"><?php _e( 'Approve' , MURM_TEXTDOMAIN ); ?></a><br />
							<a href="users.php?page=murm-moderation&action=deny&id=<?php echo $request->id; ?>" style="color: red;"><?php _e( 'Deny' , MURM_TEXTDOMAIN ); ?></a><br />
							<?php
								if( $admin_can_delete_request or is_super_admin() ) {
									?>
									<a href="users.php?page=murm-moderation&action=confirm-delete&id=<?php echo $request->id; ?>" 
										style="color: grey;"
									>
										<?php _e( 'Delete' , MURM_TEXTDOMAIN ); ?>
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


function murm_fix_blog_url( $blog ) {
	if( substr( $blog, 0, 7 ) != 'http://' ) {
		return 'http://'.$blog;
	} else {
		return $blog;
	}
}


function murm_moderation_approve( $rid ) {
	global $wpdb, $blog_id;
	
	murm_log( 'admin approving registration request '.$rid );
	
	$request = murm_get_admin_request( $rid );
	
	if( $request == NULL ) {
		murm_log( 'error - rr '.$rid.' doesnt exist', 4 );
		return array( 
			"result" => false,
			"message" => sprintf( __( 'Request no.%d was not found in the database, so it cannot be approved. If you think this is an error, please contact the plugin developer.' , MURM_TEXTDOMAIN ), $rid )
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
	
	$iok = $wpdb->insert( $wpdb->base_prefix.'murm_requests', $insert_data );
	$sup_req_id = $wpdb->insert_id;
	
	if( !$iok ) {
		murm_log( "Error while inserting to superadmin moderation table: ".print_r( $insert_data, TRUE ), 4 );
		murm_extended_log( murm_dberror_description() );
		return array(
			"result" => false,
			"message" => sprintf( __( 'Database error, request no.%d could not be processed. Please contact the plugin developer.' , MURM_TEXTDOMAIN ), $rid )
		);
	}
	murm_log( sprintf( 'Request no.%d forwarded into network admin level with id==%d.', $rid, $sup_req_id ), 1 );
	
	extract( murm_get_site_settings() );

	$superadmin_email = get_site_option( 'admin_email' );
	$superadmin_is_current_user = false;
	if( murm_is_current_user_email( $superadmin_email ) or is_super_admin() ) {
		murm_log( 'skipping mail to superadmin, because it\'s the current user', 2 );
		$superadmin_is_current_user = true;
	} else {
		if( $to_superadmin_new_request['enabled'] ) {
			murm_log( 'sending mail to superadmin' );
			$subject = murm_parse_mail( $to_superadmin_new_request['subject'], $blog_id, $request );
			$message = murm_parse_mail( $to_superadmin_new_request['message'], $blog_id, $request );
			wp_mail( $superadmin_email, $subject, $message );
		}
	}
	
	$dok = murm_delete_admin_request( $rid );
	
	$ret = array( "result" => true, "message" => "" );
	
	if( $dok ) {
		murm_log( 'rr approved by admin', 2 );
		if( $superadmin_autoapprove ) {
			murm_log( '$superadmin_autoapprove==true', 1 );
			$ret = murm_superadmin_approve( $sup_req_id );
		} else if ( $superadmin_is_current_user ) {
			$ret["message"] = sprintf( __( 'Request no.%d has been approved on the single blog level. Now please continue with the approval process as the network administrator via %sthis link%s.' , MURM_TEXTDOMAIN ), $rid, '<a href="/wp-admin/network/users.php?page=murm-superadmin&action=approve&id='.$sup_req_id.'">', '</a>' );
		} else {
			$ret["message"] = sprintf( __( 'Request no.%d has been approved and forwarded to superadmin for moderation. Now please be patient, we will inform you about further development via e-mail.' , MURM_TEXTDOMAIN ), $rid );
		}
		
	} else {
		$ret["message"] = sprintf( __( 'Database error, request no.%d could not be fully processed. Please contact the plugin developer.' , MURM_TEXTDOMAIN ), $rid );
		$ret["result"] = false;
		murm_log( 'Error while removing rr from db', 4 );
		murm_extended_log( murm_dberror_description() );
	}
	return $ret;
}


function murm_moderation_deny( $rid ) {
	
	murm_log( 'admin denying registration request '.$rid );
	
	$request = murm_get_admin_request( $rid );
	
	if( $request == NULL ) {
		murm_log( 'error - rr '.$rid.' doesnt exist', 4 );
		return array(
			"result" => false,
			"message" => sprintf( __( 'Request no.%d was not found in the database, so it cannot be denied. If you think this is an error, please contact the plugin developer.' , MURM_TEXTDOMAIN ), $rid )
		);
	}
	
	extract( murm_get_site_settings() );
	
	if( $to_user_deny_from_admin['enabled'] ) {
		murm_log( 'sending mail to requesting user' );
		$subject = murm_parse_mail( $to_user_deny_from_admin['subject'], $blog_id, $request );
		$message = murm_parse_mail( $to_user_deny_from_admin['message'], $blog_id, $request );	
		wp_mail( $request->email, $subject, $message );
	}
	
	$dok = murm_delete_admin_request( $rid );
	
	if( $dok ) {
		murm_log( 'rr successfully denied', 2 );
		return array(
			"result" => true,
			"message" => sprintf( __( 'Request no.%d has been denied and an information e-mail has been sent to the request author.' , MURM_TEXTDOMAIN ), $rid )
		);
	} else {
		murm_log( 'Error while removing rr from db', 4 );
		murm_extended_log( murm_dberror_description() );
		return array(
			"result" => false,
			"message" => sprintf( __( 'Database error, request no.%d could not be fully processed. Please contact the plugin developer.' , MURM_TEXTDOMAIN ), $rid )
		);		
	}
}


function murm_moderation_page_confirm_delete() {
	$rid = $_REQUEST['id'];
	$request = murm_get_admin_request( $rid );
	?>
	<div class="wrap">
		<h2><?php _e( 'Registration requests on this blog' , MURM_TEXTDOMAIN ); ?></h2>
		<p><?php printf( __( 'You are about to delete reqistration request no.%d (user %s with e-mail %s). Do you really want to proceed?' , MURM_TEXTDOMAIN ), $rid, '<em>'.$request->username.'</em>', '<em>'.$request->email.'</em>' ); ?></p>
		<p><?php _e( 'When deleting a request, the requesting person is not informed about it (on the contrary to denying the request). You should use this function only to delete spam or otherwise processed requests.' , MURM_TEXTDOMAIN ); ?></p>
		<form method="post" action="users.php?page=murm-moderation">
			<input type="hidden" name="action" value="delete" />
			<input type="hidden" name="id" value="<?php echo $rid; ?>" />
			<p class="submit">
	            <input type="submit" value="<?php _e( 'Delete request' , MURM_TEXTDOMAIN ); ?>" />    
	        </p> 
		</form>
		<p>
			&laquo; <a href="users.php?page=murm-moderation"><?php _e( 'Back to request overview' , MURM_TEXTDOMAIN ); ?></a> &laquo;
		</p>
	</div>
	<?php
}


function murm_moderation_delete( $rid ) {
	murm_log( 'admin deleting registration request '.$rid );

	$dok = murm_delete_admin_request( $rid );
	
	if( $dok ) {
		murm_log( 'rr '.$rid.' successfully deleted', 2 );
		return array(
			"result" => true,
			"message" => sprintf( __( 'Request no.%d has been deleted and an information e-mail has <strong>not</strong> been sent to the request author.' , MURM_TEXTDOMAIN ), $rid )
		);
	} else {
		murm_log( 'Error while deleting rr '.$rid.' from db', 4 );
		murm_extended_log( murm_dberror_description() );
		return array(
			"result" => false,
			"message" => sprintf( __( 'Database error, request no.%d could not be fully processed. Please contact the plugin developer.' , MURM_TEXTDOMAIN ), $rid )
		);
	}

}


/* ************************************************************************* *\
	SUPERADMIN
\* ************************************************************************* */


function murm_superadmin_page() {	
	if( isset($_REQUEST['action']) ) {
        $action = $_REQUEST['action'];
    } else {
        $action = 'default';
    }

	switch( $action ) {
	case 'deny':
		$ret = murm_superadmin_deny();
		murm_superadmin_page_default( $ret );
		break;
	case 'approve':
		$ret = murm_superadmin_approve( $_REQUEST['id'] );
		murm_superadmin_page_default( $ret );
		break;
	case 'confirm-delete':
		murm_superadmin_page_confirm_delete();
		break;
	case 'delete':
		$ret = murm_superadmin_delete();
		murm_superadmin_page_default( $ret );
		break;
	default:
		murm_superadmin_page_default();
	}
}



function murm_superadmin_page_default( $ret = array() ) {

	if( !empty( $ret ) ) {
		if( $ret["result"] ) {
			murm_nag( $ret["message"] );
		} else {
			murm_nagerr( $ret["message"] );
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
		
	$requests = $wpdb->get_results( $wpdb->prepare( $query ) );
	
	?>
	<div class="wrap">
		<h2><?php _e( 'MURM Network Registration Request' , MURM_TEXTDOMAIN ); ?></h2>
		<table class="widefat" cellspacing="0">
		    <thead>
		        <tr>
		            <th scope="col" class="manage-column"><?php _e( 'id' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Blog' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'User name' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'E-mail' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Displayed name' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Web page' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Message for blog owner' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Approved by' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Action' , MURM_TEXTDOMAIN ); ?></th>
				</tr>
			</thead>
			<tfoot>
		        <tr>
		            <th scope="col" class="manage-column"><?php _e( 'id' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Blog' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'User name' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'E-mail' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Displayed name' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Web page' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Message for blog owner' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Approved by' , MURM_TEXTDOMAIN ); ?></th>
		            <th scope="col" class="manage-column"><?php _e( 'Action' , MURM_TEXTDOMAIN ); ?></th>
				</tr>
			</tfoot>
			<?php
				foreach( $requests as $request ) {
					?>
					<tr>
						<td><code><small><?php echo $request->id; ?></small></code></td>
						<td><?php echo $request->domain; ?></td>
						<td><?php echo $request->username; ?></td>
						<td><a href="mailto:<?php echo $request->email; ?>?subject=[<?php echo $request->domain; ?>] <?php _e( 'Registration request', MURM_TEXTDOMAIN ); ?>"><?php echo $request->email; ?></a></td>
						<td><?php echo $request->displayname; ?></td>
						<td><a href="<?php echo murm_fix_blog_url( $request->url ); ?>"><?php echo $request->url; ?></a></td>
						<td><?php echo $request->message; ?></td>
						<td><?php echo $request->approved_by; ?></td>
						<td>
							<a href="users.php?page=murm-superadmin&action=approve&id=<?php echo $request->id; ?>" style="color: green;"><?php _e( 'Approve' , MURM_TEXTDOMAIN ); ?></a><br />
							<a href="users.php?page=murm-superadmin&action=deny&id=<?php echo $request->id; ?>" style="color: red;"><?php _e( 'Deny' , MURM_TEXTDOMAIN ); ?></a><br />
							<a href="users.php?page=murm-superadmin&action=confirm-delete&id=<?php echo $request->id; ?>" style="color: grey;" ><?php _e( 'Delete' , MURM_TEXTDOMAIN ); ?></a>
						</td>
					</tr>
				
					<?php
				}
			?>
		</table>
	</div>
	<?php
}



function murm_superadmin_approve( $rid ) {

	murm_log( 'superadmin approving request '.$rid );

	$request = murm_get_superadmin_request( $rid );
	
	if( $request == NULL ) {
		murm_log( 'error - rr '.$rid.' doesnt exist', 4 );
		return array(
			"result" => false,
			"message" => sprintf( __( 'Request no.%d was not found in the database, so it cannot be approved. If you think this is an error, please contact the plugin developer.' , MURM_TEXTDOMAIN ), $rid )
		);
	}
	
	// vytvořit uživatele
	if( !murm_is_valid_combination( $request->username, $request->email ) ) {
		murm_log( 'error - invalid username and email combination', 4 );
		return array(
			"result" => false,
			"message" => sprintf( __( 'Cannot create the user account - combination of username and e-mail (%s, %s) is already taken.' , MURM_TEXTDOMAIN ), $request->username , $request->email ) 
		);
	}
	
	extract( murm_get_site_settings() );
	
	if( username_exists( $request->username ) ) {
		murm_log( 'user already exists - adding to blog' );
		$user = get_userdatabylogin( $request->username );
		
		switch_to_blog( $request->blog_id );
		if( user_can( $user->ID, 'read' ) ) {
			murm_log( 'error - user '.$request->username.' already registered on blog '.$request->blog_id, 4 );
			return array(
				"result" => false,
				"message" => sprintf( __( 'User %s is already registered on this blog. Cannot continue.' , MURM_TEXTDOMAIN ), $request->username ) 
			);
		} 
		restore_current_blog();
		
		add_user_to_blog( $request->blog_id, $user->ID, 'subscriber' );
		
		if( $to_existing_user_on_approve['enabled'] ) {
			$user_subject = murm_parse_mail( $to_existing_user_on_approve['subject'], $request->blog_id, $request );
			$user_message = murm_parse_mail( $to_existing_user_on_approve['message'], $request->blog_id, $request );	
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
		murm_log( 'creating new user '.$request->username.', '.$request->email.': '.print_r( $userdata, TRUE ).'; password: '.$password );
		
		if( $to_new_user_on_approve['enabled'] ) {
			$user_subject = murm_parse_mail( $to_new_user_on_approve['subject'], $request->blog_id, $request, $password );
			$user_message = murm_parse_mail( $to_new_user_on_approve['message'], $request->blog_id, $request, $password );	
		} else {
			murm_log( 'NOT sending an e-mail to newly created user. It\'s password must be delivered manually.', 3 );
		}
	}
	
	// mail uživateli
	murm_log( 'sending mail to user' );
	wp_mail( $request->email, $user_subject, $user_message );	
	
	// mail adminovi
	$admin = get_userdata( $request->approved_by );
	if( murm_is_current_user_email( $admin->user_email ) ) {
		murm_log( 'skipping mail to admin because it\'s current user', 2 );
	} elseif( $to_admin_on_superadmin_approve['enabled'] ) {
		murm_log( 'sending mail to admin' );
		$admin_subject = murm_parse_mail( $to_admin_on_superadmin_approve['subject'], $request->blog_id, $request );
		$admin_message = murm_parse_mail( $to_admin_on_superadmin_approve['message'], $request->blog_id, $request );	
		wp_mail( $admin->user_email, $admin_subject, $admin_message );
	}
	
	// odstranit žádost
	$dok = murm_delete_superadmin_request( $rid );
	
	if( $dok ) {
		murm_log( 'rr successfully approved', 2 );
		return array(
			"result" => true,
			"message" => sprintf( __( 'Request no.%d has been approved.' , MURM_TEXTDOMAIN ), $rid ) 
		);
	} else {
		murm_log( 'Error while deleting rr from db', 4 );
		murm_extended_log( murm_dberror_description() );
		return array(
			"result" => false,
			"message" => sprintf( __( 'Database error, request no.%d could not be fully processed. Please contact the plugin developer.' , MURM_TEXTDOMAIN ), $rid ) 
		);
	}
}


function murm_superadmin_deny() {
	$rid = $_REQUEST['id'];
	
	murm_log( 'superadmin denying registration request '.$rid );
	
	$request = murm_get_superadmin_request( $rid );
	
	if( $request == NULL ) {
		murm_log( 'error - rr '.$rid.' doesnt exist', 4 );
		return array(
			"result" => false,
			"message" => sprintf( __( 'Request no.%d was not found in the database, so it cannot be denied. If you think this is an error, please contact the plugin developer.' , MURM_TEXTDOMAIN ), $rid ) 
		);
	}
	
	extract( murm_get_site_settings() );
	
	// mail adminovi
	$admin = get_userdata( $request->approved_by );
	if( murm_is_current_user_email( $admin->user_email ) ) {
		murm_log( 'skipping mail to admin because it\'s the current user.',  2 );
	} elseif( $to_admin_on_superadmin_deny['enabled'] ) {
		murm_log( 'sending mail to admin' );
		$admin_subject = murm_parse_mail( $to_admin_on_superadmin_deny['subject'], $request->blog_id, $request );
		$admin_message = murm_parse_mail( $to_admin_on_superadmin_deny['message'], $request->blog_id, $request );	
		wp_mail( $admin->user_email, $admin_subject, $admin_message );
	}

	// mail žádajícímu
	if( $to_user_deny_from_superadmin['enabled'] ) {
		murm_log( 'sending mail to user' );
		$user_subject = murm_parse_mail( $to_user_deny_from_superadmin['subject'], $request->blog_id, $request );
		$user_message = murm_parse_mail( $to_user_deny_from_superadmin['message'], $request->blog_id, $request );	
		wp_mail( $request->email, $user_subject, $user_message );
	}
	
	$dok = murm_delete_superadmin_request( $rid );
	
	if( $dok ) {
		murm_log( 'rr successfully denied', 2 );
		return array(
			"result" => true,
			"message" => sprintf( __( 'Request no.%d has been denied and an information e-mail has been sent to the request author and blog owner.' , MURM_TEXTDOMAIN ), $rid ) 
		);
	} else {
		murm_log( 'Error while removing rr from db', 4 );
		murm_extended_log( murm_dberror_description() );
		return array(
			"result" => false,
			"message" => sprintf( __( 'Database error, request no.%d could not be fully processed. Please contact the plugin developer.' , MURM_TEXTDOMAIN ), $rid ) 
		);
	}
}


function murm_superadmin_page_confirm_delete() {
	$rid = $_REQUEST['id'];
	$request = murm_get_superadmin_request( $rid );
	?>
	<div class="wrap">
		<h2><?php _e( 'MURM Network Registration Request' , MURM_TEXTDOMAIN ); ?></h2>
		<p><?php printf( __( 'You are about to delete reqistration request no.%d (user %s with e-mail %s). Do you really want to proceed?' , MURM_TEXTDOMAIN ), $rid, '<em>'.$request->username.'</em>', '<em>'.$request->email.'</em>' ); ?></p>
		<p><?php _e( 'When deleting a request, the requesting person is not informed about it (on the contrary to denying the request). You should use this function only to delete spam or otherwise processed requests.' , MURM_TEXTDOMAIN ); ?></p>
		<form method="post" action="users.php?page=murm-superadmin">
			<input type="hidden" name="action" value="delete" />
			<input type="hidden" name="id" value="<?php echo $rid; ?>" />
			<p class="submit">
	            <input type="submit" value="<?php _e( 'Delete request' , MURM_TEXTDOMAIN ); ?>" />    
	        </p> 
		</form>
		<p>
			&laquo; <a href="users.php?page=murm-superadmin"><?php _e( 'Back to reguest overview' , MURM_TEXTDOMAIN ); ?></a> &laquo;
		</p>
	</div>
	<?php
}


function murm_superadmin_delete() {
	$rid = $_REQUEST['id'];
	
	murm_log( 'superadmin deleting registration request '.$rid );
	
	$dok = murm_delete_superadmin_request( $rid );
	
	if( $dok ) {
		murm_log( 'rr successfully deleted', 2 );
		return array(
			"result" => true,
			"message" => sprintf( __( 'Request no.%d has been deleted and no information e-mail has <strong>not</strong> been sent to the request author.' , MURM_TEXTDOMAIN ), $rid ) 
		);
	} else {
		murm_log( 'Error while removing rr from db', 4 );
		murm_extended_log( murm_dberror_description() );
		return array(
			"result" => false,
			"message" => sprintf( __( 'Database error, request no.%d could not be fully processed. Please contact the plugin developer.' , MURM_TEXTDOMAIN ), $rid ) 
		);
	}

}


?>
