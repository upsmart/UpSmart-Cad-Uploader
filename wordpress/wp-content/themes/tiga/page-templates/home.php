<?php
/**
 * Template Name: Home Page
 * Description: A Page Template for displaying a custom home page like twentytwelve.
 *
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		1.0
 */

get_header(); ?>

	<section id="full-primary" class="site-content full-width">

		<?php tiga_content_before(); ?>

		<div id="content" role="main">

			<?php tiga_content(); ?>

			<?php while ( have_posts() ) : the_post(); ?>
				
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'home-custom' ); ?>>

					<header class="entry-header">
						<h1 class="entry-title"><?php esc_attr( the_title() ); ?></h1>
					</header><!-- .entry-header -->

					<?php 
						$args = array(
							'order'          => 'ASC',
							'post_type'      => 'attachment',
							'post_parent'    => $post->ID,
							'post_mime_type' => 'image',
							'post_status'    => null,
							'numberposts'    => -1,
						);
						$attachments = get_posts($args);

						if ( $attachments ) {

							echo '<ul class="home-slides thumbnail">';

							foreach ( $attachments as $attachment ) {
								echo '<li>';
								echo wp_get_attachment_image( $attachment->ID, 'tiga-460px', false, false );
								echo '</li>';
							}

							echo '</ul>';

						}
					?>

					<div class="entry-content">
						<?php the_content(); ?>
						<?php edit_post_link( __( 'Edit', 'tiga' ), '<span class="page-edit">', '</span>' ); ?>
					</div><!-- .entry-content -->
					
				</article><!-- end #article-<?php the_ID(); ?> -->

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->

		<?php tiga_content_after(); ?>
		
	</section><!-- #full-primary .site-content -->

<?php get_sidebar( 'home' ); ?>
<?php get_footer(); ?>