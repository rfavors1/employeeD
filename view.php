<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>View Employees</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<?php
// define variables and set to empty values
$NameError = $EmailError = $HireError = "";
$Name = $Email = $Hire = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  echo "<style>#addform {display: block;}</style>";
  if (empty($_POST["Ename"])) {
    $NameError = "Name is required";
  } else {
    $Name = validate($_POST["Ename"]);
  }
  
  if (empty($_POST["Eemail"])) {
    $EmailError = "Email is required";
  } else {
    $Email = validate($_POST["Eemail"]);
  }
    
  if (empty($_POST["Ehire"])) {
    $HireError = "Hire Date is required";
  } else {
    $Hire = validate($_POST["Ehire"]);
  }
}

function validate($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<div id="viewform">
  <h3>View Employees</h3>
  <p>Search Criteria</p>
  <form>
    <p>Name: <input type="text" size=35 name="EnameS"></p>
    <p>Email: <input type="text" size=50 name="EemailS"></p>
    <p>Hiring Date Before: <input type="date" name="EhireBS"></p>
	<p>Hiring Date After: <input type="date" name="EhireAS"></p>
	<p><input type="button" value="Add"> <input type="button" value="Cancel" class="vcancel"></p>
  </form>
</div>

</body>
</html>
