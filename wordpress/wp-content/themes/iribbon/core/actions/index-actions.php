<?php
/**
* Index actions used by the CyberChimps Response Core Framework
*
* Author: Tyler Cunningham
* Copyright: © 2012
* {@link http://cyberchimps.com/ CyberChimps LLC}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package Response
* @since 1.0
*/

/**
* Response index actions
*/

add_action( 'response_index', 'response_index_content');

/**
* Index content
*
* @since 1.0
*/
function response_index_content() { 

	global $options, $ir_themeslug, $post, $sidebar, $content_grid; // call globals ?>
	
	<!--Begin @response sidebar init-->
		<?php response_sidebar_init(); ?>
	<!--End @response sidebar init-->
	<div class="row-fluid">
<!--Begin @response before content sidebar hook-->
		<?php response_before_content_sidebar(); ?>
	<!--End @response before content sidebar hook-->

		<div id="content" class="<?php echo $content_grid; ?>">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="post_outer_container">
      
				<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
		
				<!--Begin @response index loop hook-->
					<?php response_loop(); ?>
				<!--End @response index loop hook-->	

				</div><!--end post_class-->
			</div><!-- end post outer container -->
			<?php if (is_single() && $options->get($ir_themeslug.'_post_pagination') == "1") : ?>
			<nav id="post_pagination"><div class="pagination_text">
				<!--Begin @response post pagination hook-->
					<?php response_post_pagination(); ?>
				<!--End @response post pagination hook-->	
			</div></nav>		
				<?php endif;?>

			
			<?php if (is_single()):?>
			<?php comments_template(); ?>
			<?php endif ?>
			
	
			<?php endwhile; ?>
		
			<?php else : ?>

				<h2>Not Found</h2>

			<?php endif; ?>
			
				<!--Begin @response pagination hook-->
			<?php response_pagination(); ?>
			<!--End @response pagination loop hook-->
		
		</div><!--end content-->

	<!--Begin @response after content sidebar hook-->
		<?php response_after_content_sidebar(); ?>
	<!--End @response after content sidebar hook-->

</div><!-- row fluid -->
<?php }

/**
* End
*/

?>