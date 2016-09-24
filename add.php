<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Add Employee</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
<?php
$url = parse_url("mysql://bd49b5ceb61b1f:edcd06f9@us-cdbr-iron-east-04.cleardb.net/heroku_c17a9191641ffc8?reconnect=true");
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);
// define variables and set to empty values
$NameError = $EmailError = $HireError = "";
$Name = $Email = $Hire = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["Ename"])) {
    $NameError = "Name is required";
  } else {
    $Name = validate($_POST["Ename"]);
	if (!preg_match("/^[a-zA-Z ]*$/",$Name)) {
      $NameError = "Only letters and white space allowed"; 
    }
  }
  
  if (empty($_POST["Eemail"])) {
    $EmailError = "Email is required";
  } else {
    $Email = validate($_POST["Eemail"]);
	if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
      $EmailError = "Invalid email format"; 
    }
  }
    
  if (empty($_POST["Ehire"])) {
    $HireError = "Hire Date is required";
  } else {
    $Hire = validate($_POST["Ehire"]);
  }
  
  if (!$NameError and !$EmailError and !$HireError) {
  $link = new mysqli($server,$username,$password,$db); 
  if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
  } 
  
  $sql = "INSERT INTO employee (ID,Name,Email,HireDate,LastModified) VALUES ('','$Name','$Email','$Hire',now())";

  if ($link->query($sql) === TRUE) {
    echo "<h2 class='success'>New record created successfully</h2>";
	unset($_POST);
  } else {
    echo "Error: " . $sql . "<br>" . $link->error;
  }
  
  $link->close();
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
  <p><a class="goback" href="dashboard.php">Go Back</a></p>
  <h3>Add New Employee</h3>
  <p>*All fields are required.</p>
  <form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <p>Name: <input type="text" size=35 name="Ename" value="<?php echo $_POST["Ename"]; ?>"><span class="error"> <?php echo $NameError;?></span></p>
    <p>Email: <input type="text" size=50 name="Eemail" value="<?php echo $_POST["Eemail"]; ?>"><span class="error"> <?php echo $EmailError;?></span></p>
    <p>Hiring Date: <input type="date" name="Ehire" value="<?php echo $_POST["Ehire"]; ?>"><span class="error"> <?php echo $HireError;?></span></p>
	<p><input type="submit" value="Add"></p>
  </form>
</div>
</body>
</html>
