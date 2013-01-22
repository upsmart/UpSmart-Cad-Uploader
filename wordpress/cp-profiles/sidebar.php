		<div id="linkSidebar" class="left whiteShell shadowFull">
			<a href=<?PHP echo '"companyHome.php?name=' . $company .'"'; ?>><img id="guestLogo" src = <?php echo "'". $compLogo ."'" ?> height=<?php echo "'". $new_height ."'" ?> width=<?php echo "'". $new_width ."'" ?> /></a>
			<div class="button-wrapper">
				<a href=<?PHP echo '"http://www.upsmart.com/groups/' . $companyLink .'"'; ?> class="a-btn radius">
					<span class="a-btn-text">Fan Group</span> 
					<span class="a-btn-slide-text">Support!</span>
					<span class="a-btn-icon-right"><span id="bubble"></span></span>
				</a>
				<a href=<?PHP echo '"http://www.upsmart.com/groups/' . $companyLink . '/' . $companyLink .'EA"'; ?> class="a-btn radius">
					<span class="a-btn-text">Early Adopters</span>
					<span class="a-btn-slide-text">Try it!</span>
					<span class="a-btn-icon-right"><span id="bulb"></span></span>
				</a>
				<a href=<?PHP echo '"labs.php?name=' . $company .'"'; ?> class="a-btn radius">
					<span class="a-btn-text">Company Labs</span>
					<span class="a-btn-slide-text">Innovate!</span>
					<span class="a-btn-icon-right"><span id="beaker"></span></span>
				</a>
				<a href=<?PHP echo '"invest.php?name=' . $company .'"'; ?> class="a-btn radius">
					<span class="a-btn-text">Invest</span>
					<span class="a-btn-slide-text">Contribute!</span>
					<span class="a-btn-icon-right"><span id="invest"></span></span>
				</a>
			</div>
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
