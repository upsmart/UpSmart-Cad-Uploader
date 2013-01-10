<?php
/**
* comments template used by SpringFestival
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


<?php if ( post_password_required() ) : ?>
				<p class="nopassword">  <?php _e( 'This post is password protected. Enter the password to view any comments.', 'SpringFestival' ) ?>
</p>
<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php
	// You can start editing here -- including this comment!
?>
<!-- You can start editing here. -->
<?php if ( have_comments() ) : ?>
<h3 class="commenttitle">

  <?php comments_number( __('no responses','SpringFestival'), __('one responses','SpringFestival'), __('% responses','SpringFestival') ); ?>
</h3>

<div id="comments">
  <ol class="commentlist">
    <?php wp_list_comments(); ?>
  </ol>
</div>
<div class="navigation">
  <div class="alignleft">
    <?php previous_comments_link() ?>
  </div>
  <div class="alignright">
    <?php next_comments_link() ?>
  </div>
</div>
<?php else : // this is displayed if there are no comments so far ?>
<?php if ( comments_open() ) : ?>
<!-- If comments are open, but there are no comments. -->

	<?php else : // this is displayed if there are no comments so far ?>
<?php if ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments">  <?php _e( 'Comments are closed.', 'SpringFestival' ) ?>
</p>
	<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php if ( comments_open() ) : ?>

<?php comment_form(); ?>
<?php endif; // if you delete this the sky will fall on your head ?>
