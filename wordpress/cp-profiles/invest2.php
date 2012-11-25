<?PHP
  require('../wp-blog-header.php');
  include_once('framework/dbProfiles.class.php');

  $dbsObject = new DBS_PROFILE("invest");

  if(!( $con = $dbsObject->connect() ) ){
    die('Could not connect: ' . mysql_error());
  }

  $_CLEAN = $dbsObject->clean($_GET); 
  $company = (!(array_key_exists('name', $_CLEAN)) || empty($_CLEAN)) ? "" : $_CLEAN['name'];
  
  $values = array("id", "name", "primaryContactId", "companyLogo");
  $tables = array("cp_companies");
  $whereClause = array("name" => array("=", $company));
  
  $dbData = $dbsObject->retrieve($values, $tables, $whereClause);
  
  $compId = $dbData[0][0];
  $compLogo = $dbData[0][3];

  if(!$compId){ 	
      $dbsObject->return_404();
  }

  $compName = $dbData[0][1];
  $companyLink = strtolower(str_replace(" ","",$compName));
  $primaryContact = $dbData[0][2];
  $compLogo = $dbData[0][3];
  
  $values = array("firstname", "lastname");
  $tables = array("cp_employees");
  $whereClause = array("id" => array("=", $primaryContact));
  
  $dbData = $dbsObject->retrieve($values, $tables, $whereClause);
  
  $postAuthor = $dbData[0][0] . "-" . $dbData[0][1];
  
  $imgData = $dbsObject->determineLogo($compLogo);
  $imgsrc = $imgData[0];
  $compLogo = $imgData[1];

  $scaledImg = $dbsObject->scaleImg($imgsrc);
  $new_width = $scaledImg[1];
  $new_height = $scaledImg[0];
?>
<?php get_header("cpTemplate"); ?>
	<div id="content" class = "cpInvest">
	<?php include("../cp-profiles/sidebar.php"); ?>
		<div class="padder left">
			<div id="information" class = "left whiteShell shadowFull">				
				<div id="contentMid" class = "left">
					<h3 class = "upSmartGradient pagetitle textCenter radius"><?PHP echo $company . " Investments"; ?></h3>
						 <?PHP 
							if(isset($_CLEAN['invalid'])){
							    $value = $_CLEAN['invalid'];
								echo '<div id="inputError"><b>Error:</b> This gift requires a ' . $value .' donation. Please select another gift or enter a donation of atleast ' . $value .'.</div>';
							}
						?>
						<form id="invest" method="post" action = '<?php echo "/cp-profiles/checkout.php?name=" .$company ?>'>
							<h4> Enter Your Pledge Amount </h4>
							<div id="money" class = "roundedBox">
								 <input id="dollars" class="left" name="dollars" type=text placeholder="Amount" pattern="\d+(\.\d{2})?" required autofocus />
								 <span class ="rewardsDesc left"> Enter any amount over $1.00 </span>
                            </div>	
							<h4> Let Us Show Our Appreciation! </h4>
							<div id="rewardOptions" class = "roundedBox">
							<ul>
								<li><input class ="left" type="radio" name="investRewards" value="No Thanks" /> <label class ="rewardsDesc left"> &quot;I just felt like giving&quot; </span> </li>
								<li><input class ="left" type="radio" name="investRewards" value="T-Shirt" /> <label class ="rewardsDesc left"> <b>$10.00 or more:</b> A super-slick UpSmart T-shirt! (Who doesn&apos;t want a T-shirt with a brain on it?) </label> </li>
								<li><input class ="left" type="radio" name="investRewards" value="UpSmart Credits" /> <label class ="rewardsDesc left"> <b>UpSmart Credits ($25.00 or more):</b> While our companies cannot offer equity to you yet (we have to wait until January for that), UpSmart can offer you credit. Choose this option to recieve $25.00 in UpSmart credits to invest in any of our crowdfunding companies this January</label> </li>
							</ul>
							</div>

							<input id="continue" type="submit" value="Continue To Next Step" />
						</form>
					
				
<?php /*http://www.primothemes.com/forums/viewtopic.php?f=36&t=16048 http://www.s2member.com/forums/topic/s2member-paypal-button-short-code-in-welcome-2/*/ ?>
					
				</div>
                                <?php include_once("../cp-profiles/social-links.php"); ?>
			</div>
		</div><!-- .padder -->
	</div><!-- #content -->
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
<?php get_footer("cpTemplate"); ?>