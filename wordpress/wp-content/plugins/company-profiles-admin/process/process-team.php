<?PHP
if(isset($_POST['process-form']) && $_POST['process-form']){
	print_r($_POST);

	$companyID = $_POST['company_id'];

	$entryPrefix = "entry";
	$titlePrefix = "title";
	$firstPrefix = "first";
	$lastPrefix = "last";
	$picPrefix = "pic";
	$filePrefix = "file";
	$bioPrefix = "bio";

	$memberNum = 1;
	$successCount = 0;
	$TOTAL_QUERIES = 0;
		
	print "<p>Updating existing members...</p>";

	while( array_key_exists($titlePrefix . $memberNum, $_POST) ){

		$TOTAL_QUERIES +=3;
		
		$cTitle = $_POST[$titlePrefix . $memberNum];
		$cFirst = $_POST[$firstPrefix . $memberNum];
		$cLast = $_POST[$lastPrefix . $memberNum];
		$cPic = $_POST[$picPrefix . $memberNum];
		$cFile = $_POST[$filePrefix . $memberNum];
		$cBio = $_POST[$bioPrefix . $memberNum];
		
		$cEmployeeId = $_POST[$entryPrefix . $memberNum];
		
		print("<p>".$cEmployeeId."</p>");
		
		$queryData = $databaseInterface->update(
		"UPDATE cp_employees SET firstname='" .$cFirst ."',lastname='". $cLast . 
		"' WHERE id =". $cEmployeeId
		.""); 

		if($queryData == -2 || $queryData)
		 ++$successCount;
		 
		$queryData = $databaseInterface->update(
			"UPDATE cp_positions SET title='" . $cTitle .
			"' WHERE employeeId =". $cEmployeeId
			.""); 
			
		if($queryData == -2 || $queryData)
		 ++$successCount;
		 
		$queryData = $databaseInterface->update(
		"UPDATE cp_personbio SET bio='" .$cBio ."',pic='". $cPic . 
		"' WHERE employeeId =". $cEmployeeId
			.""); 
			
		if($queryData == -2 || $queryData)
		 ++$successCount;
		 
		$memberNum++;
	}

	print "<p>Updating any new members...</p>";

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