<?php
/*
Template Name: upSmartHome
Author: Aaron Tobias
*/

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>WebSocket</title>
		<link rel="stylesheet" href="companyHome.css">
	</head>
	<body class ="centerAlign">
		<div id="header">
			<h1>HEADER!</h1>
		</div>
		<div id="mainContent">
		
			<div id="sidebar" class="left">
				<div id="guestLogo"> 
				</div>
				<ul>
					<li class="upSmartGradient radius pagetitle"><span class="icon left"></span><a href="#" class="right"> Join Group </a></li>
					<li class="upSmartGradient radius pagetitle"><span class="icon left"></span><a href="#" class="right"> Early Adopters </a></li>
					<li class="upSmartGradient radius pagetitle"><span class="icon left"></span><a href="#" class="right"> [Company] Labs</a></li>
				</ul>
			</div>
			<div id="content" class="right">				
				<h3 class = "upSmartGradient pagetitle radius"> Company Name </h3>
				<div id="contentMid" class = "left">
				    <div id="mediaFrame radius">
						<iframe width="420" height="315" src="http://www.youtube.com/watch?v=9fAZIQ-vpdw&feature=relmfu" frameborder="0" allowfullscreen> </iframe>
					</div>
					<ul>
						<li class="upSmartGradient radius pagetitle left"><a href="#"> Who We Are </a></li>
						<li class="upSmartGradient radius pagetitle left"><a href="#"> What We're Doing </a></li>
					</ul>
				</div>
				<div id="socialLinks" class="right">
					<div class="fb-like slink" data-href="http://localhost" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true" data-action="recommend" data-font="arial"></div>
					<div class="slink"> <a href="https://twitter.com/company" class="twitter-follow-button" data-show-count="false">Follow @company</a></div>
					<!-- Place this tag where you want the +1 button to render -->
					<div class="slink"> <g:plusone annotation="none"></g:plusone> </div>
					<div class="slink rss"> </div>
				</div>
				<div class="clear">
					<div id="blog">
					BLOG!
					</div>
				</div>
			</div>	
			
		</div>
	<div id="fb-root"></div>	
	<script>
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	</script>

	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

	<!-- Place this render call where appropriate -->
	<script type="text/javascript">
	  (function() {
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		po.src = 'https://apis.google.com/js/plusone.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	  })();
	</script>
    </body>
</html>


