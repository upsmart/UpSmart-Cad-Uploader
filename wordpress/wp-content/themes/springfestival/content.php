<?php
/**
* content template used by SpringFestival
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
 
<?php while ( have_posts() ) : the_post(); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="posttitle">
    <div class="fl">
            <?php comments_popup_link(__('0', 'SpringFestival'),__('1', 'SpringFestival'),__('%', 'SpringFestival'), '',__('Closed' , 'SpringFestival')); ?>
    </div>
    <div class="fr">
      <h2 class="title2"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
        <?php if(the_title( '', '', false ) !='') the_title(); else echo 'Untitled';?>
        </a> </h2>
      <?php the_time('F jS, Y') ?>
      /
           <?php the_tags( __( 'Tags:', 'SpringFestival' ), ' , ' , ''); ?>

      /
      <?php _e(' categories: ','SpringFestival' );the_category(', '); ?>
      /
      <?php edit_post_link( __( 'Edit', 'SpringFestival' ), '', ''); ?>
    </div>
  </div>
  <div class="entry">
    <?php if ( has_post_thumbnail()) : ?>
    <a href="<?php the_permalink(); ?>" class="alignleft thumbnailimg">
    <?php the_post_thumbnail(); ?>
    </a>
    <?php endif; ?>
     <?php  if ( has_post_format( 'gallery' )) { ?>
      <a href="<?php echo get_post_format_link( 'gallery' ); ?>" title="<?php _e('View Galleries','SpringFestival');?>">
      <?php _e('More Galleries','SpringFestival');?>
      </a>
      <?php }?>
      
    <?php the_content(); ?>
             <?php wp_link_pages(array('before' => '<div class="page-link"><strong>'. __( 'Pages:', 'SpringFestival' ) .'</strong> ', 'after' => '</div>', 'next_or_number' => 'number')); ?>

  </div>
</div>
<?php endwhile; ?>
