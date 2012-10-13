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
  
  $companyContact = $dbData[0][0] . " " . $dbData[0][1];
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
					<h3 class = "upSmartGradient pagetitle textCenter radius">Checkout</h3>
						<?php

					    $amount = 0;
						if( isset($_POST["dollars"]) ){
							$dollar = intval(esc_attr($_POST["dollars"]));
							$amount =  number_format(round($dollar, 2), 2);
						}
						
						$host  = $_SERVER['HTTP_HOST'];
						$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
						$extra = 'invest2.php?name=' . $company;
						$errorVar = '&invalid=';
						
						if(isset($_POST["investRewards"]) ){
						   
						    $reward = $_POST["investRewards"];
							
							if($amount <= 1){ $errorVar .= "more than a $1.00"; header("Location: http://$host$uri/$extra$errorVar"); }
							else
							if($reward == "T-Shirt") {
							    $errorVar .= "$10.00";
								if($amount < 10){ header("Location: http://$host$uri/$extra$errorVar"); }
							}
							else if($reward == "UpSmart Credits") {
							    $errorVar .= "$25.00";
							    if($amount < 25){ header("Location: http://$host$uri/$extra$errorVar"); }
							}
						}
						else{
						  header("Location: http://$host$uri/$extra$errorVar");
						}
						
						?>

						<section id="summary" class = "roundedBox">
							<ul class="left"> 
								<li><b>Funding: </b> <span class="finalSelection"><?php echo $company ?></span> </li>
								<li>By: <?php echo $companyContact ?> </li>
								<li>
									<label for = "termsOfServerice">Yes, I have read and agreed to the <a href="/about-us/"> terms of service.</a></label>
									<input id="termsOfServerice" name="termsOfServerice" value="agree" type="checkbox" required>
								</li>
							</ul>
							<ul class="right"> 
								<li>Pledge: <span class="finalSelection"><?php echo "$" . $amount ?> </span></li>
								<li>Selected Reward: <span class="finalSelection"><?php echo $reward ?> </span></li>
								<li>
									<a id="changeOrder" href= '<?php echo "/cp-profiles/invest2.php?name=" .$company ?>'></a>
								</li>
							</ul>
						</section>	

						<section id="checkout" class = "roundedBox">
						<h4> Please select one of our payment services to complete your contribution.</h4>
						<?php echo do_shortcode('[s2Member-PayPal-Button level="1" ccaps="" desc="Benefactor" ps="paypal" lc="" cc="USD" dg="0" ns="1" custom="website.com" ta="0" tp="1" tt="M" ra="' . $amount . '" rp="1" rt="L" rr="1" rrt="" rra="1" image="default" output="button" ]'); ?>
						<form action="https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/857665999167502" id="BB_BuyButtonForm" class="right" method="post" name="BB_BuyButtonForm" target="_top">
							<input name="item_name_1" type="hidden" value=<?php echo '"'. $company .' Donation"'?>/>
							<input name="item_description_1" type="hidden" value=""/>
							<input name="item_quantity_1" type="hidden" value="1"/>
							<input name="item_price_1" type="hidden" value= <?php echo '"'. $amount .'"'?>/>
							<input name="item_currency_1" type="hidden" value="USD"/>
							<input name="_charset_" type="hidden" value="utf-8"/>
							<input alt="" src="https://checkout.google.com/buttons/buy.gif?merchant_id=857665999167502&amp;w=121&amp;h=44&amp;style=trans&amp;variant=text&amp;loc=en_US" type="image" height="44px" width="121px"/>
						</section>
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