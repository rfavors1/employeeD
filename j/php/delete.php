<?php
$results = "Employee Record deleted successfully.";
$url = parse_url("mysql://bd49b5ceb61b1f:edcd06f9@us-cdbr-iron-east-04.cleardb.net/heroku_c17a9191641ffc8?reconnect=true");
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);
// define variables and set to empty values

  $ID = validate($_POST["ID"]);
  $Name = validate($_POST["name"]);  
  $Email = validate($_POST["email"]);  
  $Hire = validate($_POST["hire"]);  
  
  $ID = intval($ID);
  $link = new mysqli($server,$username,$password,$db); 
  if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
  } 
  
  $sql = "INSERT INTO deletelog (DeleteID,ID,Name,Email,HireDate,DeleteDate) VALUES ('',$ID,'$Name','$Email','$Hire',now())";
  if ($link->connect_error) {
     $results = "Employee Record could not be added.";
  } 
  
  $sql = "DELETE from employee where ID = " . $ID;

  if (!($link->query($sql) === TRUE)) {
     $results = "Employee Record could not be deleted.";
  }
  
  $link->close();
  echo $results;

function validate($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
