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
$Complete = $DComplete = $Due = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") { //handles update
    $Due = validate($_POST["due"]);
    $Complete = intval(validate($_POST["complete"]));
    $DComplete = validate($_POST["dcomplete"]);
  $ID = validate($_POST["ID"]);
  $ID = intval($ID);
  if (!(is_integer($ID))) {
     echo "<h2 class='error'>Employee ID must be in number format.</h2>";
  } 
	  
  $link = new mysqli($server,$username,$password,$db); 
  if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
  } 
  
  $sql = "update employee_training set due_date='$Due',complete=$Complete,date_complete='$DComplete' where employee_id=$ID";

  if ($link->query($sql) === TRUE) {
    echo "<h2 class='success'>Record updated successfully.</h2>";
  } else {
    echo "Error: " . $sql . "<br>" . $link->error;
  }

  //close connection
  $link->close();

} else {//handles initial request for employee information (before update)
  if (!empty($_GET["ID"])) {
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
	  
	  $sql = "SELECT e.*, d.name as dept_name FROM employeetb e left join department d on e.DepartmentID = d.id WHERE e.ID = " . $ID;
	  
	  $result = $link->query($sql);
		
	  if ($result->num_rows == 0) {
		 echo "<script>location.replace('edit.php?Action=Fail');</script>";
	  } else {	   
	    while($row = $result->fetch_assoc()) {
	      $Name = $row["name"];
	      $Email = $row["Email"];
	      $Hire = $row["HireDate"];
		  $Supervisor = $row["SupervisorID"];
		  $Department = $row["dept_name"];
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
<div class="lang" style="color: #0099FF;"><a href="dashboard.php" style="color: #333333;text-decoration: underline;font-weight:bold;" title="PHP Version">PHP</a> <a href="/j/dashboard.html" style="color: #0099FF;" title="JavaScript Version">JavaScript</a></div>
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
    <p>Name: <?php echo $Name?></p>
	<p>Department: <?php echo $Department ?> </p>
    <?php
	  $link = new mysqli($server,$username,$password,$db); 
	  if ($link->connect_error) {
		die("Connection failed: " . $link->connect_error);
	  } 
	  
      $sql = "select e.*, t.name from employee_training e left join training t on t.id = e.training_id where e.employee_id = " . $ID;
	  
	  $result = $link->query($sql);
		
	  if ($result->num_rows == 0) {
		 echo "<h2>No Trainings at this time.</h2>";
	  } else {	
	    echo "<table class='viewt' style='margin-left:0px;margin-bottom:10px'><tr><th>TRAINING NAME</th><th>DUE DATE</th><th>COMPLETE</th><th>DATE COMPLETE</th></tr>";   
	    while($row = $result->fetch_assoc()) {
		  echo "<td>" . $row["name"] . "</td><td><input type='date' name='due' value='" . $row["due_date"] . "'></td>";
	      echo "<td><select name='complete'>";
		  if ($row["complete"] == 0) {
		    echo "<option value=1>Yes</option><option value=0 selected>No</option>";
		  } else {
		    echo "<option value=1 selected>Yes</option><option value=0>No</option>";		  
		  }
		  echo "</select></td><td><input type='date' name='dcomplete' value='" . $row["date_complete"] . "'></td></tr>";
		}
		echo "</table>";
	  }
    ?>
	<input type="hidden" name="ID" value="<?php echo $ID ?>">
     <p><input type="submit" value="Update"> <input type="button" value="Go Back" onClick="GoBack()"></p> 
  </form>
  </div>
</div>
</div>
<script>
function GoBack() {
  window.history.back();
}
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
