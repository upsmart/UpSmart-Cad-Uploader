<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		0.0.1
 *
 */
?>

	<?php
		$layout = of_get_option( 'tiga_layouts' );
		if( 'onecolumn' != $layout ) :
	?>

		<aside id="secondary" class="sidebar-primary widget-area" role="complementary">
			<?php tiga_sidebar_before(); ?>

			<?php dynamic_sidebar( 'primary' ); ?>

		</aside><!-- #secondary .sidebar-primary .widget-area -->

	<?php endif; ?>
