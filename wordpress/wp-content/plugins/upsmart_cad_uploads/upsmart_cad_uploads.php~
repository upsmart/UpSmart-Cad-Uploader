<?php
/*
Plugin Name: upSmart_RS
Plugin URI: http://aarontobias.com
Description: Company Profiles can be approved and voted up for registered users 
             at upSmart LLC website via go-upsmart.com
Version: 1.0
Last Modified: January 6th 2012
Author: Aaron Tobias
Author URI: aarontobias.com
License: GNU GENERAL PUBLIC LICENSE
*/
DEFINE( 'WP_DEBUG', true);
DEFINE( 'UPSMART_RS_PLUGIN_URL', trailingslashit( WP_PLUGIN_URL ) . basename( dirname( __FILE__ ) ) );
DEFINE( 'UPSMART_RS_IMAGES_URL', trailingslashit( WP_PLUGIN_URL ) . basename( dirname( __FILE__ ) ) . '/images/' );
DEFINE( 'UPSMART_RS_APPROVE', 'approve' );
DEFINE( 'UPSMART_RS_PENDING', 'pending' );

function upSmart_RS_110n_init(){
  load_plugin_textdomain( 'upSmart_RS', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}

add_action( 'init','upSmart_RS_110n_init' );

function upSmart_RS_register_plugin_js_css()
{
  wp_enqueue_script( 'jquery' );

  wp_register_style( 'upSmart_RS-css', plugins_url( '/css/upSmart_RS.css', __FILE__ ) );
  wp_enqueue_style( 'upSmart_RS-css' );

  wp_register_script( 'upSmart_RS-js', plugins_url( '/js/upSmart_RS.js', __FILE__ ) );
  wp_enqueue_script( 'upSmart_RS-js' );
  
  $protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
  wp_localize_script('upSmart_RS-js', 'upSmart_RS_l10n', array(
            			'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
            			'upSmart_RS_IMAGES_URL' => upSmart_RS_IMAGES_URL
	                ) );

}
add_action( 'init', 'upSmart_RS_register_plugin_js_css' );

function upSmart_RS_get_userID()
{
  global $current_user;
  $current_userIP = dechex( sanitize_key( $_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'] ) );
  $current_userID = ( is_user_logged_in() && get_option( 'upSmart_RS_logbyip' ) == '' ) ? $current_user->ID : $current_userIP;
  return $current_userID;
}

function upSmart_RS_ajax_php($atts, $content = null )
{
  global $post;
  $current_userID = upSmart_RS_get_userID();
  $response = 'some_response';
  
  /*Check if data from ajax was filled */
  if ( !isset( $_POST['ap'] ) || !isset( $_POST['nonce'] ) || !isset( $_POST['postID'] ) ) {
    $response = json_encode( array( 'error' => '1', 'msg' => '$post var not set!') ) ;;
    header( "Content-Type: application/json" );
    echo $response;
    die( );
  }
  
  $_CLEAN = array();
  $_CLEAN['action'] = $_POST['ap'];
  if ( $_CLEAN['action'] != UPSMART_RS_PENDING) {
    $_CLEAN['postID'] = 0;
  }
  else{
    $_CLEAN['postID'] = (int)$_POST['postID'];
    $_CLEAN['nonce'] = wp_verify_nonce( $_POST['nonce'], 'upSmart_RS_approve_' . $_CLEAN['postID'] );
  }
  
  /* NOTE: storing in options database is working ... now we just need postmeta stored and were done!*/
  
  if ( $_CLEAN['postID'] > 0 && $_CLEAN['nonce'] ) {
 
  $usersapprove = get_post_meta( $_CLEAN['postID'], '_upSmart_RS-usersapproveit', true );
  
    if ( get_option( 'upSmart_RS_memberaccess' ) == 'on' && !is_user_logged_in() ) {
      $textneedlogin = esc_attr( get_option( 'upSmart_RS_textneedlogin' ) );
      $textneedlogin = str_replace( '{connection}', '<a href="' . wp_login_url( get_permalink( $_CLEAN['postID'] ) ) . '">' . __( 'Connection', 'upSmart_RS' ) . '</a>', $textneedlogin );
      $textneedlogin = str_replace( '{registration}', wp_register( '', '', false ), $textneedlogin );
      $response = json_encode( array( 'error' => '2', 'msg' => '<p style="clear: both;">' . $textneedlogin . '</p><a href="http://go-upsmart.com/login/" title="Login" > Login Page </a>') );
    } 
    else if ( $_CLEAN['action'] == UPSMART_RS_PENDING ) {
      $postsapprove = get_user_meta( $current_userID, '_upSmart_RS-postsapproveit', true ); 
      //echo ("USER APPROVED:  " . $usersapprove);
      if ( strstr( ',' . $usersapprove . ',', ',' . $current_userID . ',' ) != '' ) {
         $newusersap = str_replace( ',' . $current_userID . ',', ',', ',' . $usersapprove );
         update_post_meta( $_CLEAN['postID'], '_upSmart_RS-usersapproveit', substr( $newusersap, 1 ), $usersapprove );
         $newpostsap = str_replace( ',' . $_CLEAN['postID'] . ',', ',', ',' . $postsapprove );
         update_user_meta( $current_userID, '_upSmart_RS-postsapproveit', substr( $newpostsap, 1 ), $postsapprove );
      }
      else{
          update_post_meta( $_CLEAN['postID'], '_upSmart_RS-usersapproveit', $usersapprove . $current_userID . ',' );
          update_user_meta( $current_userID, '_upSmart_RS-postsapproveit', $postsapprove . $_CLEAN['postID'] . ',' );
        }
      
      extract(shortcode_atts(array(
	 "ID" => '0',
	 "type" => 'post',
	 "approve" => UPSMART_RS_APPROVE
      ), $atts));
      
      $ID = $ID > 0 ? $ID : ( $type == 'post' ? $post->ID : bawlu_get_userID() );
      $type = $type == 'user' ? 'post' : 'user';
      $rank = -1;
      if ( $type == 'user' ) {
	  $rank = count( explode( ',', get_post_meta( $ID, '_upSmart_RS-' . $type . 's' . $approve . 'it', true ) ) ) - 1;
      }
      else{
	  $rank = count( explode( ',', get_user_meta( $ID, '_upSmart_RS-' . $type . 's' . $approve . 'it', true ) ) ) - 1;
      }
       
      $response = json_encode( array( 'error' => '0', 'msg' => $current_userID, 'rank' => $rank) );
    } 
    
    header( "Content-Type: application/json" );
    echo $response;
    die();
  }
}
add_action( 'wp_ajax_upSmart_RS_ajax_php', 'upSmart_RS_ajax_php' );
add_action( 'wp_ajax_nopriv_upSmart_RS_ajax_php', 'upSmart_RS_ajax_php' );

function upSmart_RS_createbutton() {
global $post;
global $current_user;

$current_userID = upSmart_RS_get_userID();
$postID = $postID > 0 ? $postID : $post->ID;
$nonce = wp_create_nonce( 'upSmart_RS_approve_'.$postID );

/* NOTE: if user already approved this post need to change href to UPSMART_RS_APPROVE 
$ID = $ID > 0 ? $ID : ( $type == 'post' ? $post->ID : bawlu_get_userID() );
get_post_meta( $ID, '_upSmart_RS-' . $type . 's' . UPSMART_RS_APPROVE . 'it', true );
*/
$usersapprove = get_post_meta( $post->ID, '_upSmart_RS-usersapproveit', true );


if($usersapprove != '' && is_user_logged_in()){
$approvalSection = "<div id='upSmart_RS' class = 'approve'>";		
$approvalSection .= "<h3>" . $current_user->display_name . ", thanks for approving " . get_the_title() . " as a good investment! </h3>";
$approvalSection .= "<p>Thanks to your vote of approval, UpSmart LLC will embrace " . get_the_title() . " ";
$approvalSection .= 'and get them more involved with your investment interests. Thanks for being a part of our online community!</p>';
$approvalSection .= "<div class = 'companyApproval'>";
$approvalSection .= '<a class="rs_approved" title="' . get_the_title() . ' was approved!" href="#' . $nonce . ',' . $postID . ',' . UPSMART_RS_PENDING . '"></a>';
}
else
{
$approvalSection = "<div id='upSmart_RS' class = 'approve'>";		
$approvalSection .= "<h3>Do you like " . get_the_title() . " for its: </h3>";
$approvalSection .= "<ul>";
$approvalSection .= "<li> Investment Potential </li>";
$approvalSection .= "<li> Business Actions </li>";
$approvalSection .= "<li> Business Strategy </li>";
$approvalSection .= "<li> Community Service </li>";
$approvalSection .= "</ul>";
$approvalSection .= "<p>Would you like us to involve them directly in our site?";
$approvalSection .= ' If so, click the "Approve" button below to let us know!</p>';
$approvalSection .= "<div class = 'companyApproval'>";
$approvalSection .= '<a class="rs_img" title="Approve ' . get_the_title() . '" href="#' . $nonce . ',' . $postID . ',' . UPSMART_RS_PENDING . '"></a>';
}
     
extract(shortcode_atts(array(
 "ID" => '0',
 "type" => 'post',
 "approve" => UPSMART_RS_APPROVE
), $atts));

$ID = $ID > 0 ? $ID : ( $type == 'post' ? $post->ID : bawlu_get_userID() );
$type = $type == 'user' ? 'post' : 'user';
$rank = -1;
if ( $type == 'user' ) {
  $rank = count( explode( ',', get_post_meta( $ID, '_upSmart_RS-' . $type . 's' . $approve . 'it', true ) ) ) - 1;
}
else{
  $rank = count( explode( ',', get_user_meta( $ID, '_upSmart_RS-' . $type . 's' . $approve . 'it', true ) ) ) - 1;
}

$approvalSection .= '<span class="rs_rank">'. $rank .'</span>';
$approvalSection .= "</div><div id='appPreLoad'></div>";
/* DEBUG: $usersapprove . ' ' . $nonce . ' '. $post->ID */
$approvalSection .= "<div id='ratePrompt'></div></div>";
return $approvalSection;
}
add_shortcode( 'upSmart_RS_button', 'upSmart_RS_createbutton' );

function upSmart_RS_default_values()
{
  load_plugin_textdomain( 'upSmart_RS', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );

  add_option( 'upSmart_RS_memberaccess', 'on' );
  add_option( 'upSmart_RS_logbyip', '' );
  add_option( 'upSmart_RS_texterror', __( 'Error', 'upSmart_RS' ) );
  add_option( 'upSmart_RS_textneedlogin', __( 'Please login to rate this company', 'upSmart_RS' ) );
}
register_activation_hook( __FILE__, 'upSmart_RS_default_values' );

function upSmart_check_versions() {
  global $wp_version;
	if ( version_compare( PHP_VERSION, '5.0.0', '<' ) ) {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die( __( 'This plugin requires PHP 5.0 or more', 'upSmart_RS' ) );
	}
	if ( version_compare( $wp_version, '3.1', '<' ) ) {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die( __( 'This plugin requires WordPress 3.1 or more. Your version : ' . $wp_version, 'upSmart_RS' ) );
	}
}
register_activation_hook( __FILE__, 'upSmart_check_versions' );

function upSmart_RS_uninstaller(){
  global $wpdb;
  $wpdb->query( 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "upSmart_RS%"' );
  $wpdb->query( 'DELETE FROM ' . $wpdb->postmeta . ' WHERE meta_key LIKE "_upSmart_RS%"' );
}
register_uninstall_hook( __FILE__, 'upSmart_RS_uninstaller' );
?>