<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Delete Employee</title>
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
  $link->query($sql);  
  
  $sql = "DELETE from employee where ID = " . $ID;

  $link->query($sql);
  
  $link->close();

function validate($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
</div>
</body>
</html>
