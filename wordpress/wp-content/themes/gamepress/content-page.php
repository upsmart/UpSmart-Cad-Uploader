
<article id="post-<?php the_ID(); ?>" <?php post_class('single'); ?>>
	<header>
		<h2><?php the_title(); ?></h2>
	</header>
	<div class="entry-content">
		<?php the_content(); ?>
		<div class="clear"></div>
		<?php 
			$paged = get_query_var( 'page' ) ? get_query_var( 'page' ) : false;
			if ( $paged === true ) :
		?>
		<hr class="divider-dotted" />
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'gamepress' ), 'after' => '</div>' ) ); ?>
		<?php endif; ?>
    	<?php edit_post_link(__( 'Edit', 'gamepress' ),'<p>', '</p>'); ?>
	</div>
</article>
