<?php get_header(); ?>

	<!-- CONTENT -->
	<div id="content">
		<section id="main-content" role="main" class="full-height">
			
			<?php while ( have_posts() ) : the_post(); ?>
			
				<?php get_template_part('video','single'); ?>
			
			<?php endwhile; // end of the loop. ?>
			
			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template( '', true );
			?>
		</section>
	</div>
	<!-- END CONTENT -->
		
	<?php get_sidebar(); ?>

<?php get_footer(); ?>