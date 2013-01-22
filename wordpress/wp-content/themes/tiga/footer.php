<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the id=main div, footer widgets
 * and all content after
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		0.0.1
 */
?>

		</div><!-- #main -->
	</div> <!-- end #page .hfeed .site -->

	<?php if ( is_active_sidebar( 'subsidiary' ) && !is_page_template( 'page-templates/home.php' ) ) : ?>
		<footer id="colophon" class="site-footer <?php tiga_dynamic_sidebar_class( 'subsidiary' ); ?>" role="contentinfo">
			<div class="footer">
			
				<?php dynamic_sidebar( 'subsidiary' ); ?>
			
			</div> <!-- end .footer -->
		</footer> <!-- end #colophon .site-footer -->
	<?php endif; ?>

	<div id="site-credit" class="site-info">
		<?php tiga_credits(); ?>
		
		<span class="copyleft">&copy; Copyright <?php echo date('Y'); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a>
		</span>
		
		<?php if( of_get_option( 'tiga_credits' ) == false ) { ?>

			<span class="credit">
				<?php printf( __('Powered by <a href="http://wordpress.org/" title="%1$s" rel="generator">%2$s</a> &middot; Theme by <a href="http://satrya.me/" title="%3$s" rel="designer">%4$s</a>', 'tiga'),
					esc_attr( 'A Semantic Personal Publishing Platform'),
					'WordPress',
					esc_attr( 'Satrya'),
					'Satrya'
				); ?>
			</span>

		<?php } ?>
		
	</div> <!-- #site-credit .site-info -->

	<?php wp_footer(); ?>
</body>
</html>