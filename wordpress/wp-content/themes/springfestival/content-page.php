<?php
/**
* content-page template used by SpringFestival
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
 ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="posttitle">
    <div class="fl">
            <?php comments_popup_link(__('0', 'SpringFestival'),__('1', 'SpringFestival'),__('%', 'SpringFestival'), '','');?>
    </div>
    <div class="fr">
      <h2 class="title2"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
        <?php the_title(); ?>
        </a> </h2>
      <?php the_time('F jS, Y') ?>
      /
      <?php edit_post_link( __( 'Edit', 'SpringFestival' ), '', ''); ?>
    </div>
  </div>
  <div class="entry">
    <?php the_content(); ?>
             <?php wp_link_pages(array('before' => '<div class="page-link"><strong>'. __( 'Pages:', 'SpringFestival' ) .'</strong> ', 'after' => '</div>', 'next_or_number' => 'number')); ?>


  </div>
</div>
