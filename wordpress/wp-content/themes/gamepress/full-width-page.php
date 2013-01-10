<?php 
/*
Template Name: Full-width
*/

get_header(); 
?>

	<!-- CONTENT -->
		<div id="content" class="full-width">
			<section class="mian-content full-height" role="main">
				<article>
				<?php while ( have_posts() ) : the_post(); ?>
				<header>
					<h2><?php the_title(); ?></h2>
				</header>
				<div class="entry-content">
				
					<?php the_content(); ?>
				
				</div>
				<?php endwhile; ?>
				
				</article>
				
				<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template( '', true );
				?>
				
			</section>			
		<div class="clear"></div>
		</div>
	<!-- END CONTENT -->

<?php get_footer(); ?>