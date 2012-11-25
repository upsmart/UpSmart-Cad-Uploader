<?PHP
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);

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

?>
<?php get_header("cpTemplate"); ?>
	<div id="content" class = "cpwhat">
		<?php include("sidebar.php"); ?>
		<div class="padder left">
			<div id="information" class = "left whiteShell shadowFull">				
				<div id="contentMid" class = "left">
					<h3 class = "upSmartGradient pagetitle textCenter radius"><?PHP echo $company; ?></h3>
									
					<?PHP 
					if($mission != "."){
					echo '<h4 class = "upSmartGradient subHead textCenter radius"> Our Mission </h4>';
					echo "<p>" . $mission . "</p>"; 
					}
					?>
					
					<?PHP 
					if($about != "."){
					echo '<h4 class = "upSmartGradient subHead textCenter radius"> Who We Are </h4>';
					echo "<p>" . $about . "</p>";
					}
					
					$header = 1;
					
					if ($result = $con->query("SELECT bio, photo FROM cp_employees, cp_personbio WHERE companyId = $compId AND employeeId = id")) {
					
					    /* fetch associative array */
					    while ($row = $result->fetch_assoc()) {
						       
							$photo = $row['photo'];
							$bio = $row['bio'];
							
							if($bio != "."){
 								if($header) { echo '<h4 class = "upSmartGradient subHead textCenter radius"> People </h4>'; $header = 0; }
								
								echo '<div class="personBlock">';
								
								if($photo == "."){
									echo '<span class="left img"> <img src="/cp-profiles/images/person.png" width: "100px" height: "100px" /> </span>'; 
								}
								else{
									$imgsrc = "companies/" . $photo;
									$photo = "/cp-profiles/" . $imgsrc;

									$scaledImg = $dbsObject->scaleImg($imgsrc);
									$imgData = '<span class="left img"> <img src="' . $photo . '" width= "' . $scaledImg[1] . '" height= "'. $scaledImg[0] . '"/> </span>';
								       
								        echo $imgData;
								}
								
								echo '<span class="bio right">'. $bio .'</span>'; 
							        echo '</div>';
							}
						
						}
					    $result->free();
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