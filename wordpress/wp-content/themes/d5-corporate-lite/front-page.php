<?php
/*
	Template Name: Front Page
	d5corporate Theme's Front Page to Display the Home Page if Selected
	Copyright: 2012, D5 Creation, www.d5creation.com
	Based on the Simplest D5 Framework for WordPress
	Since D5 CORPORATE 1.0
*/
?>

<?php get_header(); ?>
<div id="slide-container">
<img src="<?php echo of_get_option('banner-image', get_template_directory_uri() . '/images/slide-image/slide-image1.jpg'); ?>"/>
</div> <!-- slide-container -->


<h1 id="heading"><?php echo of_get_option('heading_text', 'World class and industry standard IT services are our passion. We build your ideas True'); ?></h1>
<?php get_template_part( 'featured-box' ); ?> 


<?php if ( of_get_option('bottom-quotation', 'All the developers of D5 Creation have come from the disadvantaged part or group of the society. All have established themselves after a long and hard struggle in their life ----- D5 Creation Team') != '' ) : ?>
<div class="content-ver-sep"></div>
<div id="customers-comment">
<blockquote><?php echo of_get_option('bottom-quotation', 'All the developers of D5 Creation have come from the disadvantaged part or group of the society. All have established themselves after a long and hard struggle in their life ----- D5 Creation Team'); ?></blockquote>
</div>
<?php endif; ?>

<?php if (of_get_option('lpost') == '1') :  
	echo '<div class="textcenter"><h2 class="post-title-color">: Latest Post : </h2><h3 class="lpost">';
	$args = array( 'numberposts' => 10 );
	$recent_posts = wp_get_recent_posts( $args );
	foreach( $recent_posts as $recent ){
		echo '|&nbsp &nbsp <a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].'</a> &nbsp &nbsp | &nbsp &nbsp';
	} echo '</h3></div>';
	endif;?>

<?php get_footer(); ?>