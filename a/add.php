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

include_once 'employee.php';

$url = parse_url("mysql://bd49b5ceb61b1f:edcd06f9@us-cdbr-iron-east-04.cleardb.net/heroku_c17a9191641ffc8?reconnect=true");
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);
// define variables and set to empty values
$NameError = $EmailError = $HireError = $SupervisorError = $DepartmentError = "";
$Name = $Email = $Hire = $Supervisor = $Department = "";

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
   //echo $_POST["Esup"] . " made " . $_POST["Edept"];
  if (empty($_POST["Esup"])) {
    $SupervisorError = "Supervisor is required";
  } else {
    $Supervisor = validate($_POST["Esup"]);
  }  

  if (empty($_POST["Edept"])) {
    $DepartmentError = "Department is required";
  } else {
    $Department = validate($_POST["Edept"]);
  } 
    
  if (!$NameError and !$EmailError and !$HireError and !$SupervisorError and !$DepartmentError) {
  $link = new mysqli($server,$username,$password,$db); 
  if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
  } 
  
  $sql = "INSERT INTO employeetb (ID,Name,Email,HireDate,LastModified,SupervisorID,DepartmentID) VALUES ('','$Name','$Email','$Hire',now(),'$Supervisor','$Department')";

  if ($link->query($sql) === TRUE) {
    echo "<script>location.replace('add.php?Action=Success');</script>";
	//NewHireTraining();
	unset($_POST);
  } else {
    echo "<script>location.replace('add.php?Action=Fail');</script>";
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
<div id="header">
<a href="dashboard.php"><h1>Employee Dashboard</h1></a>
<div class="lang" style="color: #0099FF;"><a href="dashboard.php" style="color: #333333;text-decoration: underline;font-weight:bold;" title="PHP Version">PHP</a> <a href="/j/dashboard.html" style="color: #0099FF;" title="JavaScript Version">JavaScript</a></div>
</div>
<div id="container">
<div id="right">
  <div id="top">
    <img src="img/x-mark.png" class="close" style="margin-left: 175px;">  
    <img src="img/rightarrow.png" class="open">  	
  </div>  
  <ul class="menu">
  <li><a href="add.php" class="active">Add Employee</a></li>
  <li><a href="view.php">View Employee</a></li>  
  </ul>
</div>
<div id="left">
<?php
  if ($_GET["Action"] == 'Success') {
    echo "<div class='success'>Employee record added successfully.</div>";
  } elseif ($_GET["Action"] == 'Fail') {
    echo "<div class='fail'>Unable to add employee record at this time.</div>";  
  }
?>
<div id="addform">
  <h3>Add New Employee</h3>
  <p>*All fields are required.</p>
  <form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <p>Name: <input type="text" size=35 name="Ename" value="<?php echo $_POST["Ename"]; ?>"><span class="error"> <?php echo $NameError;?></span></p>
    <p>Email: <input type="text" size=50 name="Eemail" value="<?php echo $_POST["Eemail"]; ?>"><span class="error"> <?php echo $EmailError;?></span></p>
    <p>Hiring Date: <input type="date" name="Ehire" value="<?php echo $_POST["Ehire"]; ?>"><span class="error"> <?php echo $HireError;?></span></p>
	<p>Supervisor: <select name-"Esup">
	<?php 
	$options = employeeName();
	foreach ($options as $value) {
	  $i = $value["id"];
	  $n = $value["name"];
	 // if ($Supervisor == $i) {
	  //  echo "<option value='" . $i . "' selected>" . $n . "</option>";
	  //} else {
	    echo "<option value='" . $i . "'>" . $n . "</option>";      
	  //}

   }
  
   ?>
    </select><span class="error"> <?php echo $SupervisorError;?></span></p>
	<p>Department: <select name="Edept">
	<?php 
	$options = departmentName();
	foreach ($options as $value) {
	  $i = $value["id"];
	  $n = $value["name"];
	  //if ($Department == $i) {
	  //  echo "<option value='" . $i . "' selected>" . $n . "</option>";
	  //} else {
	    echo "<option value='" . $i . "'>" . $n . "</option>";      
	  //}
   }
  
   ?>
    </select><span class="error"> <?php echo $DepartmentError;?></span></p>
	<p><input type="submit" value="Add"></p>
  </form>
</div>
</div>
</div>
<script>
$(".close").click(function(){
    $(".menu").css("display","none"); 
	$(".close").css("display","none"); 
	$(".open").css({"display":"block","margin-left":"5px"}); 
	$("#right").css("width","30px");    
	$("#left").css("margin-left","30px");  	
});

$(".open").click(function(){
	$("#right").css("width","200px");  
    $(".menu").css("display","block"); 
	$(".close").css({"display":"block","margin-left":"175px"}); 
	$(".open").css("display","none"); 
	$("#left").css("margin-left","175px");  	
  
});
</script>
</body>
</html>
