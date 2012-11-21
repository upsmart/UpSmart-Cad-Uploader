<?PHP
if(isset($_POST['process-form']) && $_POST['process-form']){

	$last=$_POST['last'];
	$first=$_POST['first'];
	$phone=$_POST['phone'];
	$email= $_POST['email'];
	$incorp =$_POST['incorporated'];

	$hasWebsite=$_POST['hasWebsite'];
	$website=$_POST['website'];
	$company = $_POST['companyName'];
	$address = $_POST['address'];
	$city= $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];

	$companyID = $_POST['company_id'];
	$empoloyeeID = $_POST['employee_id'];

	$successCount = 0;
	$TOTAL_QUERIES = 2;

	$queryData = $databaseInterface->update(
		"UPDATE cp_employees SET firstname='" .$first ."',lastname='". $last ."', phone='".$phone."', 
		email='".$email. "' WHERE companyId =". $companyID . " AND id=" . $empoloyeeID
		.""); 

	if($queryData == -2 || $queryData)
	 ++$successCount;
	 
	$queryData = $databaseInterface->update(
		"UPDATE cp_companies SET name='" . $company ."', address='".$address."', city='".$city."', state='".$state ."', zip=". $zip . ", website='".$website."', incorporated=".$incorp. " WHERE id =". $companyID
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