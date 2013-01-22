<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		0.0.1
 */

get_header(); ?>

		<section id="primary" class="site-content">

			<?php tiga_content_before(); ?>

			<div id="content" role="main">
				
				<?php tiga_content(); ?>
				
				<?php if ( have_posts() ) : ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'content', 'index' ); ?>

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