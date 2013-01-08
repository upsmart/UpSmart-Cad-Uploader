<?php
/**
 * The template used for displaying post content in index.php
 * 
 * @package 	Tiga
 * @author		Satrya
 * @license		license.txt
 * @since 		0.0.1
 *
 */
?>

	<?php
		$class = of_get_option( 'tiga_home_layouts' );
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( "$class clearfix" ); ?>>
				
		<header>
			<h2 class="entry-title">
				<a href="<?php esc_url( the_permalink() ); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'tiga' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php esc_attr( the_title() ); ?></a>
			</h2>
		</header>
		
		<?php if(has_post_thumbnail()) { ?>

			<figure class="entry-thumbnail">
				<a href="<?php esc_url( the_permalink() ); ?>">
					<?php 
					$thumb_size = '';
					if ( $class == 'two-cols' )
						$thumb_size = 'tiga-300px';
					else
						$thumb_size = 'tiga-140px';
					the_post_thumbnail( $thumb_size, array( 'class' => 'photo thumbnail', 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
				</a>
			</figure>

		<?php } ?>

		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div>
		
		<div class="entry-meta">
			<?php tiga_posted_on(); ?>
		</div>
		
	</article><!-- end #article-<?php the_ID(); ?> -->