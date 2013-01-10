<?php 
/**
* Header template used by the CyberChimps Response Core Framework
*
* Authors: Tyler Cunningham, Trent Lapinski
* Copyright: © 2012
* {@link http://cyberchimps.com/ CyberChimps LLC}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package Response
* @since 1.0
*/

	global $options, $ir_themeslug, $ir_themename; // call globals

?>
	<?php response_head_tag(); ?>
<!-- End @response head_tag hook content-->

<?php wp_head(); ?> <!-- wp_head();-->
	
</head><!-- closing head tag-->

<!-- Adding wrapper class for sticky footer -->
<div class="wrapper">

<div class="iribbon-content-margin"><!-- creates container for whole site and creates left right margin -->
<!-- Begin @response after_head_tag hook content-->
	<?php response_after_head_tag(); ?>
<!-- End @response after_head_tag hook content-->
	
<!-- Begin @response before_header hook  content-->
	<?php response_before_header(); ?> 
<!-- End @response before_header hook content -->
			
<header>		
	<div class="container-fluid">
	<?php
		foreach(explode(",", $options->get('header_section_order')) as $fn) {
			if(function_exists($fn)) {
				call_user_func_array($fn, array());
			}
		}
	?>
	</div>	
</header>

<!-- Begin @response after_header hook -->
	<?php response_after_header(); ?> 
<!-- End @response after_header hook -->
