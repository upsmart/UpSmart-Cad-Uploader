<?php
/**
 * The Sidebar containing the footer widget areas.
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		Tiga 0.0.1
 *
 */
?>

	<aside id="footer-sidebar" class="footer-widget-area clearfix">
		<?php tiga_sidebar_footer_before(); ?>
		
		<div class="footer-1 footer-widget">
			<?php if ( ! dynamic_sidebar( 'Footer Sidebar 1' ) ) : ?>
			<?php endif; ?>
		</div>
		
		<div class="footer-2 footer-widget">
			<?php if ( ! dynamic_sidebar( 'Footer Sidebar 2' ) ) : ?>
			<?php endif; ?>
		</div>
		
		<div class="footer-3 footer-widget">
			<?php if ( ! dynamic_sidebar( 'Footer Sidebar 3' ) ) : ?>
			<?php endif; ?>
		</div>
		
		<div class="footer-4 footer-widget">
			<?php if ( ! dynamic_sidebar( 'Footer Sidebar 4' ) ) : ?>
			<?php endif; ?>
		</div>
		
	</aside> <!-- end #footer-sidebar .footer-widget-area -->