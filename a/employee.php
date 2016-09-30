<?php

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
  
  	 $sql = "SELECT id,name FROM employee e left join department d on d.manager_id = e.id";
	 $result = $link->query($sql);
	 
	 while($row = $result->fetch_assoc()) {
	   array_push($rows,$row);
	 }
	 
	 return $rows;
}
	  
?>