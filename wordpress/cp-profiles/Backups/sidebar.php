		<div id="linkSidebar" class="left whiteShell shadowFull">
			<a href=<?PHP echo '"companyHome.php?name=' . $company .'"'; ?>><img id="guestLogo" src = <?php echo "'". $compLogo ."'" ?> height=<?php echo "'". $new_height ."'" ?> width=<?php echo "'". $new_width ."'" ?> /></a>
			<ul>
				<li class="radius linktitle linkGradient">
					<span class="icon bubble left"></span>
					<a href=<?PHP echo '"http://www.go-upsmart.com/homepage/homepage/groups/' . $companyLink .'"'; ?> class="right"> Join Group </a>
				</li>
				<li class="radius linktitle linkGradient">
					<span class="icon bulb left"></span>
					<a href=<?PHP echo '"http://www.go-upsmart.com/homepage/homepage/groups/' . $companyLink . '/' . $companyLink .'EA"'; ?> class="right">Early Adopters </a>
				</li>
				<li class="radius linktitle linkGradient">
					<span class="icon beaker left"></span>
					<a href=<?PHP echo '"labs.php?name=' . $company .'"'; ?> class="right">Company Labs</a>
				</li>
				<li class="radius linktitle linkGradient">
					<span class="icon invest left"></span>
					<a href=<?PHP echo '"invest.php?name=' . $company .'"'; ?> class="right">Invest</a>
				</li>
			</ul>
			<?php do_action('sidebar_right') ?> 
		</div>
		
		<!--
					<div class="button-wrapper">
					<a href="#" class="a-btn">
						<span class="a-btn-text">Register now</span> 
						<span class="a-btn-slide-text">It's free!</span>
						<span class="a-btn-icon-right"><span></span></span>
					</a>
					<a href="#" class="a-btn">
						<span class="a-btn-text">Become a member</span>
						<span class="a-btn-slide-text">Sign up!</span>
						<span class="a-btn-icon-right"><span></span></span>
					</a>
					<a href="#" class="a-btn">
						<span class="a-btn-text">Enter</span>
						<span class="a-btn-slide-text">Log in!</span>
						<span class="a-btn-icon-right"><span></span></span>
					</a>
					<a href="#" class="a-btn">
						<span class="a-btn-text">Get an account</span>
						<span class="a-btn-slide-text">For free!</span>
						<span class="a-btn-icon-right"><span></span></span>
					</a>
				</div>
		 -->