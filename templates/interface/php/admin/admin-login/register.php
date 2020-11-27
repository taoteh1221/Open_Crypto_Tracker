<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


$register_result = array();

	
if ( !$_GET['new_reset_key'] && !$_POST['admin_submit_register'] && sizeof($stored_admin_login) == 2 && validate_email($app_config['comms']['to_email']) == 'valid' ) {
$register_result['error'][] = "An admin login already exists, and you HAVE properly added a VALID 'To' email in the communications configuration. Try <a href='password-reset.php' class='red_bright'>resetting your password</a> instead.";
}
	
	
if ( $_POST['admin_submit_register'] ) {

	// Run checks...
	
	if ( valid_username( trim($_POST['set_username']) ) != 'valid' ) {
	$register_result['error'][] = valid_username( trim($_POST['set_username']) );
	$username_field_color = '#ff4747';
	}
	
	//////////////
	
	////////////////
	
	
	if ( password_strength($_POST['set_password'], 12, 40) != 'valid'  ) {
	$register_result['error'][] = password_strength($_POST['set_password'], 12, 40);
	$password_field_color = '#ff4747';
	}
	
	
	///////////////
	
	
	if ($_POST['set_password'] != $_POST['set_password2']  ) {
	$register_result['error'][] = "Passwords do not match.";
	$password2_field_color = '#ff4747';
	}
	
	
	///////////////
	
	
	if ( trim($_POST['captcha_code']) == '' || trim($_POST['captcha_code']) != '' && strtolower( trim($_POST['captcha_code']) ) != strtolower($_SESSION['captcha_code']) ) {
	$register_result['error'][] = "Captcha image code was not correct.";
	$captcha_field_color = '#ff4747';
	}
	

}


$login_template = 1;
require("templates/interface/php/header.php");

?>

<div style="text-align: center;">

	<div id='login_alert'>
<?php
	foreach ( $register_result['error'] as $error ) {
	echo "<br clear='all' /><div class='red_bright' style='display: inline-block;  font-weight: bold; padding: 15px; margin: 15px; font-size: 21px; border: 4px dotted #ff4747;'> $error </div>";
	}


	foreach ( $register_result['success'] as $success ) {
	echo "<br clear='all' /><div class='green_bright' style='display: inline-block;  font-weight: bold; padding: 15px; margin: 15px; font-size: 21px; border: 4px dotted #10d602;'> $success </div>";
	}
?>
	</div>
	
	<div class='green_bright' style='display: none; font-weight: bold; padding: 15px; margin: 15px; font-size: 21px; border: 4px dotted #10d602;' id='submit_alert'></div>

<?php
if ( $_GET['new_reset_key'] ) {
?>

<h3 class='bitcoin'>Admin Login Reset</h3>

<p class='red' style='font-size: 19px;'>Reset your username / password for the Admin Config area.</p>

<?php
}
else {
?>

<h3 class='bitcoin'>Admin Login Creation</h3>

<p class='red' style='font-size: 19px;'>Create a username / password to secure the Admin Config area.</p>

<?php
}
?>


<?php

if ( !$_POST['submit_registration'] || sizeof($register_result['error']) > 0 ) {
?>

<script>


		var username_notes = '<h5 align="center" class="red_bright tooltip_title">Username Format Requirements</h5>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="red_bright">All lower case<br />Starts with a letter<br />Numbers allowed <br />No symbols <br />No spaces <br />Between 4 - 30 characters <br /></span></p>'
			
			+'<p> </p>';



		var password_notes = '<h5 align="center" class="red_bright tooltip_title">Password Format Requirements</h5>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="red_bright">At least one upper case letter<br />At least one lower case letter<br />At least one number <br />At least one symbol <br />No spaces <br />Between 12 - 40 characters <br /></span></p>'
			
			+'<p> </p>';


</script>

<form name='set_admin' id='set_admin' action='' method='post'>


    <div style="display: inline-block; text-align: right; width: 400px;">
    
	 <p>
	 
	 <img id='username_notes' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative; left: 5px;' />  
	 
	 <b>Username:</b> 
	 
	 <input type='text' id='set_username' name='set_username' value='<?=trim($_POST['set_username'])?>' style='<?=( $username_field_color ? 'background: ' . $username_field_color : '' )?>' />
	 
		
	 <script>
	
			$('#username_notes').balloon({
			html: true,
			position: "left",
			contents: username_notes,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		 </script>
		 
	 </p>

	 <p>
	 
	 <img id='password_notes' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative; left: 5px;' /> 
	 
	 <b>Password:</b> 
	 	 
	 <input type='password' id='set_password' name='set_password' value='<?=$_POST['set_password']?>' style='<?=( $password_field_color ? 'background: ' . $password_field_color : '' )?>' />
	 
		
	 <script>
	
			$('#password_notes').balloon({
			html: true,
			position: "left",
			contents: password_notes,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		 </script>
		 
	 </p>

	 <p><b>Repeat Password:</b> <input type='password' id='set_password2' name='set_password2' value='<?=$_POST['set_password2']?>' style='<?=( $password2_field_color ? 'background: ' . $password2_field_color : '' )?>' /></p>
    	
    	
		<p class='align_left' style='font-size: 19px; font-weight: bold; color: #ff4747;' id='user_alert'></p>
	
		<p class='align_left' style='font-size: 19px; font-weight: bold; color: #ff4747;' id='pass_alert'></p>
		
    </div>
  	 
  	 
  	 <br clear='all' />
  
  
  	 <div class='align_center' style='display: inline-block;'>
  	 
  	 <p><img id='captcha_image' src='templates/interface/media/images/captcha.php' alt='' class='image_border' />
  	 <br />
  	 <a href='javascript: refreshImage("captcha_image", "templates/interface/media/images/captcha.php");' class='bitcoin' style='font-weight: bold;' title='CAPTCHA image text contrast can be adjusted in Admin Config, within the "Power User" section.'>Get A Different Image</a>
  	 </p>
  	 
  	 </div>
  
  	 
  	 <br clear='all' />


  	 <div style="display: inline-block; text-align: right; width: 400px;">
  
  	 <p><b>Enter Image Text:</b> <input type='text' name='captcha_code' id='captcha_code' value='' style='<?=( $captcha_field_color ? 'background: ' . $captcha_field_color : '' )?>' /></p>
	
	<p class='align_left' style='font-size: 19px; font-weight: bold; color: #ff4747;' id='captcha_alert'></p>
  
  	 </div>
  	 
  
  	 <br clear='all' />
  
  
<input type='hidden' name='admin_submit_register' value='1' />

</form>
  
    
<p style='padding: 20px;'>

<button id='admin_register_button' class='force_button_style' onclick='

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
   
   document.getElementById("admin_register_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
	document.getElementById("admin_register_button").disable = true;
	$("#set_admin").submit(); // Triggers "app reloading" sequence
	}
	else {
		
   captcha_code.style.backgroundColor = badColor;
   captcha_alert.style.color = badColor;
   captcha_alert.innerHTML = "Captcha code MUST be included.";
   
	}

}

'><?=( $_GET['new_reset_key'] ? 'Reset' : 'Create' )?> Admin Login</button>

</p>


<?php
}

?>


</div>


<?php
require("templates/interface/php/footer.php");
?>