<?php
/**
 * The template for displaying Author Archive page
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

			<?php if ( have_posts() ) : ?>

				<?php
					/* Queue the first post, that way we know
					 * what author we're dealing with (if that is the case).
					 *
					 * We reset this later so we can run the loop
					 * properly with a call to rewind_posts().
					 */
					the_post();
				?>
				
				<header class="page-header">
					<h1 class="page-title author"><?php printf( __( 'Author Archives: %s', 'tiga' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?></h1>
				</header>

				<?php
					/* Since we called the_post() above, we need to
					 * rewind the loop back to the beginning that way
					 * we can run the loop properly, in full.
					 */
					rewind_posts();
				?>

				<?php tiga_content_nav( 'nav-above' ); ?>

				<?php
				// If a user has filled out their description, show a bio on their entries.
				if ( get_the_author_meta( 'description' ) ) : ?>
				<div id="author-info">
					<div id="author-avatar">
						<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'tiga_author_bio_avatar_size', 60 ) ); ?>
					</div><!-- #author-avatar -->
					<div id="author-description">
						<h2><?php printf( __( 'About %s', 'tiga' ), get_the_author() ); ?></h2>
						<p><?php the_author_meta( 'description' ); ?></p>
					</div><!-- #author-description	-->
				</div><!-- #entry-author-info -->
				<?php endif; ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to overload this in a child theme then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'content', 'index' );
					?>

				<?php endwhile; ?>

				<?php tiga_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<?php get_template_part( 'no-results', 'author' ); ?>
				
			<?php endif; ?>

			</div><!-- #content -->

			<?php tiga_content_after(); ?>

		</section><!-- #primary .site-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>