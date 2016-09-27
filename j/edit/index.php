<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Edit Employee</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="../js/employee.js"></script>
<link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>
<?php
$url = parse_url("mysql://bd49b5ceb61b1f:edcd06f9@us-cdbr-iron-east-04.cleardb.net/heroku_c17a9191641ffc8?reconnect=true");
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);
// define variables and set to empty values
$Name = $Email = $Hire = "";

 
    $ID = validate($_GET["ID"]);
	$ID = intval($ID);
	if (!(is_integer($ID))) {
      echo "<script>location.replace('../edit/index.php?Action=Fail');</script>";
    } else {
	  $link = new mysqli($server,$username,$password,$db); 
	  if ($link->connect_error) {
		die("Connection failed: " . $link->connect_error);
	  } 
	  
	  $sql = "SELECT * FROM employee WHERE ID = " . $ID;
	  
	  $result = $link->query($sql);
		
	  if ($result->num_rows == 0) {
		 echo "<script>location.replace('../edit/index.php?Action=Fail');</script>";
	  } else {	   
	    while($row = $result->fetch_assoc()) {
	      $Name = $row["Name"];
	      $Email = $row["Email"];
	      $Hire = $row["HireDate"];
	    }
	  }  
     $link->close();
    }  
  
function validate($data) { //ensure proper data
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<div id="header">
<a href="../dashboard.html"><h1>Employee Dashboard</h1></a>
<div class="lang"><a href="../../dashboard.php" style="color: #0099FF;" title="PHP Version">PHP</a> <a href="../dashboard.html" style="color: #333333;text-decoration: underline;font-weight:bold;" title="JavaScript Version">JavaScript</a></div>
</div>
<div id="container">
<div id="right">
  <div id="top">
    <img src="../img/x-mark.png" class="close" style="margin-left: 175px;">  
    <img src="../img/rightarrow.png" class="open">  	
  </div>  
  <ul class="menu">
  <li><a href="../add"  class="active">Add Employee</a></li>
  <li><a href="../view">View Employee</a></li>  
  </ul>
</div>
<div id="left">
  <div id="editform">
  <?php 
  if($_GET["Action"] == 'Delete') {
    echo "<p class='error'>Are you sure want to delete this record? You will not be able to undo these changes.</p>";
  } elseif($_GET["Action"] == 'Fail') {
    echo "<p class='error'>Employee ID does not exist.</p>";
  }
  ?>
  <h3>Edit Employee</h3>
  <p>*All fields are required.</p>
  <form  method="post" name="updateE" onsubmit="return validateForm()">
    <p>Name: <input type="text" size=35 name="Ename" value="<?php echo $Name?>"><span class="name error"> <?php echo $NameError;?></span></p>
    <p>Email: <input type="text" size=50 name="Eemail" value="<?php echo $Email?>"><span class="email error"> <?php echo $EmailError;?></span></p>
    <p>Hiring Date: <input type="date" name="Ehire" value="<?php echo $Hire?>"><span class="hire error"> <?php echo $HireError;?></span></p>
	<input type="hidden" name="ID" value="<?php echo $ID?>">
	<input type="hidden" name="Oname" value="<?php echo $Name?>">
	<input type="hidden" name="Oemail" value="<?php echo $Email?>">
	<input type="hidden" name="Ohire" value="<?php echo $Hire?>">
    <?php 
    if($_GET["Action"] == 'Delete') {
	?>
    <p><input type="button" value="Delete" onClick="Delete(<?php echo $ID . ",'" . $Name . "','" . $Email . "','" . $Hire . "'"?>)"></p>
	<?php
    } else {
      if($_GET["Action"] != 'Fail') {	  
    ?>
    <p><input type="submit" value="Submit"></p>
	<?php
	  }
	}
    ?> 	
  </form>
  </div>
</div>
</div>
<script>
function validateForm() {
    var name = document.forms["updateE"]["Ename"].value;
	var email = document.forms["updateE"]["Eemail"].value;
    var hire = document.forms["updateE"]["Ehire"].value;
    var oname = document.forms["updateE"]["Oname"].value;
	var oemail = document.forms["updateE"]["Oemail"].value;
    var ohire = document.forms["updateE"]["Ohire"].value;	
	var id = document.forms["updateE"]["ID"].value;
	var NameError = "";
	var EmailError = "";
	var HireError = "";	

    if (name == null || name == "") {
		NameError = " Name is Required.";	
		$(".name").html(NameError);
	} else if (!validateName(name))	{
		NameError = " Invalid Name format.";	
		$(".name").html(NameError);
    }  else {
	   $(".name").html(NameError);
    } 	
  	alert(NameError);
    if (email == null || email == "") {
		EmailError = " Email is Required.";	
		$(".email").html(EmailError);
	} else if (!validateEmail(email))	{
		EmailError = " Invalid email format.";	
		$(".email").html(EmailError);
  }  else {
	   $(".email").html(EmailError);
  } 

    if (hire == null || hire == "") {
        HireError = " Hire Date is Required.";	
		$(".hire").html(HireError);
    }  else {
	   $(".name").html(HireError);
	} 	
	
	if (NameError || EmailError || HireError) {
	  return false;
	}	
	
	Update(id,name,email,hire,oname,oemail,ohire);
}
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

function Update(id,name,email,hire,oname,oemail,ohire) {
   var dataString = 'ID=' + id + '&Ename=' + name + '&Eemail=' + email + '&Ehire=' + hire + '&Oname=' + oname + '&Oemail=' + oemail + '&Ohire=' + ohire;
	$.ajax({
	type: "POST",
	url: "../php/edit.php",
	data: dataString,
	dataType: 'text',
    cache: false,
	success: function(data){
	  alert(data);
	  return false;
    }
	});
}

function Delete(id,name,email,hire) {
   var dataString = 'ID=' + id + '&name=' + name + '&email=' + email + '&hire=' + hire;
	$.ajax({
	type: "POST",
	url: "../php/delete.php",
	data: dataString,
	dataType: 'text',
	cache: false,
	success: function(data){
      alert(data);
	  if(data == "Employee Record deleted successfully.") {
	    location.replace("../view");
	  } else {
	    return false;
	  }
    }
	});
}
</script>
</body>
</html>
