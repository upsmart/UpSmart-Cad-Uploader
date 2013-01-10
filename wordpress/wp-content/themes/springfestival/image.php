<?php
/**
* image template used by SpringFestival
*
* Authors: wpart
* Copyright: 2012
* {@link http://www.wpart.org/}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package SpringFestival
* @since 1.4
*/
 get_header(); ?>

<div id="content" class="homepage">
  <?php the_post(); ?>
  <?php get_template_part( 'content', 'image' ); ?>
  <div class="navigation">
    <div class="alignleft">
      <?php previous_image_link( false, __('&lt;&lt; Previous','SpringFestival' )); ?>
    </div>
    <div class="alignright">
      <?php next_image_link( false,  __('Next &gt;&gt;','SpringFestival')); ?>
    </div>
  </div>
  <?php comments_template( '', true ); ?>
</div>
<?php get_sidebar(); ?>
<!-- /content -->
<?php get_footer(); ?>
