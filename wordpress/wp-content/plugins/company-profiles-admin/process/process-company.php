<?PHP

if(isset($_POST['process-form']) && $_POST['process-form']){

	$mission =$_POST['mission'];
	$about = $_POST['about'];
	$pr1 = $_POST['mainProduct'];
	$pr2 = $_POST['secProduct'];

	$what=$_POST['what'];
	$how=$_POST['how'];
	$why = $_POST['why'];

	$companyID = $_POST['company_id'];

	$successCount = 0;
	$TOTAL_QUERIES = 2;

	$queryData = $databaseInterface->update(
		"UPDATE cp_siteinfo_what SET what='" .$what ."',why='". $why ."', 
		how='".$how. "' WHERE companyId =". $companyID
		.""); 

	if($queryData == -2 || $queryData)
	 ++$successCount;


	$queryData = $databaseInterface->update(
		"UPDATE cp_siteinfo_about SET missionStatement='" . $mission ."', about='".$about .
		"' WHERE companyId =". $companyID
		.""); 
		
	if($queryData == -2 || $queryData)
	 ++$successCount;

	if($successCount > 0 && $successCount < $TOTAL_QUERIES){
		$successCount = 1;
	}
	 
	switch ($successCount)
	{
	case 1:
		print "<p>An error was encountered when processing your update. However, some of the form may have updated properly.<p>";
		break;
	case 2:
		print "<p> Your information has been successfully added to the database!<p>";
		break;
	default:
		print "<p>Fatal error updating database. No updates occured!<p>";
	}

}
?>