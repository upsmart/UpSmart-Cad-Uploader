<?php
/*
  Template Name: Blog Page
 */
?>
<?php get_header(); ?>  
<div class="heading_container">
  <div class="container_24">
    <div class="grid_24">
      <div class="page-heading">
        <h1><?php the_title(); ?></h1>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<div class="page_container">
  <div class="container_24">
    <div class="grid_24 body_color">
      <div class="page-content">
        <div class="grid_16 alpha">
          <div class="content-bar">
		  <?php
            $limit = get_option('posts_per_page');
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            query_posts('showposts=' . $limit . '&paged=' . $paged);
            $wp_query->is_archive = true;
            $wp_query->is_home = false;
            ?>
          <!-- Start the Loop. -->
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
 <!--post start-->
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
              <h1 class="post_title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
              <div class="post_date">
                <ul class="date">
                  <li class="day"><?php echo get_the_time('d') ?></li>
                  <li class="month"><?php echo get_the_time('F') ?></li>
                  <li class="year"><?php echo get_the_time('Y') ?></li>
                </ul>
              </div>
              <ul class="post_meta">
                <li class="posted_by"><?php _e( 'By', 'poloray' ); ?>&nbsp;&nbsp; <?php the_author_posts_link(); ?></li>
                <li class="post_category">&nbsp;&nbsp;<?php the_category(', '); ?></li>
                <li class="post_comment">&nbsp;&nbsp;<?php comments_popup_link('No Comments.', '1 Comment.', '% Comments.'); ?></li>
              </ul>
              <div class="post_content">
			<?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                         <?php poloray_get_thumbnail(600, 188); ?>
                    <?php } else { ?>
                        <?php poloray_get_image(600, 188); ?> 
                        <?php
                    }
                    ?>
             <?php the_excerpt(); ?>
              <a class="read_more" href="<?php the_permalink() ?>"><?php _e( 'read more', 'poloray' ); ?></a> </div>
            </div>
            <!--End Post-->
			<?php endwhile;
else: ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <p>
            <?php _e('Sorry, no posts matched your criteria.', 'poloray'); ?>
        </p>
    </div>
<?php endif; ?>
<!--End Loop-->
          </div>
        </div>
        <div class="grid_8 omega">
          <!--Start Sidebar-->
            <?php get_sidebar(); ?>
          <!--End Sidebar-->
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<?php get_footer(); ?>