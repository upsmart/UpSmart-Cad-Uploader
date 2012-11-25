<?PHP
include_once('dbProfiles.class.php');

$newprofile = new DBS_PROFILE("home");
$newprofile->connect();

$values = array("bio", "photo");
$tables = array("cp_employees", "cp_personbio");
$whereClause = array("companyId" => array("=", $compId), "employeeId" => array("=", "id"));

$newprofile->retrieve($values, $tables, $whereClause);
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
</body>
</html>