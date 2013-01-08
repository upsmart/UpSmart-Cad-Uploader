<?PHP
require('../wp-blog-header.php');

include_once('./framework/dbProfiles.class.php');

  $dbsObject = new DBS_PROFILE("who");

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
  
  $imgData = $dbsObject->determineLogo($compLogo);
  $imgsrc = $imgData[0];
  $compLogo = $imgData[1];

  $scaledImg = $dbsObject->scaleImg($imgsrc);
  $new_width = $scaledImg[1];
  $new_height = $scaledImg[0];
  
  $values = array("missionStatement", "about");
  $tables = array("cp_siteinfo_about");
  $whereClause = array("companyId" => array("=", $compId));
  
  $dbData = $dbsObject->retrieve($values, $tables, $whereClause);
  $mission = $dbData[0][0];
  $about = $dbData[0][1];
  
  $values = array("what", "why", "how");
  $tables = array("cp_siteinfo_what");
  $whereClause = array("companyId" => array("=", $compId));
  
  $dbData = $dbsObject->retrieve($values, $tables, $whereClause);
  $what = $dbData[0][0];
  $why = $dbData[0][1];
  $how = $dbData[0][1];
 
?>
<?php get_header("cpTemplate"); ?>
	<div id="content" class = "cpwhat">
		<?php include("sidebar.php"); ?>
		<div class="padder left">
			<div id="information" class = "left whiteShell shadowFull">				
				<div id="contentMid" class = "left">
					<h3 class = "upSmartGradient pagetitle textCenter radius"><?PHP echo $company; ?></h3>
			
					<?PHP 
					if($what != "."){
					echo '<h4 class = "upSmartGradient subHead textCenter radius"> What are we doing? </h4>';
					echo "<p>" . $what . "</p>"; 
					}
					?>
					<?PHP 
					if($why != "."){
					echo '<h4 class = "upSmartGradient subHead textCenter radius"> Why are we doing it? </h4>';
					echo "<p>" . $why . "</p>"; 
					}
					?>
					<?PHP 
					if($how != "."){
					echo '<h4 class = "upSmartGradient subHead textCenter radius"> How are we doing it? </h4>';
					echo "<p>" . $how. "</p>"; 
					}
					?>					
					
					
					
				</div>
 				<?php include_once("social-links.php"); ?>
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