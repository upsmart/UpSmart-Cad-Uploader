<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 * 
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		0.0.1
 *
 */
?>

	<article id="post-0" class="post no-results not-found">

		<header class="entry-header">
			<h1 class="entry-title"><?php _e( 'Nothing Found', 'tiga' ); ?></h1>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php if ( is_home() ) { ?>

				<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'tiga' ), admin_url( 'post-new.php' ) ); ?></p>

			<?php } elseif ( is_search() ) { ?>

				<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'tiga' ); ?></p>
				<?php get_search_form(); ?>

			<?php } else { ?>

				<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'tiga' ); ?></p>
				<?php get_search_form(); ?>

			<?php } ?>
		</div><!-- .entry-content -->
		
	</article><!-- #post-0 -->
