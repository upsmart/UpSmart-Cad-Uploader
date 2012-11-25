<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		Tiga 0.0.1
 *
 */
?>

	<?php
		$layout = of_get_option('tiga_layouts');
		if( 'onecolumn' != $layout ) :
	?>

		<aside id="secondary" class="widget-area" role="complementary">
			<?php tiga_sidebar_before(); ?>

			<?php if ( ! dynamic_sidebar( 'General' ) ) : ?>
			<?php endif; // end sidebar widget area ?>

		</aside><!-- #secondary .widget-area -->

	<?php endif; ?>
