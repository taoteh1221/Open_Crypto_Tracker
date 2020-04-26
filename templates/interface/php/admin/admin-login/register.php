<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


$register_result = array();

if ( $_POST['submit_registration'] ) {

	// Run checks...
	
	if ( isset($_POST['captcha_code']) && $securimage->check( $_POST['captcha_code'] ) == false ) {
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

<p class='red'>(<u>for security of this app</u>, please create a username / password for the Admin Configuration area)</p>


    <div style="display: inline-block; text-align: right; width: 350px;">

<?php

if ( !$_POST['submit_registration'] || sizeof($register_result['error']) > 0 ) {
?>

<form name='set_admin' action='' method='post'>

<p><b>Username:</b> <input type='text' id='set_username' name='set_username' value='<?=$_POST['set_username']?>' /></p>

<p><b>Password:</b> <input type='password' id='set_password' name='set_password' value='<?=$_POST['set_password']?>' /></p>

<p><b>Repeat Password:</b> <input type='password' id='set_password2' name='set_password2' value='<?=$_POST['set_password2']?>' /></p>


  <div>
    <?php
    	// Captcha
      $options = array();
      $options['input_name'] = 'captcha_code'; // change name of input element for form post
      $options['disable_flash_fallback'] = false; // allow flash fallback

      if (!empty($_SESSION['ctform']['captcha_error'])) {
        // error html to show in captcha output
        $options['error_html'] = $_SESSION['ctform']['captcha_error'];
      }

      echo "<div class='captcha_container'>\n";
      echo $securimage->getCaptchaHtml($options);
      echo "\n</div>\n";

    ?>
  </div>
<input type='hidden' name='submit_registration' value='1' />

</form>
  
<p style='padding: 20px;'>

<button class='force_button_style' onclick='

if ( check_pass("pass_alert", "set_password", "set_password2") == true && alphanumeric("user_alert", "set_username", "Username") == true ) {

var captcha_code = document.getElementById("captcha_code");
var captcha_alert = document.getElementById("captcha_alert");

var goodColor = "#10d602";
var badColor = "#ff4747";

	if ( captcha_code.value != "" ) {
		
	//console.log("OK to submit.");
	
	document.getElementById("login_alert").style.display = "none";
	document.getElementById("submit_alert").style.display = "inline-block";
	document.getElementById("submit_alert").innerHTML = "Creating your new admin login, please wait...";
	
   captcha_code.style.backgroundColor = goodColor;
   captcha_alert.style.color = goodColor;
   captcha_alert.innerHTML = "Captcha code included."
   
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
    	
    	
	<div style='font-weight: bold; color: #ff4747;' id='user_alert'></div>
	
	<div style='font-weight: bold; color: #ff4747;' id='pass_alert'></div>
	
	<div style='font-weight: bold; color: #ff4747;' id='captcha_alert'></div>

</div>


<?php
require("templates/interface/php/footer.php");
?>