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
 * @since 		Tiga 0.0.1
 */
?>

		</div><!-- #main -->
	</div> <!-- end #page .hfeed .site -->

	<?php if(of_get_option('tiga_footer_widgets')): ?>
		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="footer">
			
				<?php get_sidebar( 'footer' ); ?>
			
			</div> <!-- end .footer -->
		</footer> <!-- end #colophon .site-footer -->
	<?php endif; ?>

	<div id="site-credit" class="site-info">
		<?php tiga_credits(); ?>
		
		<span class="copyleft">&copy; Copyright <?php echo date('Y'); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a>
		</span>
		
		<span class="credit">
			<?php printf( __('Powered by <a href="http://wordpress.org/" title="%1$s" rel="generator">%2$s</a> &middot; Theme by <a href="http://satrya.me/" title="%3$s" rel="designer">%4$s</a>', 'tiga'),
				esc_attr( 'A Semantic Personal Publishing Platform'),
				'WordPress',
				esc_attr( 'Satrya'),
				'Satrya'
			); ?>
		</span>
		
	</div> <!-- #site-credit .site-info -->

	<?php wp_footer(); ?>
</body>
</html>