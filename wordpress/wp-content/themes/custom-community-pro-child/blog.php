<?php
/*
Template Name: Blog
Author: Sam Hagen
*/

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>WebSocket</title>
		<link rel="stylesheet" href="companyHome.css">
	</head>

<h2>Recent Posts</h2>
<ul>
<?php
	$recent_posts = wp_get_recent_posts();
	foreach( $recent_posts as $recent ){
		echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" >' 
.   $recent["post_title"].'</a> </li> ';
	}
?>
</ul>

</html>


