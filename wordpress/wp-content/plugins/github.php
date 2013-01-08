<?php

/**
 * Plugin Name: GitHub Integration
 * Plugin URI: 
 * Description: Adds [github repo="(name)"] for embedding GitHub repo information into a page.
 * Version: 1.0
 * Author: T.J. Lipscomb
 * Author URI: http://tjl.co/
 *
 * @package GitHub Integration
 * @author T.J. Lipscomb
 * @license Something Open Source
 *
 */


if(!function_exists('ellistr')) {
 //Copyright 2006 T.J. Lipscomb
function ellistr($s,$n) {
	for ( $x = 0; $x < strlen($s); $x++ ) {
		$o = ($n+$x >= strlen($s) ? $s : ($s{$n+$x} == " " ? substr($s,0,$n+$x) . "..." : ""));
		if ( $o != "" ) { return $o; }
	}
}
}
//Copyright 2006 T.J. Lipscomb
function reldate($date,$format='M d, Y') {
	$delta = abs(time()-$date);
	if($delta > 86400) return date($format,$date);
	if($delta > 3600) return round($delta/3600)." hour".(round($delta/3600)==1?"":"s")." ".($date>time()?"from now":"ago");
	if($delta > 60) return round($delta/60)." minute".(round($delta/60)==1?"":"s")." ".($date>time()?"from now":"ago");
	
	return $delta." second".($delta==1?'':'s')." ".($date>time()?"from now":"ago").".";
}

add_shortcode('github-commits', 'github_commits_func');

function github_commits_func($attributes) {
	if(!isset($attributes['user']) || !isset($attributes['repository'])) return false;
	//Fetch the data from the wordpress API
	$data = json_decode(file_get_contents("https://api.github.com/repos/{$attributes['user']}/{$attributes['repository']}/commits"),true);
	//Max
	if(isset($attributes['max'])) $max = (int)$attributes['max'];
	else $max = 0;
	//Build the output
	$out = '<table class="github_commits">';
	$i=0;
	foreach($data as $commit) {
		//Format the message with ellipses
		$msg = explode("<br/>",$commit['commit']['message']);
		$msg = ellistr($msg[0],50);
		
		//Format the date
		$time = reldate(strtotime($commit['commit']['author']['date']));
		
		//
		$img = md5( strtolower( trim( $commit['commit']['committer']['email'] )));
		$out .= "<tr><td rowspan='2'><img src='http://www.gravatar.com/avatar/{$img}?s=40'/></td><th colspan='2'><a href='{$commit['url']}'>{$msg}</a></th></tr><tr><td>By {$commit['commit']['committer']['name']}</td><td>$time</td></tr>";
		
		$i++;
		if($max != 0 && $i >= $max) break;
	}
	$out .= '</table>';
	return $out;
	//return //"<div class='github-repo' data-git-repo='{$attributes['repository']}'>PLACEHOLDER</div>";
}

?>