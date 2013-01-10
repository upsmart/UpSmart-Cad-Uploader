<?php 
/* Design Theme's Search Page
	Copyright: 2012, D5 Creation, www.d5creation.com
	Based on the Simplest D5 Framework for WordPress
	Since Design 1.0
*/

get_header(); ?>
<div class="pagenev"><div class="conwidth"><?php design_breadcrumbs(); ?></div></div>
<div id="container">
		<?php if (have_posts()) : ?>
		<div id="content">
        <h1 class="arc-post-title">Search Results</h1>
		
		<?php $counter = 0; ?>
		<?php query_posts($query_string . "&posts_per_page=20"); ?>
		<?php while (have_posts()) : the_post();
			if($counter == 0) {
				$numposts = $wp_query->found_posts; // count # of search results ?>
				<h3 class="arc-src"><span>Search Term: </span><?php the_search_query(); ?></h3>
				<h3 class="arc-src"><span>Number of Results: </span><?php echo $numposts; ?></h3> <div class="content-ver-sep"></div><br />
				<?php } //endif ?>
			
				<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h2>
 <span class="postmetadata"><h3><?php the_time('F j, Y'); ?></h3><div class="content-ver-sep"> </div><h2>By: <?php the_author_posts_link() ?></h2><h5><?php comments_popup_link('No Comments Yet&#187;', '1 Comment &#187;', '% Comments &#187;'); ?></h5>Posted in <?php the_category(', ') ?><?php the_tags('<br />Tags: ', ', ', ''); ?><br /><h5><?php edit_post_link('Edit This Post'); ?></h5></span>	
 <div class="entrytext"><div class="thumb"><?php the_post_thumbnail(); ?></div>
 <?php the_excerpt(); ?>
 <div class="clear"> </div>
 </div></div>
 <div class="content-ver-sep"></div><br />
				
		<?php $counter++; ?>
 		
		<?php endwhile; ?>
		</div>		
		<?php get_sidebar(); ?>
        <?php else: ?>
		<br /><br /><h1 class="arc-post-title">Sorry, we couldn't find anything that matched your search.</h1>
		
		<h3 class="arc-src"><span>You Can Try Another Search...</span></h3>
		<?php get_search_form(); ?><br />
		<p><a href="<?php echo home_url(); ?>" title="Browse the Home Page">&laquo; Or Return to the Home Page</a></p><br />
		<h2 class="post-title-color">You can also Visit the Following. These are the Featured Contents</h2>
		<div class="content-ver-sep"></div><br />
		<?php get_template_part( 'featured-box' ); ?>

	<?php endif; ?>
	
<?php get_footer(); ?>