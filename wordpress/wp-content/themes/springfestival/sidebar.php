<?php
/**
* sidebar template used by SpringFestival
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

<div id="sidebar">
  <ul>
    <?php if (is_active_sidebar( 'sidebar' )) : ?>
    <?php dynamic_sidebar( 'sidebar' ); ?>
    <?php else : ?>
    <li>
      <h3 class="title3">
        <?php _e( 'pages', 'SpringFestival' ) ?>
      </h3>
      <ul>
        <?php wp_list_pages('sort_column=menu_order&depth=1&title_li='); ?>
      </ul>
    </li>
    <li>
      <h3 class="title3">
        <?php _e( 'categories', 'SpringFestival' ) ?>
      </h3>
      <ul>
        <?php wp_list_categories('title_li='); ?>
      </ul>
    </li>
    <li>
      <h3 class="title3">
        <?php _e( 'archives', 'SpringFestival' ) ?>
      </h3>
      <ul>
        <?php wp_get_archives('type=monthly');?>
      </ul>
    </li>
    <li>
      <h3 class="title3">
        <?php _e( 'links', 'SpringFestival' ) ?>
      </h3>
      <ul>
        <?php wp_list_bookmarks('title_li=&categorize=0'); ?>
      </ul>
    </li>
    <li>
      <h3 class="title3">
        <?php _e( 'meta', 'SpringFestival' ) ?>
      </h3>
      <ul>
        <?php wp_register(); ?>
        <li>
          <?php wp_loginout(); ?>
        </li>
        <?php wp_meta(); ?>
      </ul>
    </li>
    <?php endif; ?>
  </ul>
</div>
