<?php
/**
 * Theme options sidebar
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		Tiga 0.0.6
 *
 */

add_action( 'optionsframework_after','tiga_options_sidebar' );
function tiga_options_sidebar() { ?>

	<div id="optionsframework-sidebar">
		<div class="metabox-holder">
			
			<div class="tiga-support">
				<a href="http://satrya.me/" title="Support" target="_blank">Support</a> <a href="http://about.me/margasatrya" title="About theme author" target="_blank">About Author</a> <span class="donate-button"><a href="http://satrya.me/donation/" title="Donation" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/library/img/paypal-donate.gif" alt="Paypal"></a></span>
			</div>

			<div id="tiga-buddypress" class="postbox">
				<h3 class="hndle">Components</span></h3>
				<div class="inside">
					<ol>
						<li><a href="http://satrya.me/topic/3/tiga-components/" title="BuddyPress for tiga" target="_blank">Buddypress</a> <br />
							A child theme for buddypress compatibility.</li>
						<li><a href="http://satrya.me/topic/3/tiga-components/" title="Sample child theme" target="_blank">Sample child theme</a> <br />
							Sample child theme for Tiga.</li>
						<li><a href="http://satrya.me/topic/3/tiga-components/" title="Sass files" target="_blank">Sass files</a> <br />
							I'm building Tiga with SASS & Compass, download it if you need.</li>
					</ol>
				</div>
			</div>
			
			<div id="tiga-themes" class="postbox">
				<h3 class="hndle"><span>Recommended Theme</span></h3>
				<div class="inside">
					<a href="http://satrya.me/topic/10/foto-features/" title="Download now!" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/library/img/foto.png"></a>
					<p>Photo blogging WordPress theme with beautiful and modern look<br><a href="http://satrya.me/topic/10/foto-features/" title="Download now!" target="_blank">Get it for free! &rarr;</a></p>
				</div>
			</div>
			
		</div>
	</div>
	
<?php }


/**
 * loads an additional CSS file for the options panel
 *
 * @since Tiga 0.0.6
 */
 if ( is_admin() ) {
    $of_page= 'appearance_page_options-framework';
    add_action( "admin_print_styles-$of_page", 'tiga_optionsframework_custom_css', 100);
}
 
function tiga_optionsframework_custom_css () {
	wp_register_style( 'tiga_optionsframework_custom_css', get_template_directory_uri() .'/library/css/options-custom.css' );
	wp_enqueue_style( 'tiga_optionsframework_custom_css' );
}
