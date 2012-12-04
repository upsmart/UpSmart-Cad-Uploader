<?php
/**
 * The Sidebar containing the footer widget areas.
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		0.0.1
 *
 */
?>

	<aside id="footer-sidebar" class="footer-widget-area clearfix <?php tiga_dynamic_sidebar_class( 'secondary' ); ?>">

		<?php dynamic_sidebar( 'subsidiary' ); ?>

	</aside> <!-- end #footer-sidebar .footer-widget-area -->