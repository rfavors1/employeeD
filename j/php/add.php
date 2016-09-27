<?php
$results["status"] = 1;
$results["error"] = '';
$url = parse_url("mysql://bd49b5ceb61b1f:edcd06f9@us-cdbr-iron-east-04.cleardb.net/heroku_c17a9191641ffc8?reconnect=true");
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);
// define variables and set to empty values
$Name = $Email = $Hire = "";

  $Name = validate($_POST["Ename"]);
  $Email = validate($_POST["Eemail"]);
  $Hire = validate($_POST["Ehire"]);

  $link = new mysqli($server,$username,$password,$db); 
  if ($link->connect_error) {
     $results["status"] = 0;
     $results["error"] = "Employee Record could not be added.";
  } 
  
  $sql = "INSERT INTO employee (ID,Name,Email,HireDate,LastModified) VALUES ('','$Name','$Email','$Hire',now())";

  if (!($link->query($sql) === TRUE)) {
     $results["status"] = 0;
     $results["error"] = "Employee Record could not be added.";
  }
  
  $link->close();

  $results = json_encode($results);
  echo $sresults;  



function validate($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
