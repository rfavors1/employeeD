<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Add Employee</title>
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
  echo $Hire;
  echo $Name;
  echo $Email;
  
  if (!$NameError and !$EmailError and !$HireError) {
  echo "made it here2";
  $link = mysql_connect('richfavorscom.ipagemysql.com', 'employee', 'richard'); 
  if (!$link) { 
    die('Could not connect: ' . mysql_error()); 
  } 
  mysql_select_db(employee_16_db); 
  
  $sql = "INSERT INTO employee (Name, Email, HiringDate) VALUES ($Name, $Email, $Hire)";

  if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  
}
}

function validate($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
<div id="addform">
  <p></p><a class="goback" href="dashboard.php">Go Back</a></p>
  <h3>Add New Employee</h3>
  <p>*All fields are required.</p>
  <form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <p>Name: <input type="text" size=35 name="Ename"><span class="error"> <?php echo $NameError;?></span></p>
    <p>Email: <input type="text" size=50 name="Eemail"><span class="error"> <?php echo $EmailError;?></span></p>
    <p>Hiring Date: <input type="date" name="Ehire"><span class="error"> <?php echo $HireError;?></span></p>
	<p><input type="submit" value="Add"></p>
  </form>
</div>
</body>
</html>
