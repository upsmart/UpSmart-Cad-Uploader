<?php
/**
 * Template Name: Full Width Template
 * Description: A Page Template for displaying a full width content
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		0.0.1
 */
get_header(); ?>

	<section id="full-primary" class="site-content full-width">

		<?php tiga_content_before(); ?>

		<div id="content" role="main">

			<?php tiga_content(); ?>

			<?php while ( have_posts() ) : the_post(); ?>
				
				<?php get_template_part( 'content', 'page' ); ?>

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->

		<?php tiga_content_after(); ?>
		
	</section><!-- #full-primary .site-content -->

<?php get_footer(); ?>