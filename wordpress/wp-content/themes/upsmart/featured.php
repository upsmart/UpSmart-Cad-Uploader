<?php
/*
Template Name: UpSmart Home
*/

get_header("featured"); ?>
<section id='featured' style='background: transparent; padding: 0;'>
	<div id='featured-login' class='featured-2col'><div>
		
		<?php if(is_user_logged_in()): $user = wp_get_current_user(); ?>
			<h2 style='position: absolute; bottom: 0; color: #FFF;'>Welcome back, <?php echo $user->display_name; ?>.</h2>
		<?php else: ?>
			<?php wp_login_form(); ?>
		<?php endif; ?>
	</div></div>
	<div class='featured-2col featured-1row featured-content'><div>
		<?php if (have_posts()) : while (have_posts()) : the_post();?>
			<h2><?php the_title();?></h2>
			<?php the_content(); ?>
		<?php endwhile; endif; ?>
	</div></div>
	<div class='featured-1col featured-1row featured-bottomrow'><div style='position: relative; background: url("http://flickholdr.com/600/200/space")'>
		<h2 style='position: absolute; bottom: 0; color: #FFF;'>Featured Site 2</h2>
	</div></div>
	<div class='featured-1col featured-1row featured-bottomrow'><div style='position: relative; background: url("http://flickholdr.com/600/200/grass")'>
		<h2 style='position: absolute; bottom: 0; color: #FFF;'>Featured Site 3</h2>
	</div></div>
	<br style='clear: both'/>
</section>
<div id='bottom'>
	<div class="widget-area"><div>
		<?php dynamic_sidebar( 'sidebar-3' ); ?>
	</div></div>
	<div class="widget-area"><div>
		<?php dynamic_sidebar( 'sidebar-4' ); ?>
	</div></div>
	<div class="widget-area"><div>
		<?php dynamic_sidebar( 'sidebar-5' ); ?>
	</div></div>
</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
	//alert(typeof($));
	(function($) {
		$("#bottom .widget-area").hover(function() {
			
			nh = this.offsetTop-$("#featured").children()[0].offsetTop;
			$(this).css('margin-top',-1*nh);
			$(this).addClass('selected');
		},
		function() {
			$(this).css('margin-top',0);
			$(this).removeClass('selected');
		});
		
	})(jQuery);
</script>
<?php get_footer("featured"); ?>