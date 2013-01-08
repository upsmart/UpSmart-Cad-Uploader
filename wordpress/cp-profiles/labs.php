<?PHP
  require('../wp-blog-header.php');
  include_once('./framework/dbProfiles.class.php');

  $dbsObject = new DBS_PROFILE("labs");

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
		<?php include("sidebar.php"); ?>
		<div class="padder left">
			<div id="information" class = "left whiteShell shadowFull">				
				<div id="contentMid" class = "left">
					<h3 class = "upSmartGradient pagetitle textCenter radius"><?PHP echo $company . " Labs"; ?></h3>
					<p> Nothing here yet, come back later </p>		
					
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