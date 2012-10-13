<?php


/* ************************************************************************* *\
	MURM DATABASE ACCESS
\* ************************************************************************* */


function murm_create_tables() {
	global $wpdb;
	
	$site_query = '
		CREATE TABLE IF NOT EXISTS '.$wpdb->base_prefix.'murm_requests (
			id INT NOT NULL AUTO_INCREMENT,
			blog_id INT,
			approved_by INT,
			username VARCHAR( 60 ),
			displayname VARCHAR( 50 ),
			url VARCHAR( 100 ),
			email VARCHAR( 100 ),
			message TEXT,
			UNIQUE ( id ),
            PRIMARY KEY ( id )
        )';
        
	$serr = $wpdb->query( $wpdb->prepare( $site_query ) );
	if( $serr === FALSE ) {
		murm_extended_log( "MySQL error while creating {$wpdb->base_prefix}murm_requests table: last query was \"{$wpdb->last_query}\", resulting to \"{$wpdb->last_result}\", error \"{$wpdb->last_error}\".", 4 );
		return false;
	}
        
	$blog_query = '
		CREATE TABLE IF NOT EXISTS '.$wpdb->prefix.'murm_requests (
			id INT NOT NULL AUTO_INCREMENT,
			username VARCHAR( 60 ),
			displayname VARCHAR( 50 ),
			url VARCHAR( 100 ),
			email VARCHAR( 100 ),
			message TEXT,
			UNIQUE ( id ),
            PRIMARY KEY ( id )
        )';
	
	$berr = $wpdb->query( $wpdb->prepare( $blog_query ) );
	if( $berr === FALSE ) {
		murm_extended_log( "MySQL error while creating {$wpdb->prefix}murm_requests table: last query was \"{$wpdb->last_query}\", resulting to \"{$wpdb->last_result}\", error \"{$wpdb->last_error}\"." );
		return false;
	}

	return true;
}


function murm_drop_blog_table() {
	global $wpdb;
	$query = 'DROP TABLE '.$wpdb->prefix.'murm_requests';
	$derr = $wpdb->query( $wpdb->prepare( $query ) );

	if( $derr === FALSE ) {
		return false;
	} else {
		return true;
	}
}


function murm_delete_admin_request( $request_id ) {
	global $wpdb;
	
	$query = '
		DELETE FROM '.$wpdb->prefix.'murm_requests
		WHERE id='.$request_id;
		
	$dok = $wpdb->query( $wpdb->prepare( $query ) );
	
	if( $dok === FALSE ) {
		return false;
	} else {
		return true;
	}
}


function murm_delete_superadmin_request( $request_id ) {
	global $wpdb;
	
	$query = '
		DELETE FROM '.$wpdb->base_prefix.'murm_requests
		WHERE id='.$request_id;
		
	$dok = $wpdb->query( $wpdb->prepare( $query ) );
	
	if( $dok === FALSE ) {
		return false;
	} else {
		return true;
	}
}


function murm_get_admin_request( $request_id ) {
	global $wpdb;
	
	$query = '
			SELECT *
			FROM '.$wpdb->prefix.'murm_requests
			WHERE id='.$request_id;
	
	$result = $wpdb->get_row( $wpdb->prepare( $query ) );
	
	return $result;
}


function murm_get_superadmin_request( $request_id ) {
	global $wpdb;
	
	$query = '
			SELECT *
			FROM '.$wpdb->base_prefix.'murm_requests
			WHERE id='.$request_id;
			
	$result = $wpdb->get_row( $wpdb->prepare( $query ) );
	
	return $result;
}


function murm_has_admin_requests() {
	global $wpdb;
	
	$query = '
		SELECT COUNT(*)
		FROM '.$wpdb->prefix.'murm_requests';
		
	$count = $wpdb->get_var( $wpdb->prepare( $query ) );
	
	if( $count > 0 ) {
		return true;
	} else {
		return false;
	}
}



?>
