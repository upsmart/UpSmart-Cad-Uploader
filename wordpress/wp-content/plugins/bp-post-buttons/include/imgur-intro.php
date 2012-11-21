<?php
//relative path to WordPress site root from this scripts location
$path_to_wordpress = "../../../../";
require($path_to_wordpress.'wp-load.php');
global $current_user;

$imgur_explained = $_POST['imgur_explained'];
$upload_image = $_POST['upload_image'];
if($imgur_explained == "ON" && is_user_logged_in()) {
    update_user_meta(get_current_user_id(), "imgur_explained", $imgur_explained);
    echo("UPDATED");
}
if(get_user_meta(get_current_user_id(), "imgur_explained", true) == "ON" || $upload_image)
    wp_redirect("http://imgur.com/");
?>
<h1>Uploading images via Imgur</h1>
<p>
    Please copy & paste the <strong>"HTML image" link</strong> to the post after uploading.
    You can right click the link and select "copy", then close this window and
    insert the image into your post using Ctrl-V.
</p>
<form method="POST">
    <input type="submit" value="Continue" name="upload_image" />
    <?php if(is_user_logged_in()) {?>
        <input type="checkbox" value="ON" name="imgur_explained"/>dont show again
    <?php }?>
</form>
<p>
    <em><strong>No</strong> registration required</em>
</p>
