<?php
/**
* footer template used by SpringFestival
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
<div class="cb"></div>
</div>
<div id="FooterShadow"></div>
<div id="footer">


<?php _e('Theme by ','SpringFestival');?><a href="<?php echo esc_url(__('http://www.wpart.org','SpringFestival')); ?>" title="<?php esc_attr_e('wpart', 'SpringFestival'); ?>" class="red" ><?php printf('wpart'); ?></a> <br>
   <?php _e('Powered by','SpringFestival');?> <a href="<?php echo esc_url(__('http://wordpress.org','SpringFestival')); ?>" title="<?php esc_attr_e('WordPress', 'SpringFestival'); ?>" class="red" ><?php printf('WordPress'); ?></a> 
  </div>
<?php wp_footer(); ?>
</body></html>