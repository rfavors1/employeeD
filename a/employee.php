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

//add new hire training dates when employee added
function NewHireTraining() {

$url = parse_url("mysql://bd49b5ceb61b1f:edcd06f9@us-cdbr-iron-east-04.cleardb.net/heroku_c17a9191641ffc8?reconnect=true");
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

  $link = new mysqli($server,$username,$password,$db); 
  if ($link->connect_error) {
		die("Connection failed: " . $link->connect_error);
  } 
  
  	 $sql = "SELECT max(e.id) as max_id, e.hiredate as hire_date FROM employeetb e";
	
	 $result = $link->query($sql);
	 
	 while($row = $result->fetch_assoc()) {
		$id = $row['max_id'];
        $hire = $row['hire_date'];
	 }
    echo $id;
    echo $hire;
  
  	$training = Training();
	foreach ($training as $value) {
	  $i = $value["id"];
	  $d = $value["days_due"];
	}
  mysqli_close($link);
}

NewHireTraining();
//echo $id;
//Return list of department names
function departmentName() {

$url = parse_url("mysql://bd49b5ceb61b1f:edcd06f9@us-cdbr-iron-east-04.cleardb.net/heroku_c17a9191641ffc8?reconnect=true");
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

  $link = new mysqli($server,$username,$password,$db); 
  if ($link->connect_error) {
		die("Connection failed: " . $link->connect_error);
  } 
  
  	 $sql = "SELECT d.id,d.name FROM department d";
	 $result = $link->query($sql);
	 
	 while($row = $result->fetch_assoc()) {
		$new_array[$row['id']]['id'] = $row['id'];
        $new_array[$row['id']]['name'] = $row['name'];
	 }

     mysqli_close($link);
	 return $new_array;
}

//Return list of trainings
function Training() {

$url = parse_url("mysql://bd49b5ceb61b1f:edcd06f9@us-cdbr-iron-east-04.cleardb.net/heroku_c17a9191641ffc8?reconnect=true");
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

  $link = new mysqli($server,$username,$password,$db); 
  if ($link->connect_error) {
		die("Connection failed: " . $link->connect_error);
  } 
  
  	 $sql = "SELECT t.id,t.days_due FROM training t";
	 $result = $link->query($sql);
	 
	 while($row = $result->fetch_assoc()) {
		$new_array[$row['id']]['id'] = $row['id'];
        $new_array[$row['id']]['days_due'] = $row['days_due'];
	 }

     mysqli_close($link);
	 return $new_array;
}

//return list of supervor names
function employeeName() {

$url = parse_url("mysql://bd49b5ceb61b1f:edcd06f9@us-cdbr-iron-east-04.cleardb.net/heroku_c17a9191641ffc8?reconnect=true");
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

  $link = new mysqli($server,$username,$password,$db); 
  if ($link->connect_error) {
		die("Connection failed: " . $link->connect_error);
  } 
  
  	 $sql = "SELECT e.id,e.name FROM employeetb e inner join department d on d.manager_id = e.id order by e.name";
	 $result = $link->query($sql);
	 
	 while($row = $result->fetch_assoc()) {
		$new_array[$row['id']]['id'] = $row['id'];
        $new_array[$row['id']]['name'] = $row['name'];
	 }
     
	 mysqli_close($link);
	 return $new_array;
}

?>
</body>
</html>