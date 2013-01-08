<?php


class Murm_Database {

	var $p;
	
	
	function __construct( $parent ) {
		$this->p = $parent;
	}
	
	
	// table manipulation
	
	function create_tables() {
		global $wpdb;
	
		$site_query = "CREATE TABLE IF NOT EXISTS {$wpdb->base_prefix}murm_requests (
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
		    )";
		    
		$serr = $wpdb->query( $site_query );
		if( $serr === FALSE ) {
			$this->p->extended_log( "MySQL error while creating {$wpdb->base_prefix}murm_requests table: ".$this->p->dberror_description(), 4 );
			return false;
		}
		    
		$blog_query = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}murm_requests (
				id INT NOT NULL AUTO_INCREMENT,
				username VARCHAR( 60 ),
				displayname VARCHAR( 50 ),
				url VARCHAR( 100 ),
				email VARCHAR( 100 ),
				message TEXT,
				UNIQUE ( id ),
		        PRIMARY KEY ( id )
		    )";
	
		$berr = $wpdb->query( $blog_query );
		if( $berr === FALSE ) {
			$this->p->extended_log( "MySQL error while creating {$wpdb->prefix}murm_requests table: ".$this->p->dberror_description(), 4 );
			return false;
		}

		return true;
	}

	
	
	function drop_blog_table() {
		global $wpdb;
		$query = 'DROP TABLE '.$wpdb->prefix.'murm_requests';
		$derr = $wpdb->query( $query );
		return !( $derr === FALSE );
	}

	
	// blog admin queries
	
	function has_blog_requests() {
		global $wpdb;
		$query = "SELECT COUNT(*) FROM {$wpdb->prefix}murm_requests";
		$count = $wpdb->get_var( $query );
		return ( $count > 0 );
	}
	
	
	function create_blog_request( $user_name, $display_name, $email, $url, $message ) {
		global $wpdb;
		$iok = $wpdb->insert( $wpdb->prefix.'murm_requests', 
			array(
				'username' => $user_name,
				'displayname' => $display_name,
				'email' => $email,
				'url' => $url,
				'message' => $message
			) );
		if( !$iok ) {
			return 0;
		} else {
			return $wpdb->insert_id;
		}
	}
	
	
	function delete_blog_request( $request_id ) {
		global $wpdb;
		$query = "DELETE FROM {$wpdb->prefix}murm_requests WHERE id = %d";
		$dok = $wpdb->query( $wpdb->prepare( $query, $request_id ) );
		return !( $dok === FALSE );
	}


	function get_blog_request( $request_id ) {
		global $wpdb;
		$query = "SELECT * FROM {$wpdb->prefix}murm_requests WHERE id = %d";
		return $wpdb->get_row( $wpdb->prepare( $query, $request_id ) );
	}
	
	
	function get_blog_requests() {
		global $wpdb;	
		$query = "SELECT id, username, email, displayname, url, message FROM {$wpdb->prefix}murm_requests";
		return $wpdb->get_results( $query );
	}


	// network admin queries
	
	function create_network_request( $data ) {
		global $wpdb;
		$iok = $wpdb->insert( $wpdb->base_prefix.'murm_requests', $data );
		if( !$iok ) {
			return 0;
		} else {
			return $wpdb->insert_id;
		}
	}
	
	
	function get_network_request( $request_id ) {
		global $wpdb;
		$query = "SELECT * FROM {$wpdb->base_prefix}murm_requests WHERE id = %d";
		return $wpdb->get_row( $wpdb->prepare( $query, $request_id ) );
	}

	
	function delete_network_request( $request_id ) {
		global $wpdb;
		$query = "DELETE FROM {$wpdb->base_prefix}murm_requests	WHERE id = %d";
		$dok = $wpdb->query( $wpdb->prepare( $query, $request_id ) );
		return !( $dok === FALSE );
	}


	// mixed queries
	
	function is_duplicate_request( $username, $email ) {
		global $wpdb, $blog_id;
	
		$blog_query = "
			SELECT COUNT(*)
			FROM {$wpdb->prefix}murm_requests
			WHERE (
				username = %s 
				OR email = %s
			)";
	
		$blog_duplicates = $wpdb->get_var( $wpdb->prepare( $blog_query, $username, $email ) );
			
		$net_query = "
			SELECT COUNT(*)
			FROM {$wpdb->base_prefix}murm_requests
			WHERE 
				( username = %s	OR email = %s ) 
				AND blog_id = %d";
	
		$net_duplicates = $wpdb->get_var( $wpdb->prepare( $net_query, $username, $email, $blog_id ) );
	
		return ( ( $blog_duplicates != 0 ) or ( $net_duplicates != 0 ) );
	}


	
}



?>
