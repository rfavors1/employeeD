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
  
  	 $sql = "SELECT max(e.id),e.hiredate FROM employeetb e";
	 echo $sql;
	 $result = $link->query($sql);
	 
	 while($row = $result->fetch_assoc()) {
		$id = $row['ID'];
		echo $id;
        $hire = $row['HireDate'];
	 }

  	 $sql = "SELECT id,days_due FROM training";
	 $result = $link->query($sql);
	 
  	 while($row = $result->fetch_assoc()) {
		$training_id = $row['id'];
		echo "training: " . $training_id;
		$days = $row['days_due'];
		$interval = $days . " days";
		$date = $hire;
		date_add($date,date_interval_create_from_date_string($interval));
		echo "Date: " . $date;
        $sql = "INSERT INTO employee_training (id,employee_id,training_id,due_date,completed) VALUES ('','$id','$training_id','$date','0')";

        $link->query($sql)
	}	
	 
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

	 return $new_array;
}

?>
</body>
</html>