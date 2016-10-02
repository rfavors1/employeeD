<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>View Employees</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
<?php include_once 'employee.php'; ?>
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
<div id="viewform">
  <?php
  if($_GET["Action"] == 'DeleteSuccess') {
  echo "<div class='success'>Record deleted successfully.</div>";
  }
  ?>
  <h3>View Employees</h3>
  <h3>Search Criteria</h3>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <p>Name: <input type="text" size=35 name="EnameS"></p>
    <p>Email: <input type="text" size=50 name="EemailS"></p>
    <p>Hiring Date Before: <input type="date" name="EhireBS"></p>
	<p>Hiring Date After: <input type="date" name="EhireAS"></p>
	<p>Supervisor: <select name="Esup">
		<option value=""></option>
	<?php 
	$options = employeeName();
	foreach ($options as $value) {
	  $i = $value["id"];
	  $n = $value["name"];
	  echo "<option value='" . $i . "'>" . $n . "</option>";      
   }  
   ?>
    </select></p>
	<p>Department: <select name="Edept">
	<option value=""></option>
	<?php 
	$options = departmentName();
	foreach ($options as $value) {
	  $i = $value["id"];
	  $n = $value["name"];
	  echo "<option value='" . $i . "'>" . $n . "</option>";  
   }  
   ?>
    </select></p>
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
	$Name = $Email = $HireB = $HireA = $Sup = $Dept = $Sort = "";
	
		

	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	  if (!empty($_POST["EnameS"])) {
		$Name = " and e.Name like '%". validate($_POST["EnameS"]). "%'";
	  }
	  
	  if (!empty($_POST["EemailS"])) {
		$Email = " and e.Email like '%". validate($_POST["EemailS"]). "%'";
	  }
		
	  if (!empty($_POST["EhireBS"])) {
		$HireB = " and e.HireDate <= '". validate($_POST["EhireBS"]) ."'";
	  }
	  
	  if (!empty($_POST["EhireAS"])) {
		$HireA = " and e.HireDate >= '". validate($_POST["EhireAS"]) ."'";
	  }
	  
	  if (!empty($_POST["Esup"])) {
		$Sup = " and e.SupervisorID = ". validate($_POST["Esup"]);
	  }
	  
	  if (!empty($_POST["Edept"])) {
		$Dept = " and e.DepartmentID = ". validate($_POST["Edept"]);
	  }
	  	  
	  } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
	  if (!empty($_GET["EnameS"])) {
		$Name = " and e.Name like '%". validate($_GET["EnameS"]). "%'";
	  }
	  
	  if (!empty($_GET["EemailS"])) {
		$Email = " and e.Email like '%". validate($_GET["EemailS"]). "%'";
	  }
		
	  if (!empty($_GET["EhireBS"])) {
		$HireB = " and e.HireDate <= '". validate($_GET["EhireBS"]) ."'";
	  }
	  
	  if (!empty($_GET["EhireAS"])) {
		$HireA = " and e.HireDate >= '". validate($_GET["EhireAS"]) ."'";
	  }
	  
	  if (!empty($_GET["Esup"])) {
		$Sup = " and e.SupervisorID = ". validate($_GET["Esup"]);
	  }
	  
	  if (!empty($_GET["Edept"])) {
		$Dept = " and e.DepartmentID = ". validate($_GET["Edept"]);
	  }	  	  
	  
	  if (!empty($_GET["direction"]) and !empty($_GET["col"])) {
		$Sort = " order by e.". validate($_GET["col"]) . " " . validate($_GET["direction"]) ;
	  }
	  }
	   
	  $link = new mysqli($server,$username,$password,$db); 
	  if ($link->connect_error) {
		die("Connection failed: " . $link->connect_error);
	  } 

	if (($_SERVER["REQUEST_METHOD"] == "POST") or (!empty($_GET["direction"]) and !empty($_GET["col"]))) {	  
	 $sql = "SELECT e.*, date_format(e.HireDate,'%b %e %Y') as hire,date_format(e.LastModified,'%b %e %Y %l:%i %p') as modified,s.name as supname ,d.name as deptname from employeetb s, employeetb e left join department d on d.id = e.DepartmentID where e.SupervisorID = s.id" . $Name . $Email . $HireB . $HireA . $Sup . $Dept . $Sort; 
	 
	 $result = $link->query($sql);
	
	if ($result->num_rows > 0) {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {	
		echo "<div id='results'>";
		echo "<p class='count'>Count: " . $result->num_rows . " Record(s)</p>";
		echo "<table class='view'><tr><th>&nbsp;</th><th style='color:#FFFF00;'>ID</th><th>NAME</th><th>EMAIL</th><th>HIRE DATE</th><th>LAST MODIFIED DATE</th><th>SUPERVISOR</th><th>DEPARTMENT</th><th>TRAININGS</th></tr><tr><th>&nbsp;</th>
		<th><a href='view.php?direction=desc&col=ID&EnameS=" . $_POST["EnameS"] . "&EemailS=" . $_POST["EemailS"] . "&EhireBS=" . $_POST["EhireBS"] . "&EhireAS=" . $_POST["EhireAS"] . "&Esup=" . $_POST["Esup"] . "&Edept=" . $_POST["Edept"] . "'>
		<img src='img/sort_down.png'></a> <a href='view.php?direction=asc&col=ID&EnameS=" . $_POST["EnameS"] . "&EemailS=" . $_POST["EemailS"] . "&EhireBS=" . $_POST["EhireBS"] . "&EhireAS=" . $_POST["EhireAS"] . "&Esup=" . $_POST["Esup"] . "&Edept=" . $_POST["Edept"] . "'>
		<img src='img/sort_upy.png'></a></th><th><a href='view.php?direction=desc&col=Name&EnameS=" . $_POST["EnameS"] . "&EemailS=" . $_POST["EemailS"] . "&EhireBS=" . $_POST["EhireBS"] . "&EhireAS=" . $_POST["EhireAS"] . "&Esup=" . $_POST["Esup"] . "&Edept=" . $_POST["Edept"] . "'>
		<img src='img/sort_down.png'> <a href='view.php?direction=asc&col=Name&EnameS=" . $_POST["EnameS"] . "&EemailS=" . $_POST["EemailS"] . "&EhireBS=" . $_POST["EhireBS"] . "&EhireAS=" . $_POST["EhireAS"] . "&Esup=" . $_POST["Esup"] . "&Edept=" . $_POST["Edept"] . "'>
		<img src='img/sort_up.png'></a></th><th><a href='view.php?direction=desc&col=Email&EnameS=" . $_POST["EnameS"] . "&EemailS=" . $_POST["EemailS"] . "&EhireBS=" . $_POST["EhireBS"] . "&EhireAS=" . $_POST["EhireAS"] . "&Esup=" . $_POST["Esup"] . "&Edept=" . $_POST["Edept"] . "'>
		<img src='img/sort_down.png'></a> <a href='view.php?direction=asc&col=Email&EnameS=" . $_POST["EnameS"] . "&EemailS=" . $_POST["EemailS"] . "&EhireBS=" . $_POST["EhireBS"] . "&EhireAS=" . $_POST["EhireAS"] . "&Esup=" . $_POST["Esup"] . "&Edept=" . $_POST["Edept"] . "'><img src='img/sort_up.png'></a></th>
		<th><a href='view.php?direction=desc&col=HireDate&EnameS=" . $_POST["EnameS"] . "&EemailS=" . $_POST["EemailS"] . "&EhireBS=" . $_POST["EhireBS"] . "&EhireAS=" . $_POST["EhireAS"] . "&Esup=" . $_POST["Esup"] . "&Edept=" . $_POST["Edept"] . "'><img src='img/sort_down.png'></a> <a href='view.php?direction=asc&col=HireDate&EnameS=" . $_POST["EnameS"] . "&EemailS=" . $_POST["EemailS"] . "&EhireBS=" . $_POST["EhireBS"] . "&EhireAS=" . $_POST["EhireAS"] . "&Esup=" . $_POST["Esup"] . "&Edept=" . $_POST["Edept"] . "'><img src='img/sort_up.png'></a></th>
		<th><a href='view.php?direction=desc&col=LastModified&EnameS=" . $_POST["EnameS"] . "&EemailS=" . $_POST["EemailS"] . "&EhireBS=" . $_POST["EhireBS"] . "&EhireAS=" . $_POST["EhireAS"] . "&Esup=" . $_POST["Esup"] . "&Edept=" . $_POST["Edept"] . "'><img src='img/sort_down.png'></a> <a href='view.php?direction=asc&col=LastModified&EnameS=" . $_POST["EnameS"] . "&EemailS=" . $_POST["EemailS"] . "&EhireBS=" . $_POST["EhireBS"] . "&EhireAS=" . $_POST["EhireAS"] . "&Esup=" . $_POST["Esup"] . "&Edept=" . $_POST["Edept"] . "'><img src='img/sort_up.png'></a></th>
        <th><a href='view.php?direction=desc&col=SupervisorID&EnameS=" . $_POST["EnameS"] . "&EemailS=" . $_POST["EemailS"] . "&EhireBS=" . $_POST["EhireBS"] . "&EhireAS=" . $_POST["EhireAS"] . "&Esup=" . $_POST["Esup"] . "&Edept=" . $_POST["Edept"] . "'><img src='img/sort_down.png'></a> <a href='view.php?direction=asc&col=Supervisor&EnameS=" . $_POST["EnameS"] . "&EemailS=" . $_POST["EemailS"] . "&EhireBS=" . $_POST["EhireBS"] . "&EhireAS=" . $_POST["EhireAS"] . "&Esup=" . $_POST["Esup"] . "&Edept=" . $_POST["Edept"] . "'><img src='img/sort_up.png'></a></th>
        <th><a href='view.php?direction=desc&col=DepartmentID&EnameS=" . $_POST["EnameS"] . "&EemailS=" . $_POST["EemailS"] . "&EhireBS=" . $_POST["EhireBS"] . "&EhireAS=" . $_POST["EhireAS"] . "&Esup=" . $_POST["Esup"] . "&Edept=" . $_POST["Edept"] . "'><img src='img/sort_down.png'></a> <a href='view.php?direction=asc&col=Department&EnameS=" . $_POST["EnameS"] . "&EemailS=" . $_POST["EemailS"] . "&EhireBS=" . $_POST["EhireBS"] . "&EhireAS=" . $_POST["EhireAS"] . "&Esup=" . $_POST["Esup"] . "&Edept=" . $_POST["Edept"] . "'><img src='img/sort_up.png'></a></th><th>&nbsp;</th></tr>";
	} else {
		echo "<div id='results'>";
		echo "<p class='count'>Count: " . $result->num_rows . " Record(s)</p>";
		echo "<table class='view'><tr><th>&nbsp;</th>";
	    if ($_GET["col"] == 'ID') {
		  echo "<th style='color:#FFFF00;'>ID</th>";
		} else {
		  echo "<th>ID</th>";		
		}
		if ($_GET["col"] == 'Name') {
		  echo "<th style='color:#FFFF00;'>NAME</th>";
		} else {
		  echo "<th>NAME</th>";		
		}
		if ($_GET["col"] == 'Email') {
		  echo "<th style='color:#FFFF00;'>EMAIL</th>";
		} else {
		  echo "<th>EMAIL</th>";		
		}		
		if ($_GET["col"] == 'HireDate') {
		  echo "<th style='color:#FFFF00;'>HIRE DATE</th>";
		} else {
		  echo "<th>HIRE DATE</th>";		
		}	
		if ($_GET["col"] == 'LastModified') {
		  echo "<th style='color:#FFFF00;'>LAST MODIFIED</th>";
		} else {
		  echo "<th>LAST MODIFIED</th>";		
		}		
		if ($_GET["col"] == 'SupervisorID') {
		  echo "<th style='color:#FFFF00;'>SUPERVISOR</th>";
		} else {
		  echo "<th>SUPERVISOR</th>";		
		}		
		if ($_GET["col"] == 'DepartmentID') {
		  echo "<th style='color:#FFFF00;'>DEPARTMENT</th>";
		} else {
		  echo "<th>DEPARTMENT</th>";		
		}						
		echo "<th>TRAININGS</th></tr><tr><th>&nbsp;</th>
		<th><a href='view.php?direction=desc&col=ID&EnameS=" . $_GET["EnameS"] . "&EemailS=" . $_GET["EemailS"] . "&EhireBS=" . $_GET["EhireBS"] . "&EhireAS=" . $_GET["EhireAS"] . "&Esup=" . $_GET["Esup"] . "&Edept=" . $_GET["Edept"] . "'>";
	    if ($_GET["col"] == 'ID' and $_GET["direction"] == 'desc'  ) {
		  echo "<img src='img/sort_downy.png'>";
		} else {
		  echo "<img src='img/sort_down.png'>";		
		}		
		echo"</a> <a href='view.php?direction=asc&col=ID&EnameS=" . $_GET["EnameS"] . "&EemailS=" . $_GET["EemailS"] . "&EhireBS=" . $_GET["EhireBS"] . "&EhireAS=" . $_GET["EhireAS"] . "&Esup=" . $_GET["Esup"] . "&Edept=" . $_GET["Edept"] . "'>";
	    if ($_GET["col"] == 'ID' and $_GET["direction"] == 'asc'  ) {
		  echo "<img src='img/sort_upy.png'>";
		} else {
		  echo "<img src='img/sort_up.png'>";		
		}		
		echo"</a></th><th><a href='view.php?direction=desc&col=Name&EnameS=" . $_GET["EnameS"] . "&EemailS=" . $_GET["EemailS"] . "&EhireBS=" . $_GET["EhireBS"] . "&EhireAS=" . $_GET["EhireAS"] . "&Esup=" . $_GET["Esup"] . "&Edept=" . $_GET["Edept"] . "'>";
	    if ($_GET["col"] == 'Name' and $_GET["direction"] == 'desc'  ) {
		  echo "<img src='img/sort_downy.png'>";
		} else {
		  echo "<img src='img/sort_down.png'>";		
		}	
		echo "<a href='view.php?direction=asc&col=Name&EnameS=" . $_GET["EnameS"] . "&EemailS=" . $_GET["EemailS"] . "&EhireBS=" . $_GET["EhireBS"] . "&EhireAS=" . $_GET["EhireAS"] . "&Esup=" . $_GET["Esup"] . "&Edept=" . $_GET["Edept"] . "'>";
	    if ($_GET["col"] == 'Name' and $_GET["direction"] == 'asc'  ) {
		  echo "<img src='img/sort_upy.png'>";
		} else {
		  echo "<img src='img/sort_up.png'>";		
		}			
		echo "</a></th><th><a href='view.php?direction=desc&col=Email&EnameS=" . $_GET["EnameS"] . "&EemailS=" . $_GET["EemailS"] . "&EhireBS=" . $_GET["EhireBS"] . "&EhireAS=" . $_GET["EhireAS"] . "&Esup=" . $_GET["Esup"] . "&Edept=" . $_GET["Edept"] . "'>";
		if ($_GET["col"] == 'Email' and $_GET["direction"] == 'desc'  ) {
		  echo "<img src='img/sort_downy.png'>";
		} else {
		  echo "<img src='img/sort_down.png'>";		
		}	
		
		echo"</a> <a href='view.php?direction=asc&col=Email&EnameS=" . $_GET["EnameS"] . "&EemailS=" . $_GET["EemailS"] . "&EhireBS=" . $_GET["EhireBS"] . "&EhireAS=" . $_GET["EhireAS"] . "&Esup=" . $_GET["Esup"] . "&Edept=" . $_GET["Edept"] . "'>";
	    if ($_GET["col"] == 'Email' and $_GET["direction"] == 'asc'  ) {
		  echo "<img src='img/sort_upy.png'>";
		} else {
		  echo "<img src='img/sort_up.png'>";		
		}		
		echo"</a></th><th><a href='view.php?direction=desc&col=HireDate&EnameS=" . $_GET["EnameS"] . "&EemailS=" . $_GET["EemailS"] . "&EhireBS=" . $_GET["EhireBS"] . "&EhireAS=" . $_GET["EhireAS"] . "&Esup=" . $_GET["Esup"] . "&Edept=" . $_GET["Edept"] . "'>";
		if ($_GET["col"] == 'HireDate' and $_GET["direction"] == 'desc'  ) {
		  echo "<img src='img/sort_downy.png'>";
		} else {
		  echo "<img src='img/sort_down.png'>";		
		}	
		echo"</a> <a href='view.php?direction=asc&col=HireDate&EnameS=" . $_GET["EnameS"] . "&EemailS=" . $_GET["EemailS"] . "&EhireBS=" . $_GET["EhireBS"] . "&EhireAS=" . $_GET["EhireAS"] . "&Esup=" . $_GET["Esup"] . "&Edept=" . $_GET["Edept"] . "'>";
	    if ($_GET["col"] == 'HireDate' and $_GET["direction"] == 'asc'  ) {
		  echo "<img src='img/sort_upy.png'>";
		} else {
		  echo "<img src='img/sort_up.png'>";		
		}		
		echo"</a></th><th><a href='view.php?direction=desc&col=LastModified&EnameS=" . $_GET["EnameS"] . "&EemailS=" . $_GET["EemailS"] . "&EhireBS=" . $_GET["EhireBS"] . "&EhireAS=" . $_GET["EhireAS"] . "&Esup=" . $_GET["Esup"] . "&Edept=" . $_GET["Edept"] . "'>";
	    if ($_GET["col"] == 'LastModified' and $_GET["direction"] == 'desc'  ) {
		  echo "<img src='img/sort_downy.png'>";
		} else {
		  echo "<img src='img/sort_down.png'>";		
		}			
		echo"</a> <a href='view.php?direction=asc&col=LastModified&EnameS=" . $_GET["EnameS"] . "&EemailS=" . $_GET["EemailS"] . "&EhireBS=" . $_GET["EhireBS"] . "&EhireAS=" . $_GET["EhireAS"] . "&Esup=" . $_GET["Esup"] . "&Edept=" . $_GET["Edept"] . "'>";
	    if ($_GET["col"] == 'LastModified' and $_GET["direction"] == 'asc'  ) {
		  echo "<img src='img/sort_upy.png'>";
		} else {
		  echo "<img src='img/sort_up.png'>";		
		}	
		echo"</a></th><th><a href='view.php?direction=desc&col=SupervisorID&EnameS=" . $_GET["EnameS"] . "&EemailS=" . $_GET["EemailS"] . "&EhireBS=" . $_GET["EhireBS"] . "&EhireAS=" . $_GET["EhireAS"] . "&Esup=" . $_GET["Esup"] . "&Edept=" . $_GET["Edept"] . "'>";
	    if ($_GET["col"] == 'SupervisorID' and $_GET["direction"] == 'desc'  ) {
		  echo "<img src='img/sort_downy.png'>";
		} else {
		  echo "<img src='img/sort_down.png'>";		
		}			
		echo"</a> <a href='view.php?direction=asc&col=SupervisorID&EnameS=" . $_GET["EnameS"] . "&EemailS=" . $_GET["EemailS"] . "&EhireBS=" . $_GET["EhireBS"] . "&EhireAS=" . $_GET["EhireAS"] . "&Esup=" . $_GET["Esup"] . "&Edept=" . $_GET["Edept"] . "'>";
	    if ($_GET["col"] == 'SupervisorID' and $_GET["direction"] == 'asc'  ) {
		  echo "<img src='img/sort_upy.png'>";
		} else {
		  echo "<img src='img/sort_up.png'>";		
		}
		echo"</a></th><th><a href='view.php?direction=desc&col=DepartmentID&EnameS=" . $_GET["EnameS"] . "&EemailS=" . $_GET["EemailS"] . "&EhireBS=" . $_GET["EhireBS"] . "&EhireAS=" . $_GET["EhireAS"] . "&Esup=" . $_GET["Esup"] . "&Edept=" . $_GET["Edept"] . "'>";
	    if ($_GET["col"] == 'DepartmentID' and $_GET["direction"] == 'desc'  ) {
		  echo "<img src='img/sort_downy.png'>";
		} else {
		  echo "<img src='img/sort_down.png'>";		
		}			
		echo"</a> <a href='view.php?direction=asc&col=DepartmentID&EnameS=" . $_GET["EnameS"] . "&EemailS=" . $_GET["EemailS"] . "&EhireBS=" . $_GET["EhireBS"] . "&EhireAS=" . $_GET["EhireAS"] . "&Esup=" . $_GET["Esup"] . "&Edept=" . $_GET["Edept"] . "'>";
	    if ($_GET["col"] == 'DepartmentID' and $_GET["direction"] == 'asc'  ) {
		  echo "<img src='img/sort_upy.png'>";
		} else {
		  echo "<img src='img/sort_up.png'>";		
		}							
 		echo"</a></th><th>&nbsp;</th></tr>";
   }
		// output data of each row
		while($row = $result->fetch_assoc()) {
		    $training = TrainingCompliance($row["ID"]);
			if ($training == 'Compliant') {
			  $train = "<a href='edittraining.php?ID=" . $row["ID"] . "' title='Edit/View Trainings' style='text-decoration:underline;color:green;'>" . $training . "</a>";
			} else {
			  $train = "<a href='edittraining.php?ID=" . $row["ID"] . "' title='Edit/View Trainings' style='text-decoration:underline;color:red;'>" . $training . "</a>";
			}
			echo "<tr><td><a href='edit.php?Action=Delete&ID=" . $row["ID"] . "' title='Delect Record'><img src='img/delete.png'></a>&nbsp; <a href='edit.php?ID=" . $row["ID"] . "' title='Edit Record'><img src='img/pencil.png'></a></td><td>".$row["ID"]."</td><td>".$row["name"]."</td><td>".$row["Email"]."</td><td>" . $row["hire"] . "</td><td>" . $row["modified"] .  "</td><td>"  . $row["supname"] . "</td><td>" . $row["deptname"] . "</td><td>" . $train . "</td></tr>";
		}
		echo "</table></div>";
	} else {
		echo "<p class='count'>Count: 0 Record(s)</p>";
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
