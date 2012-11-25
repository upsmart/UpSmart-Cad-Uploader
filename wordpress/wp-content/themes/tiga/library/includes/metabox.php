<?php
/**
 * Custom meta box for Tiga theme
 * 
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		Tiga 0.2
 *
 */

add_action( 'add_meta_boxes', 'tiga_meta_box' );
function tiga_meta_box() {
	add_meta_box( 'tiga_social_option', __('Tiga social share', 'tiga'), 'tiga_social_option_box', 'post', 'side', 'high' );
	add_meta_box( 'tiga_social_option', __('Tiga social share', 'tiga'), 'tiga_social_option_box', 'page', 'side', 'high' );
}

function tiga_social_option_box( $post ) {
	$values = get_post_custom( $post->ID );
	$check = isset( $values['tiga_social_check'] ) ? esc_attr( $values['tiga_social_check'][0] ) : '';
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	?>
	<p>
		<input type="checkbox" name="tiga_social_check" id="tiga_social_check" <?php checked( $check, 'true' ); ?> />
		<label for="tiga_social_check"><?php _e('Disable social share button on this post?', 'tiga'); ?></label>
	</p>
	<?php	
}

add_action( 'save_post', 'cd_meta_box_save' );
function cd_meta_box_save( $post_id ) {

	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
	
	if( !current_user_can( 'edit_post' ) ) return;
		
	$chk = ( isset( $_POST['tiga_social_check'] ) && $_POST['tiga_social_check'] ) ? 'true' : 'false';
	update_post_meta( $post_id, 'tiga_social_check', $chk );
}
?>