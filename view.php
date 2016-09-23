<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>View Employees</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

<div id="viewform">
  <p><a class="goback" href="dashboard.php">Go Back</a></p>
  <h3>View Employees</h3>
  <h3>Search Criteria</h3>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <p>Name: <input type="text" size=35 name="EnameS"></p>
    <p>Email: <input type="text" size=50 name="EemailS"></p>
    <p>Hiring Date Before: <input type="date" name="EhireBS"></p>
	<p>Hiring Date After: <input type="date" name="EhireAS"></p>
	<p><input type="submit" value="Search"></p>
  </form>
	</div>
	<?php
	$url = parse_url("mysql://bd49b5ceb61b1f:edcd06f9@us-cdbr-iron-east-04.cleardb.net/heroku_c17a9191641ffc8?reconnect=true");
	$server = $url["host"];
	$username = $url["user"];
	$password = $url["pass"];
	$db = substr($url["path"], 1);
	// define variables and set to empty values
	$Name = $Email = $HireB = $HireA = "";
	
		
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
	  if (!empty($_GET["EnameS"])) {
		$Name = " and Name like '%". validate($_GET["EnameS"]) ."%'";
	  }
	  
	  if (!empty($_GET["EemailS"])) {
		$Email = " and Email like '%". validate($_GET["EemailS"]) ."%'";
	  }
		
	  if (!empty($_GET["EhireBS"])) {
		$HireB = " and HireDate <= '". validate($_GET["EhireBS"]) ."'";
	  }
	  
	  if (!empty($_GET["EhireAS"])) {
		$HireA = " and HireDate >= '". validate(.$_GET["EhireAS"]) ."'";
	  }  
	  $link = new mysqli($server,$username,$password,$db); 
	  if ($link->connect_error) {
		die("Connection failed: " . $link->connect_error);
	  } 
	  
	 $sql = "SELECT * FROM employee WHERE 1=1" . $Name . $Email . $HireB . $HireA; 	 
	 $result = $link->query($sql);
	
	if ($result->num_rows > 0) {
		echo "<div id='results'><table class='view'><tr><th>&nbsp;</th><th>ID</th><th>NAME</th><th>EMAIL</th><th>HIRE DATE</th></tr><tr><th>&nbsp;</th><th><a href='view.php?direction=up&col=ID&EnameS=" . $_GET["EnameS"] . "&EemailS=" . $_GET["EemailS"] . "&EhireBS=" . $_GET["EhireBS"] . "&EhireAS=" . $_GET["EhireAS"] . "'><img src='img/sort_down.png'></a> <img src='img/sort_up.png'></th><th><img src='img/sort_down.png'> <img src='img/sort_up.png'></th><th><img src='img/sort_down.png'> <img src='img/sort_up.png'></th><th><img src='img/sort_down.png'> <img src='img/sort_up.png'></th></tr>";
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr><td><img src='img/delete.png'> <img src='img/pencil.png'></td><td>".$row["ID"]."</td><td>".$row["Name"]."</td><td>".$row["Email"]."</td><td>" . $row["HireDate"] . "</td></tr>";
		}
		echo "</table></div>";
	} else {
		echo "<h2>0 results</h2>";
	}
	$link->close();
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	  if (!empty($_POST["EnameS"])) {
		$Name = " and Name like '%". validate($_POST["EnameS"]). "%'";
	  }
	  
	  if (!empty($_POST["EemailS"])) {
		$Email = " and Email like '%". validate($_POST["EemailS"]). "%'";
	  }
		
	  if (!empty($_POST["EhireBS"])) {
		$HireB = " and HireDate <= '". validate($_POST["EhireBS"]) ."'";
	  }
	  
	  if (!empty($_POST["EhireAS"])) {
		$HireA = " and HireDate >= '". validate($_POST["EhireAS"]) ."'";
	  }  
	  $link = new mysqli($server,$username,$password,$db); 
	  if ($link->connect_error) {
		die("Connection failed: " . $link->connect_error);
	  } 
	  
	 $sql = "SELECT * FROM employee WHERE 1=1" . $Name . $Email . $HireB . $HireA; 	 
	 $result = $link->query($sql);
	
	if ($result->num_rows > 0) {
		echo "<div id='results'><table class='view'><tr><th>&nbsp;</th><th>ID</th><th>NAME</th><th>EMAIL</th><th>HIRE DATE</th></tr><tr><th>&nbsp;</th><th><a href='view.php?direction=up&col=ID&EnameS=" . $_POST["EnameS"] . "&EemailS=" . $_POST["EemailS"] . "&EhireBS=" . $_POST["EhireBS"] . "&EhireAS=" . $_POST["EhireAS"] . "'><img src='img/sort_down.png'></a> <img src='img/sort_up.png'></th><th><img src='img/sort_down.png'> <img src='img/sort_up.png'></th><th><img src='img/sort_down.png'> <img src='img/sort_up.png'></th><th><img src='img/sort_down.png'> <img src='img/sort_up.png'></th></tr>";
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr><td><img src='img/delete.png'> <img src='img/pencil.png'></td><td>".$row["ID"]."</td><td>".$row["Name"]."</td><td>".$row["Email"]."</td><td>" . $row["HireDate"] . "</td></tr>";
		}
		echo "</table></div>";
	} else {
		echo "<h2>0 results</h2>";
	}
	$link->close();
	}
	
	function validate($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
	?>

</body>
</html>
