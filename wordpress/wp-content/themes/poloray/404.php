<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 */
?>
<?php get_header(); ?>  
<div class="heading_container">
  <div class="container_24">
    <div class="grid_24">
      <div class="page-heading">
        <h1 class="entry-title">
                    <?php _e('This is somewhat embarrassing, isn&rsquo;t it?', 'poloray'); ?>
         </h1>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<div class="page_container">
  <div class="container_24">
    <div class="grid_24">
      <div class="page-content">
        <div class="grid_16 alpha">
          <div class="content-bar">
                <?php the_content(); ?>	
				      <header class="entry-header">
            <p>
                <?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', 'poloray'); ?>
            </p>
            <?php get_search_form(); ?>
            <?php the_widget('WP_Widget_Recent_Posts', array('number' => 10), array('widget_id' => '404')); ?>
            <div class="widget">
                <h2 class="widgettitle">
                    <?php _e('Most Used Categories', 'poloray'); ?>
                </h2>
                <ul>
                    <?php wp_list_categories(array('orderby' => 'count', 'order' => 'DESC', 'show_count' => 1, 'title_li' => '', 'number' => 10)); ?>
                </ul>
            </div>
            <?php
            /* translators: %1$s: smilie */
            $archive_content = '<p>' . sprintf(__('Try looking in the monthly archives. %1$s', 'poloray'), convert_smilies(':)')) . '</p>';
            the_widget('WP_Widget_Archives', array('count' => 0, 'dropdown' => 1), array('after_title' => '</h2>' . $archive_content));
            ?>
            <?php the_widget('WP_Widget_Tag_Cloud'); ?>
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