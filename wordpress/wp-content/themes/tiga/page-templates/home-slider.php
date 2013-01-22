<?php
/**
 * Template Name: Blog Page with Slider
 * Description: A Page Template for displaying a recent post with slider.
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		1.0
 */

get_header(); ?>

	<section id="primary" class="site-content">

		<?php tiga_content_before(); ?>

		<div id="content" role="main">
			
			<?php tiga_content(); ?>
			
			<?php get_template_part( 'content', 'featured' ); ?>
			
			<?php 
				$paged = 1;
				if ( get_query_var( 'paged' ) ) { $paged = get_query_var( 'paged' ); }
				if ( get_query_var( 'page' ) ) { $paged = get_query_var( 'page' ); }
				$paged = intval( $paged );
				
				$args = array(
					'post__not_in' => get_option('sticky_posts'),
					'paged' => $paged,
					'post_type' => 'post',
				);
				query_posts( $args );
				
				if ( have_posts() ) : 
			?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content' ); ?>

				<?php endwhile; ?>

				<?php tiga_content_nav( 'nav-below' ); ?>

			<?php elseif ( current_user_can( 'edit_posts' ) ) : ?>

				<?php get_template_part( 'no-results', 'index' ); ?>

			<?php endif; ?>
			
		</div><!-- #content -->

		<?php tiga_content_after(); ?>

	</section><!-- #primary .site-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>