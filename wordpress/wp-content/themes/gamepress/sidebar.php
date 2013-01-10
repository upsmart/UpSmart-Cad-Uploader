	<!-- SIDEBAR -->
	<aside id="sidebar" role="complementary">
	
		<?php do_action( 'before_sidebar' ); ?>
		<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

		<div id="archives" class="widget">
			<h3 class="widget-title"><?php _e( 'Archives', 'gamepress' ); ?></h3>
			<ul class="list arrow">
				<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
			</ul>
		</div>

		<div id="meta" class="widget">
			<h3 class="widget-title"><?php _e( 'Meta', 'gamepress' ); ?></h3>
			<ul class="list arrow">
				<?php wp_register(); ?>
				<?php wp_loginout(); ?>
				<?php wp_meta(); ?>
			</ul>
		</div>

	<?php endif; // end sidebar widget area ?>
		
	</aside>
	<!-- END SIDEBAR -->
	<div class="clear"></div>