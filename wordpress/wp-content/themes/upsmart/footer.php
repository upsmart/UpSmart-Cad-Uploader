<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>
</div><!-- #wrapper -->
<footer style='display: block' role="contentinfo">
	Copyright UpSmart, LLC.
	<div id="site-generator">
		<?php do_action( 'upsmart_credits' ); ?>
		<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'upsmart' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'twentyeleven' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'upsmart' ), 'WordPress' ); ?></a>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>