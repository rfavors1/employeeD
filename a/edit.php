<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Edit Employee</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="/css/style.css">
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

if ($_SERVER["REQUEST_METHOD"] == "POST") { //handles update
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
  $ID = validate($_POST["ID"]);
  $ID = intval($ID);
  if (!(is_integer($ID))) {
     echo "<h2 class='error'>Employee ID must be in number format.</h2>";
  } 
	  
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
  
  $sql = "update employeetb set Name='$Name',Email='$Email',HireDate='$Hire',LastModified=now(),SupervisorID='$Supervisor',DepartmentID='$Department' where ID=$ID";

  if ($link->query($sql) === TRUE) {
    echo "<h2 class='success'>Record updated successfully.</h2>";
  } else {
    echo "Error: " . $sql . "<br>" . $link->error;
  }
  $table = "employeetb";
  
  //check to see if name changed, if so add to change log
  if ($_POST["Oname"] != $Name) {
    $sql = "INSERT INTO changeloga (ChangeID,ID,TableName,Field,OldValue,NewValue,ChangeDate) VALUES ('',$ID,'$table','Name','" . $_POST["Oname"] . "','$Name',now())";
	$link->query($sql);
  }
  
  //check to see if email has changed, if so add to change log
  if ($_POST["Oemail"] != $Email) {
    $sql = "INSERT INTO changeloga (ChangeID,ID,TableName,Field,OldValue,NewValue,ChangeDate) VALUES ('',$ID,'$table','Email','" . $_POST["Oemail"] . "','$Email',now())";
	$link->query($sql);
  }  

  //check to see if hire date has changed, if so add to change log
  if ($_POST["Ohire"] != $Hire) {
    $sql = "INSERT INTO changeloga (ChangeID,ID,TableName,Field,OldValue,NewValue,ChangeDate) VALUES ('',$ID,'$table','HireDate','" . $_POST["Ohire"] . "','$Hire',now())";
	$link->query($sql);
  }
  //check to see if supervisor id has changed, if so add to change log
  if ($_POST["Osup"] != $Supervisor) {
    $sql = "INSERT INTO changeloga (ChangeID,ID,TableName,Field,OldValue,NewValue,ChangeDate) VALUES ('',$ID,'$table','SupervisorID'," . $_POST["Osup"] . ",$Supervisor,now())";
	$link->query($sql);
  }  
  
  //check to see if department id has changed, if so add to change log
  if ($_POST["Odept"] != $Department) {
    $sql = "INSERT INTO changeloga (ChangeID,ID,TableName,Field,OldValue,NewValue,ChangeDate) VALUES ('',$ID,'$table','DepartmentID'," . $_POST["Odept"] . ",$Department,now())";
	$link->query($sql);
  }
  
  //close connection
  $link->close();
  }
} else {//handles initial request for employee information (before update)
  if (!empty($_GET["ID"])) {
 //    echo "<script>location.replace('edit.php?Action=Fail');</script>";
  //} else {
    $ID = validate($_GET["ID"]);
	$ID = intval($ID);
	if (!(is_integer($ID))) {
     echo "<script>location.replace('edit.php?Action=Fail');</script>";
	} 
    else {
	  $link = new mysqli($server,$username,$password,$db); 
	  if ($link->connect_error) {
		die("Connection failed: " . $link->connect_error);
	  } 
	  
	  $sql = "SELECT * FROM employeetb WHERE ID = " . $ID;
	  
	  $result = $link->query($sql);
		
	  if ($result->num_rows == 0) {
		 echo "<script>location.replace('edit.php?Action=Fail');</script>";
	  } else {	   
	    while($row = $result->fetch_assoc()) {
	      $Name = $row["name"];
	      $Email = $row["Email"];
	      $Hire = $row["HireDate"];
		  $Supervisor = $row["SupervisorID"];
		  $Department = $row["DepartmentID"];
	    }
	  }
	  
	  
     $link->close();
    }
  }
}
function validate($data) { //ensure proper data
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<div id="header">
<a href="dashboard.php"><h1>Employee Dashboard</h1></a>
<div class="lang" style="color: #0099FF;"><a href="dashboard.php" style="color: #333333;text-decoration: underline;font-weight:bold;" title="PHP Version">PHP</a> <a href="j/dashboard.html" style="color: #0099FF;" title="JavaScript Version">JavaScript</a></div>
</div>
<div id="container">
<div id="right">
  <div id="top">
    <img src="img/x-mark.png" class="close" style="margin-left: 175px;">  
    <img src="img/rightarrow.png" class="open">  	
  </div>  
  <ul class="menu">
  <li><a href="add.php">Add Employee</a></li>
  <li><a href="view.php" class="active">View Employee</a></li>  
  </ul>
</div>
<div id="left">
  <div id="editform">
  <?php 
  if($_GET["Action"] == 'Delete') {
    echo "<p class='error'>Are you sure want to delete this record? You will not be able to undo these changes.</p>";
  } elseif($_GET["Action"] == 'Fail') {
    echo "<p class='error'>Employee ID does not exist.</p>";
  }
  ?>
  <h3>Edit Employee</h3>
  <p>*All fields are required.</p>
  <form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <p>Name: <input type="text" size=35 name="Ename" value="<?php echo $Name?>"><span class="error"> <?php echo $NameError;?></span></p>
    <p>Email: <input type="text" size=50 name="Eemail" value="<?php echo $Email?>"><span class="error"> <?php echo $EmailError;?></span></p>
    <p>Hiring Date: <input type="date" name="Ehire" value="<?php echo $Hire?>"><span class="error"> <?php echo $HireError;?></span></p>
	<p>Supervisor: <select name="Esup">
	<?php 
	$options = employeeName();
	foreach ($options as $value) {
	  $i = $value["id"];
	  $n = $value["name"];
	 if ($Supervisor == $i) {
	    echo "<option value='" . $i . "' selected>" . $n . "</option>";
	 } else {
	    echo "<option value='" . $i . "'>" . $n . "</option>";      
	 }

   }
  
   ?>
    </select><span class="error"> <?php echo $SupervisorError;?></span></p>
	<p>Department: <select name="Edept">
	<?php 
	$options = departmentName();
	foreach ($options as $value) {
	  $i = $value["id"];
	  $n = $value["name"];
	  if ($Department == $i) {
	    echo "<option value='" . $i . "' selected>" . $n . "</option>";
	  } else {
	    echo "<option value='" . $i . "'>" . $n . "</option>";      
	  }
   }
  
   ?>
    </select><span class="error"> <?php echo $DepartmentError;?></span></p>
	<input type="hidden" name="ID" value="<?php echo $ID?>">
	<input type="hidden" name="Oname" value="<?php echo $Name?>">
	<input type="hidden" name="Oemail" value="<?php echo $Email?>">
	<input type="hidden" name="Ohire" value="<?php echo $Hire?>">
	<input type="hidden" name="Osup" value="<?php echo $Supervisor?>">
	<input type="hidden" name="Odept" value="<?php echo $Department?>">	
    <?php 
    if($_GET["Action"] == 'Delete') {
	?>
    <p><input type="button" value="Delete" onClick="Delete(<?php echo $ID . ",'" . $Name . "','" . $Email . "','" . $Hire . "','" . $Supervor . "','" . $Department . "'"?>)"></p>
	<?php
    } else {
      if($_GET["Action"] != 'Fail') {	  
    ?>
     <p><input type="submit" value="Update"></p>	
	<?php
	  }
	}
    ?> 	
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

function Delete(id,name,email,hire,supervisor,department) {
   var dataString = 'ID=' + id + '&name=' + name + '&email=' + email + '&hire=' + hire + '&supervisor=' + supervisor + '&department=' + department;
	$.ajax({
	type: "POST",
	url: "delete.php",
	data: dataString,
	cache: false,
	success: function(){
	  location.replace("view.php?Action=DeleteSuccess");
    }
	});
}
</script>
</body>
</html>
