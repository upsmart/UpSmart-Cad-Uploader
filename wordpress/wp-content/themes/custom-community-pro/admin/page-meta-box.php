<?php 
	function cc_page_metabox(){ 	
		global $post;
		$args = array('echo' => '0','hide_empty' => '0');
		$categories = get_categories($args);
		$option = Array();
		$option[0] = Array (
	            'name' => 'All categories',
	            'slug' => 'all-categories'
	        );
		$i = 1;
		foreach($categories as $category) {
			$option[$i]['name'] = $category->name;
			$option[$i]['slug'] = $category->slug;
			$i++;
		}
	
		$option_categories = $option;    
    	
    	$cc_page_options=cc_get_page_meta();

		if($cc_page_options['cc_page_slider_on'] == 1){
			$checked_slider = "checked";
		} else {
			$checked_slider = "";
		}
		
		if($cc_page_options['cc_page_slider_caption'] == 1){
			$checked_caption = "checked";
		} else {
			$checked_caption = "";
		}
		
		$cc_page_slider_time = $cc_page_options['cc_page_slider_time'];
		$cc_page_slider_orderby = $cc_page_options['cc_page_slider_orderby'];	
		$cc_page_slider_amount = $cc_page_options['cc_page_slider_amount'];	
		$cc_page_slider_post_type = $cc_page_options['cc_page_slider_post_type'];	
		$cc_page_slider_show_page = $cc_page_options['cc_page_slider_show_page'];	
		
		if($cc_page_options['cc_page_template_on'] == 1){
			$checked_page_template = "checked";
		} else {
			$checked_page_template = "";
		}
		
		$cc_page_template_amount = $cc_page_options['cc_page_template_amount'];
			
		$option_post_templates[0] = "img-mouse-over";
		$option_post_templates[1] = "img-left-content-right";
		$option_post_templates[2] = "img-right-content-left";
		$option_post_templates[3] = "img-over-content";
		$option_post_templates[4] = "img-under-content";
		
			
		$option_styles[0] = "default";
		$option_styles[1] = "full-width-image";
		?>
		
	<style type="text/css">
	#cc_page_template_amount{
		width:40px;
	}
	</style>
	<div id="cc_page_metabox" class="postbox">
		<div class="handlediv" title="<?php _e('klick','buddypress'); ?>">
			<br />
		</div>
		<h3 class="hndle"><?php _e('Custom Community settings')?></h3>
		<div class="inside">
		<p>
			<b>Slideshow</b><br />
			<label for="cc_page_slider"><?php _e('Slideshow on')?>:</label>
			<input name="cc_page_slider_on" id="cc_page_slider_on" type="checkbox" <?php echo $checked_slider ?> value="1" />
			Select a category to display in slideshow: <select id="cc_page_slider_cat" name="cc_page_slider_cat">
					<?php foreach($option_categories as $option){?>
						<option <?php if ( trim($cc_page_options['cc_page_slider_cat']) == $option['slug'] ) { echo ' selected="selected"'; } ?> value="<?php echo $option['slug'] ?>"><?php echo $option['name']; ?></option>
					<?php }?>
			</select><br />
			<label for="cc_page_slider_post_type"><?php _e('Use Post Type: for Pages write "page"')?>:</label>
			<input type="text" name="cc_page_slider_post_type" id="cc_page_slider_post_type" value="<?php echo $cc_page_slider_post_type; ?>" />
			<label for="cc_page_slider_show_page"><?php _e('post/page ids comma separated')?>:</label>
			<input type="text" name="cc_page_slider_show_page" id="cc_page_slider_show_page" value="<?php echo $cc_page_slider_show_page; ?>" />
			<br />Select a slideshow style: <select id="cc_page_slider_style" name="cc_page_slider_style">
					<?php foreach($option_styles as $option_style){?>
						<option <?php if(trim($cc_page_options['cc_page_slider_style']) == trim($option_style)){?>selected="selected"<?php }?>><?php echo $option_style; ?></option>
					<?php }?>
			</select>
			<br /><label for="cc_page_slider_caption"><?php _e('Caption off')?>:</label>
			<input name="cc_page_slider_caption" id="cc_page_slider_caption" type="checkbox" <?php echo $checked_caption ?> value="1" />
			<br /><label for="cc_page_slider_time"><?php _e('Define the sliding time in ms')?>:</label>
			<input type="text" name="cc_page_slider_time" id="cc_page_slider_time" value="<?php echo $cc_page_slider_time; ?>" />
			<label for="cc_page_slider_orderby"><?php _e('Order By')?>:</label>
			<input type="text" name="cc_page_slider_orderby" id="cc_page_slider_orderby" value="<?php echo $cc_page_slider_orderby; ?>" />
			<label for="cc_page_slider_amount"><?php _e('Amount')?>:</label>
			<input type="text" name="cc_page_slider_amount" id="cc_page_slider_amount" value="<?php echo $cc_page_slider_amount; ?>" />
		
			<br /><br /><b>Lists Posts under this Page</b>
			<p>You can show your posts in a predefined template:<br />
			<label for="cc_page_template"><?php _e('Post template on')?>:</label>
			<input name="cc_page_template_on" id="cc_page_template_on" type="checkbox" <?php echo $checked_page_template ?> value="1" />
			Select a template to use: <select id="cc_posts_on_page_type" name="cc_posts_on_page_type">
					<?php foreach($option_post_templates as $option_template){?>
						<option <?php if($cc_page_options['cc_posts_on_page_type'] == $option_template){?>selected="selected"<?php }?>><?php echo $option_template; ?></option>
					<?php }?>
			</select>
			Select a category to display: <select id="cc_page_template_cat" name="cc_page_template_cat">
					<?php foreach($option_categories as $option){?>
						<option <?php if ( trim($cc_page_options['cc_page_template_cat']) == $option['slug'] ) { echo ' selected="selected"'; } ?> value="<?php echo $option['slug'] ?>"><?php echo $option['name']; ?></option>
					<?php }?>
			</select>
				How many posts to display? <input type="text" name="cc_page_template_amount" id="cc_page_template_amount" value="<?php echo $cc_page_template_amount; ?>" />
			</p>
		</p>
		</div>	
	</div>
<?php
 }
 
function cc_page_meta_add($id){
	if ($_POST['cc_page_slider_on'] == "1") {
	 	update_post_meta($id,"_cc_page_slider_on",1);
	}else{
	 	update_post_meta($id,"_cc_page_slider_on",0);
	}
	if (isset($_POST['cc_page_slider_cat']) === true) {
	    update_post_meta($id,"_cc_page_slider_cat",$_POST["cc_page_slider_cat"]);
	}
	if ($_POST['cc_page_template_on'] == "1") {
	 	update_post_meta($id,"_cc_page_template_on",1);
	}else{
	 	update_post_meta($id,"_cc_page_template_on",0);
	}
	if (isset($_POST['cc_page_template_cat']) === true) {
	    update_post_meta($id,"_cc_page_template_cat",$_POST["cc_page_template_cat"]);
	}
	if (isset($_POST['cc_page_template_amount']) === true) {
	    update_post_meta($id,"_cc_page_template_amount",$_POST["cc_page_template_amount"]);
	}
	if (isset($_POST['cc_page_slider_time']) === true) {
	    update_post_meta($id,"_cc_page_slider_time",$_POST["cc_page_slider_time"]);
	}
	if (isset($_POST['cc_page_slider_orderby']) === true) {
	    update_post_meta($id,"_cc_page_slider_orderby",$_POST["cc_page_slider_orderby"]);
	}
	if (isset($_POST['cc_page_slider_amount']) === true) {
	    update_post_meta($id,"_cc_page_slider_amount",$_POST["cc_page_slider_amount"]);
	}
	
	if (isset($_POST['cc_page_slider_post_type']) === true) {
	    update_post_meta($id,"_cc_page_slider_post_type",$_POST["cc_page_slider_post_type"]);
	}
	if (isset($_POST['cc_page_slider_show_page']) === true) {
	    update_post_meta($id,"_cc_page_slider_show_page",$_POST["cc_page_slider_show_page"]);
	}
	
	if (isset($_POST['cc_posts_on_page_type']) === true) {
	    update_post_meta($id,"_cc_posts_on_page_type",$_POST["cc_posts_on_page_type"]);
	}
	
	if ($_POST['cc_page_slider_caption'] == "1") {
	 	update_post_meta($id,"_cc_page_slider_caption",1);
	}else{
	 	update_post_meta($id,"_cc_page_slider_caption",0);
	}
	if (isset($_POST['cc_page_slider_style']) === true) {
	    update_post_meta($id,"_cc_page_slider_style",$_POST["cc_page_slider_style"]);
	}
}
 
  function cc_get_page_meta(){
  	global $post;
	$cc_page['cc_page_slider_on']=get_post_meta($post->ID,"_cc_page_slider_on", true);
	$cc_page['cc_page_slider_cat']=get_post_meta($post->ID,"_cc_page_slider_cat", true);
	$cc_page['cc_page_template_on']=get_post_meta($post->ID,"_cc_page_template_on", true);
	$cc_page['cc_page_template_cat']=get_post_meta($post->ID,"_cc_page_template_cat", true);
	$cc_page['cc_page_template_amount']=get_post_meta($post->ID,"_cc_page_template_amount", true);
	$cc_page['cc_page_slider_time']=get_post_meta($post->ID,"_cc_page_slider_time", true);
	$cc_page['cc_page_slider_amount']=get_post_meta($post->ID,"_cc_page_slider_amount", true);
	$cc_page['cc_page_slider_post_type']=get_post_meta($post->ID,"_cc_page_slider_post_type", true);
	$cc_page['cc_page_slider_show_page']=get_post_meta($post->ID,"_cc_page_slider_show_page", true);
	$cc_page['cc_page_slider_orderby']=get_post_meta($post->ID,"_cc_page_slider_orderby", true);
	$cc_page['cc_posts_on_page_type']=get_post_meta($post->ID,"_cc_posts_on_page_type", true);
	
	$cc_page['cc_page_slider_style']=get_post_meta($post->ID,"_cc_page_slider_style", true);
	$cc_page['cc_page_slider_caption']=get_post_meta($post->ID,"_cc_page_slider_caption", true);
	return $cc_page;
  } 
  add_action('edit_page_form', 'cc_page_metabox');
  add_action('save_post','cc_page_meta_add');
?>