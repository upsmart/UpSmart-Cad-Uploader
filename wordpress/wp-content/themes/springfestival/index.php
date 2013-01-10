<?php
/**
* index template used by SpringFestival
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
  <?php if ( have_posts() ) : ?>
  <?php get_template_part( 'content', get_post_format() ); ?>
  <div class="navigation">
    <div class="alignleft">
      <?php next_posts_link(__( '<span class="small">&lt;&lt;</span> Older Entries', 'SpringFestival' )) ?>
    </div>
    <div class="alignright">
      <?php previous_posts_link(__( 'Newer Entries  <span class="small">&gt;&gt;</span>', 'SpringFestival' )) ?>
    </div>
  </div>
  <?php else : ?>
  <h2 class="title2">
      <?php _e('Not Found','SpringFestival');?>
</h2>
  <p class="aligncenter">    <?php _e('Sorry, but you are looking for something that is not here.','SpringFestival');?>
</p>
  <?php get_search_form(); ?>
  <?php endif; ?>
</div>
<?php get_sidebar(); ?>
<!-- /content -->
<?php get_footer(); ?>
