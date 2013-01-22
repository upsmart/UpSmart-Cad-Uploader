<?php
/**
 * Theme options sidebar
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		0.0.6
 *
 */

add_action( 'optionsframework_after', 'tiga_options_sidebar' );
function tiga_options_sidebar() { ?>

	<div id="optionsframework-sidebar">
		<div class="metabox-holder">
			
			<div class="tiga-support">
				<a class="button button-primary button-hero" href="<?php echo esc_url( 'http://satrya.me/tiga/' ); ?>" target="_blank"><?php _e( 'Support', 'tiga' ); ?></a>
				<a class="button button-primary button-hero" href="<?php echo esc_url( 'http://wordpress.org/support/view/theme-reviews/tiga' ); ?>" target="_blank"><?php _e( 'Feedback', 'tiga' ); ?></a>
			</div>

			<div id="tiga-buddypress" class="postbox">
				<h3 class="hndle"><?php _e( 'Components', 'tiga' ); ?></span></h3>
				<div class="inside">
					<ol>
						<li><a href="<?php echo esc_url( 'http://satrya.me/forums/topic/tiga-components/' ) ?>" target="_blank"><?php _e( 'Buddypress', 'tiga' ); ?></a> <br />
							<?php _e( 'A child theme for buddypress compatibility.', 'tiga' ); ?></li>
						<li><a href="<?php echo esc_url( 'http://satrya.me/forums/topic/tiga-components/' ) ?>" target="_blank"><?php _e( 'Sample child theme', 'tiga' ); ?></a> <br />
							<?php _e( 'Sample child theme for Tiga.', 'tiga' ) ?></li>
						<li><a href="<?php echo esc_url( 'http://satrya.me/forums/topic/tiga-components/' ) ?>" target="_blank"><?php _e( 'Sass files', 'tiga' ); ?></a> <br />
							<?php _e( 'I\'m building Tiga with SASS & Compass, download it if you need.', 'tiga' ); ?></li>
					</ol>
				</div>
			</div>
			
		</div>
	</div>
	
<?php }


/**
 * loads an additional CSS file for the options panel
 *
 * @since 0.0.6
 */
 if ( is_admin() ) {
    $of_page= 'appearance_page_options-framework';
    add_action( "admin_print_styles-$of_page", 'tiga_optionsframework_custom_css', 100 );
}
 
function tiga_optionsframework_custom_css () {
	wp_register_style( 'tiga_optionsframework_custom_css', trailingslashit( TIGA_CSS ) .'options-custom.css' );
	wp_enqueue_style( 'tiga_optionsframework_custom_css' );
}
