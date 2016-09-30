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
  
  	 $sql = "SELECT e.id,e.name FROM employee e inner join department d on d.manager_id = e.id";
	 $result = $link->query($sql);
	 
	 while($row = $result->fetch_assoc()) {
	    //echo $row["id"] . " " . $row["name"];
		$new_array[$row['id']]['id'] = $row['id'];
        $new_array[$row['id']]['name'] = $row['name'];
	 }
/*	 foreach($new_array as $array)
{       
   echo $array['id'].'<br />';
   echo $array['name'].'<br />';
}*/
	 return $new_array;
}

//$result = employeeName();
//echo $result;	  
?>