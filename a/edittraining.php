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
  $link = new mysqli($server,$username,$password,$db); 
  if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
  } 
  
  $ID = validate($_POST["ID"]);
  $ID = intval($ID);
  if (!(is_integer($ID))) {
     echo "<h2 class='error'>Employee ID must be in number format.</h2>";
  } 
  for($i=0;$i<4;$i++) {
    $j = 2 + ($i * 10);
	$s = $i + 1;
    $d = "due_" . $s;
	$c = "complete_" . $s;
	$dc = "dcomplete_" . $s;
	$today = date("Y-m-d");
	if (empty($_POST[$d])) {
	  echo "<script>alert('Due Date is required.');location.replace('edittraining.php?ID=" . $ID . "');</script>";
	} else {
	  if (($_POST[$c] == 1) and empty($_POST[$dc])) {
	    $_POST[$dc] = $today;
	    echo "made";
	  }
    $Due = $_POST[$d];
    $Complete = intval($_POST[$c]);
    $DComplete = $_POST[$dc];
	
    $sql = "update employee_training set due_date='$Due',complete=$Complete,date_complete='$DComplete' where employee_id=$ID and training_id=$j";

    $link->query($sql);
	}
  }
  
  echo "<script>location.replace('edittraining.php?Action=Success&ID=" . $ID . "');</script>";
  //close connection
  $link->close();
 

} else {//handles initial request for employee information (before update)
  if (!empty($_GET["ID"])) {
    $ID = validate($_GET["ID"]);
	$ID = intval($ID);
	if (!(is_integer($ID))) {
     echo "<script>location.replace('edittraining.php?Action=Exist');</script>";
	} 
    else {
	  $link = new mysqli($server,$username,$password,$db); 
	  if ($link->connect_error) {
		die("Connection failed: " . $link->connect_error);
	  } 
	  
	  $sql = "SELECT e.*, d.name as dept_name FROM employeetb e left join department d on e.DepartmentID = d.id WHERE e.ID = " . $ID;
	  
	  $result = $link->query($sql);
		
	  if ($result->num_rows == 0) {
		 echo "<script>location.replace('edittraining.php?Action=Exist');</script>";
	  } else {	   
	    while($row = $result->fetch_assoc()) {
	      $Name = $row["name"];
		  $Department = $row["dept_name"];
		  $Hire = $row["HireDate"];
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
  if($_GET["Action"] == 'Success') {
    echo "<p class='success'>Training Record updaded successfully.</p>";
  } elseif($_GET["Action"] == 'Fail') {
    echo "<p class='error'>Training Record update failed.</p>";
  } elseif($_GET["Action"] == 'Exist') {
    echo "<p class='error'>Employee ID does not exist.</p>";
  }
  ?>
  <h3>Edit Employee Trainings</h3>
  <p>Note: If Training is updated to complete, Date Completed will default to today if left blank.</p>
  <form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <p>Name: <?php echo $Name?></p>
	<p>Department: <?php echo $Department ?> </p>
	<p>Hire Date: <?php echo $Hire ?> </p>
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
	    $i = 0;
	    echo "<table class='viewt' style='margin-left:0px;margin-bottom:10px'><tr><th>TRAINING NAME</th><th>DUE DATE</th><th>COMPLETE</th><th>DATE COMPLETE</th></tr>";   
	    while($row = $result->fetch_assoc()) {
		  $i++;
		  echo "<td>" . $row["name"] . "</td><td><input type='date' name='due_" . $i . "' value='" . $row["due_date"] . "'></td>";
	      echo "<td><select name='complete_" .$i . "'>";
		  if ($row["complete"] == 0) {
		    echo "<option value=1>Yes</option><option value=0 selected>No</option>";
		  } else {
		    echo "<option value=1 selected>Yes</option><option value=0>No</option>";		  
		  }
		  echo "</select></td><td><input type='date' name='dcomplete_" .$i . "' value='" . $row["date_complete"] . "'></td></tr>";
		}
		echo "</table>";
	  }
    ?>
	<input type="hidden" name="ID" value="<?php echo $ID ?>">
	<?php
	if(($_GET["Action"] != 'Fail') and ($_GET["Action"] != 'Exist')) {
      echo '<p><input type="submit" value="Update"> <input type="button" value="Go Back" onClick="GoBack()"></p>';
    } else {
      echo '<p><input type="button" value="Go To View" onClick="GoToView()"></p>';
	}
   ?>
     
  </form>
  </div>
</div>
</div>
<script>
function GoBack() {
  window.history.back();
}
function GoToView() {
  location.replace('view.php');
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
</script>
</body>
</html>
