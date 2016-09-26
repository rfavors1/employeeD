// JavaScript Document
function validateEmail(email) {
  var check = true;
    var atpos = email.indexOf("@");
    var dotpos = email.lastIndeemailOf(".");
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length) {
        check = false;
    }
	
	return check;
}

function validateName(name) 
{  
   return /^[A-z ]+$/.test(name);
}