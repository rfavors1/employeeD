<?php
$results = "Employee record updated successfully.";
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
  $ID = validate($_POST["ID"]);
  $ID = intval($ID);

  $link = new mysqli($server,$username,$password,$db); 
  if ($link->connect_error) {
     die("Connection failed: " . $link->connect_error);
  } 
  
   $sql = "update employee set Name='$Name',Email='$Email',HireDate='$Hire',LastModified=now() where ID=$ID";

  if (!($link->query($sql) === TRUE)) {
    $result = "Employee record was not updated. Please Try again.";
  }
  
  //check to see if name changed, if so add to change log
  if ($_POST["Oname"] != $Name) {
    $sql = "INSERT INTO changelog (ChangeID,ID,Field,OldValue,NewValue,ChangeDate) VALUES ('',$ID,'Name','" . $_POST["Oname"] . "','$Name',now())";
	$link->query($sql);
  }
  
  //check to see if email has changed, if so add to change log
  if ($_POST["Oemail"] != $Email) {
    $sql = "INSERT INTO changelog (ChangeID,ID,Field,OldValue,NewValue,ChangeDate) VALUES ('',$ID,'Email','" . $_POST["Oemail"] . "','$Email',now())";
	$link->query($sql);
  }  

  //check to see if hire date has changed, if so add to change log
  if ($_POST["Ohire"] != $Hire) {
    $sql = "INSERT INTO changelog (ChangeID,ID,Field,OldValue,NewValue,ChangeDate) VALUES ('',$ID,'HireDate','" . $_POST["Ohire"] . "','$Hire',now())";
	$link->query($sql);
  };

  echo $results;



function validate($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
