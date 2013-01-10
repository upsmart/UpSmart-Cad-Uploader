<?php
/**
* Archive template used by SpringFestival
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


<div id="content">
  <?php if (have_posts()) : ?>
  <h1 class="pagetitle">
    <?php if ( is_day() ) : ?>
    <?php printf( __( 'Daily Archives: <span>%s</span>','SpringFestival'), get_the_date() ); ?>
    <?php elseif ( is_month() ) : ?>
    <?php printf( __( 'Monthly Archives:<span>%s</span>','SpringFestival'), get_the_date('F Y') ); ?>
    <?php elseif ( is_year() ) : ?>
    <?php printf( __( 'Yearly Archives: <span>%s</span>','SpringFestival'),get_the_date('Y')); ?>
    <?php elseif ( is_author() ) : ?>
    <?php printf( __( 'Author Archive','SpringFestival')); ?>
    <?php elseif ( is_category() ) : ?>
    <?php printf( __( 'Category Archive:<span>%s</span>','SpringFestival'), single_cat_title("", false) ); ?>
    <?php elseif ( is_tag() ) : ?>
    <?php printf( __( 'Tag Archive: <span>%s</span>','SpringFestival'),single_tag_title("", false) ); ?>
    <?php else : ?>
    <?php printf( __( 'Blog Archives','SpringFestival') ); ?>
    <?php endif; ?>
  </h1>
  <?php
						get_template_part( 'content', get_post_format() );
					?>
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
