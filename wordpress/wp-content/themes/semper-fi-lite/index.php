<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title('&#124;', true, 'right'); ?><?php bloginfo('name'); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_uri(); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!-- Begin WordPress Header -->
<?php wp_head(); ?>
<!-- End WordPress Header -->

</head>
<body <?php body_class(); ?>>

<!-- The Navigation Menu -->
<ul id="header">

<!-- The Website Title and Slogan -->
<h1 id="fittext3"><a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a><i><?php bloginfo('description');?></i></h1>
<!-- End Website Title and Slogan -->
    
<?php if ( has_nav_menu( 'bar' ) ) :  wp_nav_menu( array( 'theme_location' => 'bar', 'depth' => 2 ) ); else : ?>
<?php wp_list_pages( 'title_li=&depth=2' ); ?>
<?php endif; ?>
</ul>
<!-- End Navigation Menu -->


<!-- Setting up The Layout of the Webpage -->
<div id="centered">
<div id="margin">

<?php $semperfi_404 = false;/* A 404 Error check */?> 
<?php if (is_search()) : ?>

<!-- Searching Code -->
<?php if (have_posts()) : ?>
<ul id="paged">
    <li>
    	<h2>Your Search "<?php echo get_search_query(); ?>" Returned <?php echo $wp_query->found_posts; ?> Results</h2>
    	<p>Your search for "<?php echo get_search_query(); ?>" has returned exactly <?php echo $wp_query->found_posts; ?> results, no more and no less. Hopefully what you're looking for will be found just below on this page, but if you're unable find what you are looking for you may need to use the links "Older Search Results" or "Newer Search Results" to navigate through more pages of results for "<?php echo get_search_query(); ?>." Please keep in mind that if not enough results return, the links for "Older Search Results" and "Newer Search Results" may not appear becuase there is nothing more to show.</p>
    </li>
<?php while (have_posts()) : the_post(); ?>

	<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
		<h2><span><?php the_time('M') ?><br/><?php the_time('jS') ?></span><?php if ( get_the_title() ) { the_title();} else { echo '(No Title)';} ?></h2>
        <?php if ( has_post_thumbnail()) : ?><div class="under"><?php the_post_thumbnail('page-thumbnail'); ?><div class="over"></div></div><?php endif; ?>
        <?php the_excerpt(); ?>
	</a></li>
<?php endwhile; ?>

<?php else : ?>

<ul id="paged">
    <li>
    	<h2>Your search resulted with nothing being found.</h2>
	</li>
</ul>
<?php endif; ?>
<li class="stars"><span class="left"><a href="http://test.schwarttzy.com/page/2/" >&#8249; Older Posts</a></span><span class="right"><a href="http://test.schwarttzy.com/" >Newer Posts &#8250;</a></span></li>
</ul>
<!-- End Search -->
<?php elseif ( have_posts() && is_home() || is_category() || is_day() || is_month() || is_year() || is_paged() || is_tag() ) : ?>

<!-- Paged -->
<ul id="paged">

<?php while (have_posts()) : the_post(); ?>
	<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
		<h2><span><?php the_time('M') ?><br/><?php the_time('jS') ?></span><?php if ( get_the_title() ) { the_title();} else { echo '(No Title)';} ?></h2>
        <?php if ( has_post_thumbnail()) : ?><div class="under"><?php the_post_thumbnail('page-thumbnail'); ?><div class="over"></div></div><?php endif; ?>
        <?php the_excerpt(); ?>
	</a></li>

<?php endwhile; ?>
<?php if ( $wp_query->max_num_pages > 1 ) { ?>
<li class="stars"><span class="left"><?php next_posts_link('&#8249; Older Posts'); ?></span><span class="right"><?php previous_posts_link('Newer Search Results &#8250;'); ?></span></li>
<?php } else { ?>
<li class="stars"></li>
<?php } ?>

</ul>
<!-- End Many Things -->
<?php elseif ( is_404() ) : ?>

<!-- 404 Error -->
<ul id="comments">
    <li>
    	<h2>Code Error 404</h2>
        <img src="<?php echo get_template_directory_uri(); ?>/images/error404.jpg" class="attachment-page-thumbnail wp-post-image">
		<p>Echo Bravo Eight Two Four Five. This is Three Two One One Romeo. Be advised. We're recieving a coding error 404. Repeat, We're recieving a coding error 404. You are to be extracked at the rondevu point at <?php echo date('H') ?>57 Hundred Hours. Over and out.</p>
	</li>
    
	<li class="comments">
    <h4 class="title">Comments</h4>
    
    <ol class="commentlist">
		<li class="comment even thread-even">
				<div class="comment-body">
                    <div class="comment-author vcard">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/gary.jpg" class="avatar avatar-100 photo grav-hashed grav-hijack" height="100" width="100"> <cite class="fn">Gary</cite> <span class="says">says:</span>
                    </div>
    				<p>"Well, it appears that link fed us bad intell. We are to be extracked at the rondevu point in <?php echo date('H') ?>00 Hundred Hours which doesn't give us much time, but we should manage just fine."</p>
					</div>
		</li>
		<li class="comment odd alt">
				<div class="comment-body">
                    <div class="comment-author vcard">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/mike.jpg" class="avatar avatar-100 photo grav-hashed grav-hijack" height="100" width="100"> <cite class="fn">Mike</cite> <span class="says">says:</span>
                    </div>
    				<p>Just great! So tired of humping it through these data files. And for what!? Nothing. Absolutely nothing...</p>
					</div>
		</li>
        <li class="comment even thread-even">
				<div class="comment-body">
                    <div class="comment-author vcard">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/gary.jpg" class="avatar avatar-100 photo grav-hashed grav-hijack" height="100" width="100"> <cite class="fn">Gary</cite> <span class="says">says:</span>
                    </div>
    				<p>I know. You think they would spend more time checking the links. For cry eye, everything is on the inter-webs. It's not like this is something new. Hell, the fact that it is an 404 error means it's most likely old in the first place.</p>
					</div>
		</li>
		<li class="comment odd alt">
				<div class="comment-body">
                    <div class="comment-author vcard">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/mike.jpg" class="avatar avatar-100 photo grav-hashed grav-hijack" height="100" width="100"> <cite class="fn">Mike</cite> <span class="says">says:</span>
                    </div>
    				<p>Well, on the brightside, we don't have to keep searching any more.</p>
					</div>
		</li>
        <li class="comment even thread-even">
				<div class="comment-body">
                    <div class="comment-author vcard">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/gary.jpg" class="avatar avatar-100 photo grav-hashed grav-hijack" height="100" width="100"> <cite class="fn">Gary</cite> <span class="says">says:</span>
                    </div>
    				<p>Ya, that is nice.</p>
					</div>
		</li>
        <li class="comment odd alt">
				<div class="comment-body">
                    <div class="comment-author vcard">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/blackhawk.jpg" class="avatar avatar-100 photo grav-hashed grav-hijack" height="100" width="100"> <cite class="fn">Blackhawk Helicopter</cite> <span class="says">says:</span>
                    </div>
    				<p>Thump. Thump. Thump.</p>
					</div>
		</li>
        <li class="comment even thread-even">
				<div class="comment-body">
                    <div class="comment-author vcard">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/mike.jpg" class="avatar avatar-100 photo grav-hashed grav-hijack" height="100" width="100"> <cite class="fn">Mike</cite> <span class="says">says:</span>
                    </div>
    				<p>O Hell Ya! Do you hear that!</p>
					</div>
		</li>
        <li class="comment odd alt">
				<div class="comment-body">
                    <div class="comment-author vcard">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/pilot.jpg" class="avatar avatar-100 photo grav-hashed grav-hijack" height="100" width="100"> <cite class="fn">Gary</cite> <span class="says">says:</span>
                    </div>
    				<p>Come in Three Two One One Romeo.  Come in Three Two One One Romeo. Light that smoke. Repeat. Light that smoke.</p>
					</div>
		</li>
        <li class="comment even thread-even">
				<div class="comment-body">
                    <div class="comment-author vcard">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/gary.jpg" class="avatar avatar-100 photo grav-hashed grav-hijack" height="100" width="100"> <cite class="fn">Gary</cite> <span class="says">says:</span>
                    </div>
    				<p>Smoke is lit.</p>
					</div>
		</li>
        <li class="comment odd alt">
				<div class="comment-body">
                    <div class="comment-author vcard">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/pilot.jpg" class="avatar avatar-100 photo grav-hashed grav-hijack" height="100" width="100"> <cite class="fn">Gary</cite> <span class="says">says:</span>
                    </div>
    				<p>Roger, Roger. Prepair for extraction.</p>
					</div>
		</li>
        <li class="comment even thread-even">
        <div class="comment-body">
		<p>Hurry up and click the <a href="<?php echo home_url(); ?>/">Extraction Point</a>.</p>
        </div>
		</li>
	</ol>
    </li>
        <li class="stars"></li>
</ul>
<!-- End 404 Error -->
<?php $semperfi_404 = true;?>

<?php /* Some of the Formating is weird below so that when the page is finished the formating looks beautiful and clean */ ?>
<?php elseif (have_posts()) : the_post();?>
<ul id="single">
    <li id="post-<?php the_ID(); ?>" <?php post_class('content'); ?>>
    	<h2><?php if ( is_page()) : else : ?><span><?php the_time('M') ?><br/><?php the_time('jS') ?></span><?php endif; ?><?php if ( get_the_title() ) { the_title();} else { echo '(No Title)';} ?></h2>
		<?php the_content(); ?>
    	<span class="pagesandtags"><?php wp_link_pages(); ?>
		<?php if (is_single()) : ?>Post Categories: <?php the_category(', '); the_tags('</br>Tags: ', ', ', ''); endif;?><?php if ( is_single($post) && !comments_open() ) : ?> Commenting is closed.<?php endif; ?>
        </span>
    </li>
    
<?php if ( is_single() ) : ?>
        <li class="stars"><span class="left"><?php previous_post_link('%link', '&#8249; %title'); ?></span><span class="right"><?php next_post_link('%link', '%title &#8250;'); ?></span></li>
<?php elseif ( is_attachment() ) : ?>
        <li class="stars"><span class="left"><?php previous_image_link( false, '&#8249; Previous Image'); ?></span><span class="right"><?php next_image_link( false, 'Next Image &#8250;'); ?></span></li>
<?php endif; ?>
</ul>
<?php endif; ?><!-- End of Post / Page Stuff -->

<!-- The Comments -->
<?php if (is_search()) : ?>
<?php elseif($semperfi_404) : ?>
<?php elseif ( comments_open() ) : ?>
<?php comments_template( '', true ); ?>
<?php elseif ( ( is_page() && comments_open() ) || is_single() ) : ?>
<?php comments_template( '', true ); ?>
<?php endif; ?>
<!-- End Comments -->

<!-- The Sidebar / Widget Area -->
<?php if (semperfi_is_sidebar_active('widget')) : ?><ul id="paged" class="widget">
<li><?php if (!dynamic_sidebar('footer widget1')) : ?><?php endif; ?></li>
<li><?php if (!dynamic_sidebar('footer widget2')) : ?><?php endif; ?></li>
<li><?php if (!dynamic_sidebar('footer widget3')) : ?><?php endif; ?></li>
<li class="bottomline"></li>
</ul>
<?php endif; ?>
<!-- End Sidebar / Widget Area -->

</div>
</div>
<!-- Closing the Layout of the Page with a Finishing Touch. -->

<div id="footer">
<p>Good Old Fashioned Hand Written Code by <a href="http://schwarttzy.com/about-2/">Eric J. Schwarz</a> <!-- <?php echo get_num_queries(); ?> queries in <?php timer_stop(1); ?> seconds --></p>
</div>

<!-- Start of WordPress Footer  -->
<?php wp_footer(); ?>
<!-- End of WordPress Footer -->

</body>
</html>