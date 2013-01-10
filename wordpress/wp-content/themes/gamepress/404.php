<?php get_header(); ?>

	<!-- CONTENT -->
		<div id="content" class="full-width  p404">
			<section class="mian-content full-height" role="main">
				<article id="post-0" class="post error404 not-found">
				<div class="entry-content">
					<h1><?php _e('404 Error!','gamepress'); ?></h1>
					<p>
						<?php _e('The page You requested was not found. Perhaps searching can help.', 'gamepress'); ?>
					</p>
					
					<?php get_search_form(); ?>

					<p><a href="<?php echo home_url(); ?>"><?php _e('Home Page','gamepress'); ?></a></p>

				</div>
				</article>
			</section>			
		<div class="clear"></div>
		</div>
	<!-- END CONTENT -->

<?php get_footer(); ?>