<?php
/**
* searchform template used by SpringFestival
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
<div>
<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
 <label for="s" class="assistive-text"><?php _e( 'Search', 'SpringFestival' ); ?></label>
  <input type="text" class="field" name="s" id="s" size="12"  placeholder="<?php esc_attr_e( 'Search Here', 'SpringFestival' ); ?>" />
  <input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'GO', 'SpringFestival' ); ?>" />
</form>
</div>