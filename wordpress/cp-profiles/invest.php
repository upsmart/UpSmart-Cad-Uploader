<?PHP
  require('../wp-blog-header.php');
  include_once('./framework/dbProfiles.class.php');

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
	<div id="content" class = "cpwhat">
	<?php include("./sidebar.php"); ?>
		<div class="padder left">
			<div id="information" class = "left whiteShell shadowFull">				
				<div id="contentMid" class = "left">
					<h3 class = "upSmartGradient pagetitle textCenter radius"><?PHP echo $company . " Investments"; ?></h3>
					<h5> This page is under construction </h5>
					<br/>
					<p> While we have to wait until January to give ownership in our companies in exchange for investment, you can still support them now through donation! In fact, give more than $x&#46;xx to any one of our companies and you will ear $y.yy in UpSmart Credit, for you to invest in January. That&apos;s right! Help our entrepreneurs out and we will help you pay for your first ownership in a company.</p>
					<div id="money">
						<form>
							<label for=dollars>$</label>
							<input id=dollars name=dollars type=text placeholder="Dollar Amount" required autofocus />
							<label for=cents>&#46;</label>
							<input id=cents name=cents type=text placeholder="Amount of Cents" required />
							<?php echo do_shortcode('[s2Member-Security-Badge v="1"]'); ?>
							<input id="upsmartCredit" name="upsmartCredit" value="agree" type="checkbox"> <label for = "upsmartCredit">Yes, I would like to recieve UpSmart Credit for my contribution.</label>
							<input id="termsOfServerice" name="termsOfServerice" value="agree" type="checkbox"> <label for = "termsOfServerice">Yes, I have read and agreed to the terms of service.</label>
						</form>
						
						<?php echo do_shortcode('[s2Member-PayPal-Button level="1" ccaps="" desc="Silver Member / description and pricing details here." ps="paypal" lc="" cc="USD" dg="0" ns="1" custom="website.com" ta="100" tp="1" tt="M" ra="14.99" rp="1" rt="M" rr="1" rrt="" rra="1" image="default" output="button" ]'); ?>
					
					
					
					
					</div>
					
				
<?php /*http://www.primothemes.com/forums/viewtopic.php?f=36&t=16048 http://www.s2member.com/forums/topic/s2member-paypal-button-short-code-in-welcome-2/*/ ?>
					
				</div>
                                <?php include_once("./social-links.php"); ?>
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
