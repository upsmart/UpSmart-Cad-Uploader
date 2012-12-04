<?php
/**
 * Single Blog Post template file
 * 
 * This file is the single blog post template file, used to display single blog posts.
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
			
			<?php get_template_part( 'content', 'single' ); ?>

			<?php tiga_content_nav( 'nav-below' ); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template( '', true );
			?>

		<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->

		<?php tiga_content_after(); ?>
		
	</section><!-- #primary .site-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>