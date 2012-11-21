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
 * @since 		Tiga 0.0.1
 */

get_header(); ?>

		<section id="primary" class="site-content">

			<?php tiga_content_before(); ?>

			<div id="content" role="main">
				
				<?php tiga_content(); ?>

				<?php if( of_get_option('tiga_show_featured') ) : ?>
					<section class="featured-posts rslides_container">
						<div class="featuredposts-heading"><?php _e( 'Featured Posts', 'tiga' ); ?></div>
					
						<?php get_template_part( 'content', 'featured' ); ?>
						
					</section> <!-- end .featured-posts -->
				<?php endif; ?>
				
				<?php
					$paged = 1;
					if ( get_query_var( 'paged' ) ) { $paged = get_query_var( 'paged' ); }
					if ( get_query_var( 'page' ) ) { $paged = get_query_var( 'page' ); }
					$paged = intval( $paged );
					
					if( of_get_option('tiga_show_featured') ) {
						$args = array(
							'post__not_in' => get_option('sticky_posts'),
							'paged' => $paged,
						);
						query_posts( $args );
					}
					
					if ( have_posts() ) : 
				?>

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