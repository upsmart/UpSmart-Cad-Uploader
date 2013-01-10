<?php
/**
* content-image template used by SpringFestival
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
            <?php comments_popup_link(__('0', 'SpringFestival'),__('1', 'SpringFestival'),__('%', 'SpringFestival'), '',__('Closed' , 'SpringFestival')); ?>
    </div>
    <div class="fr">
      <h2 class="title2"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
        <?php the_title(); ?>
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
  <div class="entry-meta">
    <?php
									$metadata = wp_get_attachment_metadata();
									printf( __( '<span class="meta-prep meta-prep-entry-date">Published </span> <span class="entry-date"><abbr class="published" title="%1$s">%2$s</abbr></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%8$s</a>','SpringFestival') ,
										esc_attr( get_the_time() ),
										get_the_date(),
										esc_url( wp_get_attachment_url() ),
										$metadata['width'],
										$metadata['height'],
										esc_url( get_permalink( $post->post_parent ) ),
										esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
										get_the_title( $post->post_parent )
									);
								?>
  </div>
  <div class="entry">
    <?php
	/**
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
	 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
	 */
	$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
	foreach ( $attachments as $k => $attachment ) {
		if ( $attachment->ID == $post->ID )
			break;
	}
	$k++;
	// If there is more than 1 attachment in a gallery
	if ( count( $attachments ) > 1 ) {
		if ( isset( $attachments[ $k ] ) )
			// get the URL of the next image attachment
			$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
		else
			// or get the URL of the first image attachment
			$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
	} else {
		// or, if there's only 1 image, get the URL of the image
		$next_attachment_url = wp_get_attachment_url();
	}
?>
    <a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php the_title_attribute(); ?>" rel="attachment">
    <?php
									$attachment_size = apply_filters( 'FloatingLight_attachment_size', 848 );
									echo wp_get_attachment_image( $post->ID, array( $attachment_size, 1024 ) ); // filterable image width with 1024px limit for image height.
									?>
    </a>
    <?php if ( ! empty( $post->post_excerpt ) ) : ?>
    <div class="entry-caption">
      <?php the_excerpt(); ?>
    </div>
    <?php endif; ?>
  </div>
</div>
<!-- #post-<?php the_ID(); ?> -->
