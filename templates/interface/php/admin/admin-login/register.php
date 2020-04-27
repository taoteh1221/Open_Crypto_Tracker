<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


$register_result = array();

if ( $_POST['submit_registration'] ) {

	// Run checks...
	
	if ( trim($_POST['captcha_code']) != '' && strtolower($_POST['captcha_code']) != strtolower($_SESSION['captcha_code']) ) {
	$register_result['error'][] = "Captcha code was not correct.";
	}
	
	//////////////
	
	if ( $admin_login ) {
	$register_result['error'][] = "An admin login already exists. If you have added your to / from emails in the communications configuration, try resetting your password instead.";
	}
	
	
	////////////////
	
	
	if ( strlen( $_POST['set_password'] ) < 12 || strlen( $_POST['set_password'] ) > 40 ) {
	$register_result['error'][] = "Password must be between 12 and 40 characters long. Please choose a different password.";
	}
	
	
	///////////////
	

	//var_dump($register_result['error']);  // DEBUGGING


}


require("templates/interface/php/header.php");

?>

<div style="text-align: center;">

	<div id='login_alert'>
<?php
	foreach ( $register_result['error'] as $error ) {
	echo "<div class='red_bright' style='display: inline-block;  font-weight: bold; padding: 15px; margin: 15px; font-size: 21px; border: 4px dotted #ff4747;'> $error </div>";
	}


	foreach ( $register_result['success'] as $success ) {
	echo "<div class='green_bright' style='display: inline-block;  font-weight: bold; padding: 15px; margin: 15px; font-size: 21px; border: 4px dotted #10d602;'> $success </div>";
	}
?>
	</div>
	
	<div class='green_bright' style='display: none; font-weight: bold; padding: 15px; margin: 15px; font-size: 21px; border: 4px dotted #10d602;' id='submit_alert'></div>

<h3 class='bitcoin'>Admin Login Creation</h3>

<p class='red' style='font-size: 19px;'><u>For security of this app</u>, please create a username / password for the Admin Configuration area.</p>


<?php

if ( !$_POST['submit_registration'] || sizeof($register_result['error']) > 0 ) {
?>

<form name='set_admin' action='' method='post'>


    <div style="display: inline-block; text-align: right; width: 350px;">
    
	 <p><b>Username:</b> <input type='text' id='set_username' name='set_username' value='<?=$_POST['set_username']?>' /></p>

	 <p><b>Password:</b> <input type='password' id='set_password' name='set_password' value='<?=$_POST['set_password']?>' /></p>

	 <p><b>Repeat Password:</b> <input type='password' id='set_password2' name='set_password2' value='<?=$_POST['set_password2']?>' /></p>
    	
    	
		<p class='align_left' style='font-size: 19px; font-weight: bold; color: #ff4747;' id='user_alert'></p>
	
		<p class='align_left' style='font-size: 19px; font-weight: bold; color: #ff4747;' id='pass_alert'></p>
		
    </div>
  	 
  	 
  	 <br clear='all' />
  
  
  	 <div class='align_center' style='display: inline-block;'>
  	 
  	 <p><img src='templates/interface/media/images/captcha.php' alt='' class='image_border' /></p>
  	 
  	 </div>
  
  	 
  	 <br clear='all' />


  	 <div style="display: inline-block; text-align: right; width: 350px;">
  
  	 <p><b>Enter the text above:</b> <input type='text' name='captcha_code' id='captcha_code' value='' /></p>
	
	<p class='align_left' style='font-size: 19px; font-weight: bold; color: #ff4747;' id='captcha_alert'></p>
  
  	 </div>
  	 
  
  	 <br clear='all' />
  
  
<input type='hidden' name='submit_registration' value='1' />

</form>
  
    
<p id='admin_register_button' style='padding: 20px;'>

<button class='force_button_style' onclick='


// Remove any previous submission error notices (captcha error etc)
document.getElementById("login_alert").style.display = "none";

if ( alphanumeric("user_alert", "set_username", "Username") == true && check_pass("pass_alert", "set_password", "set_password2") == true ) {

var captcha_code = document.getElementById("captcha_code");
var captcha_alert = document.getElementById("captcha_alert");

var goodColor = "#10d602";
var badColor = "#ff4747";

	if ( captcha_code.value != "" ) {
		
	//console.log("OK to submit.");
	document.getElementById("submit_alert").style.display = "inline-block";
	document.getElementById("submit_alert").innerHTML = "Creating your new admin login, please wait...";
	
   captcha_code.style.backgroundColor = goodColor;
   captcha_alert.style.color = goodColor;
   captcha_alert.innerHTML = "Captcha code included."
   
   document.getElementById("admin_register_button").innerHTML = ajax_placeholder(30, "Submitting...");
   
	document.set_admin.submit();
	}
	else {
		
   captcha_code.style.backgroundColor = badColor;
   captcha_alert.style.color = badColor;
   captcha_alert.innerHTML = "Captcha code MUST be included.";
   
	}

}

'>Create Admin Login</button>

</p>


<?php
}

?>


</div>


<?php
require("templates/interface/php/footer.php");
?>