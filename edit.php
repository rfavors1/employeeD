<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Edit Employee</title>
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
  $ID = validate($_POST["ID"]);
  $ID = intval($ID);
  if (!(is_integer($ID))) {
     echo "<h2 class='error'>Employee ID must be in number format.</h2>";
  } 
	  
  if (!$NameError and !$EmailError and !$HireError and is_integer($ID)) {
  $link = new mysqli($server,$username,$password,$db); 
  if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
  } 
  
  $sql = "update employee set Name='$Name',Email='$Email',HireDate='$Hire' where ID=$ID";

  if ($link->query($sql) === TRUE) {
    echo "<h2 class='success'>New record created successfully</h2>";
  } else {
    echo "Error: " . $sql . "<br>" . $link->error;
  }
  
  $link->close();
  }
} else {
  if (empty($_GET["ID"])) {
     echo "<h2 class='error'>Employee ID is required.</h2>";
  } else {
    $ID = validate($_GET["ID"]);
	$ID = intval($ID);
	if (!(is_integer($ID))) {
     echo "<h2 class='error'>Employee ID must be in number format.</h2>";
	} 
    else {
	  $link = new mysqli($server,$username,$password,$db); 
	  if ($link->connect_error) {
		die("Connection failed: " . $link->connect_error);
	  } 
	  
	  $sql = "SELECT * FROM employee WHERE ID = " . $ID;
	  
	  $result = $link->query($sql);
		
	  if ($result->num_rows == 0) {
		 echo "<h2 class='error'>Employee ID does not exist.</h2>";
	  } else {	   
	    while($row = $result->fetch_assoc()) {
	      $Name = $row["Name"];
	      $Email = $row["Email"];
	      $Hire = $row["HireDate"];
	    }
	  }
	  
     $link->close();
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
  <?php 
  if($_GET["Action"] == 'Delete') {
  echo "<p class='error'>Are you sure want to delete this record? You will not be able to undo these changes.";
  }
  ?>
  <p><a class="goback" href="view.php">Go Back</a></p>
  <h3>Edit Employee</h3>
  <p>*All fields are required.</p>
  <form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <p>Name: <input type="text" size=35 name="Ename" value="<?php echo $Name?>"><span class="error"> <?php echo $NameError;?></span></p>
    <p>Email: <input type="text" size=50 name="Eemail" value="<?php echo $Email?>"><span class="error"> <?php echo $EmailError;?></span></p>
    <p>Hiring Date: <input type="date" name="Ehire" value="<?php echo $Hire?>"><span class="error"> <?php echo $HireError;?></span></p>
	<input type="hidden" name="ID" value="<?php echo $ID?>"
    <?php 
    if($_GET["Action"] == 'Delete') {
	?>
    <p><input type="button" value="Delete" onClick="Delete(<?php echo $ID ?>)"></p>
	<?php
    } else {
	?>
     <p><input type="submit" value="Update"></p>	
	<?php
	}
    ?> 	
  </form>
</div>
<script>
function Delete(id) {
   var dataString = 'ID=' + id;
	$.ajax({
	type: "POST",
	url: "delete.php",
	data: dataString,
	cache: false,
	success: function(){
	  document.write("<h2 style='color:#00CC00;margin-left: 50px;'>Record deleted successfully.</h2>");
    }
	});
}
</script>
</body>
</html>
