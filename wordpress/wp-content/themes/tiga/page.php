<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		0.0.1
 *
 */

get_header(); ?>

	<section id="primary" class="site-content">

		<?php tiga_content_before(); ?>

		<div id="content" role="main">

			<?php tiga_content(); ?>

			<?php while ( have_posts() ) : the_post(); ?>
				
				<?php get_template_part( 'content', 'page' ); ?>

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->

		<?php tiga_content_after(); ?>

	</section><!-- #primary .site-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>