<?php
/*
Template Name: upSmartHome
Author: Aaron Tobias
*/

global $wpdb;
$table_name = $wpdb->prefix . "homeVideos"; 
$videos = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "homeVideos" );
$num_entries = count($videos);
$rnVideo = rand(0, $num_entries-1);
?>

<?php get_header(); ?>
	<div id="content">
		<div class="padder">
		        <h3 class="pagetitle llh upSmartgradient hshadow"> UpSmart Companies </h3>
		        <h3 class="pagetitle rrh upSmartgradient hshadow"> Investment Countdown </h3>
			<div id="rvideo">

                          <?php
                                echo do_shortcode('[wp_cpl_sc cat_id= "30"]');
                                ?>


			</div>
			
			
			<div class="ffi_widget">
			
			<?php echo do_shortcode('[cc_widget id="1"]'); ?>
			
			</div>
			<h3 class="pagetitle rrh upSmartgradient hshadow"> Company Blogs </h3>
			<div class="ffh_widget">  

                        <?php
                      	echo do_shortcode('[wp_cpl_sc cat_id= "34" list_num=2]');
	                        ?>

			</div>
			
			

		</div><!-- .padder -->
	</div><!-- #content -->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>
