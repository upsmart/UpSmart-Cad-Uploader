<?php
/**
 * The Sidebar containing the custom home page template widget areas.
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		1.0
 *
 */
?>

	<div id="home-content" class="widget-area <?php tiga_dynamic_sidebar_class( 'home' ); ?>">
		
		<?php dynamic_sidebar( 'home' ); ?>

	</div><!-- #home-content .widget-area -->