<?php



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