<?php
/**
 * The template used for displaying post content in single.php
 * 
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		0.0.1
 *
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<header class="entry-header">
		
			<h1 class="entry-title">
				<a href="<?php esc_url( the_permalink() ); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'tiga' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php esc_attr( the_title() ); ?></a>
			</h1>

			<div class="entry-meta">
				<?php tiga_posted_on(); ?>
			</div><!-- .entry-meta -->
			
		</header><!-- .entry-header -->
		
		<?php get_sidebar( 'above-content' ); ?>
		
		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'tiga' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		
		<?php get_sidebar( 'below-content' ); ?>
		
		<footer class="entry-meta">
		
			<?php 
				$tiga_socialpage = of_get_option( 'tiga_social_share' );
				$disable_social = get_post_meta(get_the_ID(), 'tiga_social_check', true);

				if( ( 'tiga_post' == $tiga_socialpage ) || ( 'tiga_both' == $tiga_socialpage ) && ( $disable_social != 'true' ) )
					tiga_share_buttons();
			?>
		
			<?php 
				$tags_list = get_the_tag_list( '', ', ' );
					printf( __( '<span class="%1$s">%2$s</span>', 'tiga' ), 'entry-utility-prep entry-utility-prep-tag-links tag', $tags_list );

				$categories_list = get_the_category_list(', ');
					printf( __( '<span class="%1$s category">%2$s</span>', 'tiga' ), 'entry-utility-prep entry-utility-prep-cat-links cat', $categories_list );
			?>

			<?php edit_post_link( __( 'Edit', 'tiga' ), '<span class="post-edit">', '</span>' ); ?>
			
			<?php if( of_get_option( 'tiga_author_box' ) )
					tiga_the_author();
				?>
			
		</footer><!-- .entry-meta -->
		
	</article><!-- end #article-<?php the_ID(); ?> -->