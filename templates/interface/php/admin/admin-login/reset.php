<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */
 

$reset_result = array();


// If we are not activating an existing reset session, run checks before rendering anything...
if ( !$_GET['new_reset_key'] && !$_POST['admin_submit_reset'] ) {
	
	if ( validate_email($app_config['comms']['to_email']) != 'valid'  ) {
	$reset_result['error'][] = "A VALID admin's 'To' Email has NOT been properly set in the Admin Config yet, therefore the password CANNOT be reset by interface form submission. Alternatively, you can MANUALLY delete the file '/cache/secured/admin_login_XXXXXXXXXXXXX.dat' in the app directory. This will prompt you to create a new admin login, the next time you use the app.";
	$no_password_reset = 1;
	}
	
	if ( sizeof($stored_admin_login) != 2 ) {
	$reset_result['error'][] = "No admin account exists to reset.";
	$no_password_reset = 1;
	}
	
}
elseif ( $password_reset_denied ) {
$reset_result['error'][] = "Password reset key does not match.";
$no_password_reset = 1;
}




// Submitted reset request
if ( $_POST['admin_submit_reset'] ) {


	// Run more checks...
	
	if ( trim($_POST['reset_username']) == '' )	{
	$reset_result['error'][] = "Please fill in the username field.";
	$username_field_color = '#ff4747';
	}
	
	
	if ( trim($_POST['captcha_code']) == '' || trim($_POST['captcha_code']) != '' && strtolower( trim($_POST['captcha_code']) ) != strtolower($_SESSION['captcha_code']) )	{
	$reset_result['error'][] = "Captcha image code was not correct.";
	$captcha_field_color = '#ff4747';
	}
	
	
	

	// If checks clear, send email ////////
	if ( sizeof($reset_result['error']) < 1 && trim($_POST['reset_username']) != '' && trim($_POST['reset_username']) == $stored_admin_login[0] ) {

	$new_reset_key = random_hash(32);
	
	$message = "

Please confirm your request to reset the admin password for username '".$stored_admin_login[0]."', in your Open Crypto Portfolio Tracker application.

To complete resetting your admin password, please visit this link below:
". $base_url . "password-reset.php?new_reset_key=".$new_reset_key."

This link expires in 1 day.

If you did NOT request this password reset (originating from ip address ".$remote_ip."), you can ignore this message, and the account WILL NOT BE RESET.

";
	
  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
   $send_params = array(
          					'email' => array(
          											'subject' => 'Open Crypto Portfolio Tracker - Admin Password Reset',
     													'message' => $message
          											)
          					);
          	
   // Send notifications
   @queue_notifications($send_params);
          	
	store_file_contents($base_dir . '/cache/secured/activation/password_reset_' . random_hash(16) . '.dat', $new_reset_key); // Store password reset activation code, to confirm via clicked email link later

	
	}



	// Fake success message, even if the username was not found (so 3rd parties cannot hunt for a valid username)
	if ( sizeof($reset_result['error']) < 1 ) {
	$reset_result['success'][] = "IF THE USERNAME EXISTS, a message has been sent to the registered admin email address for resetting the admin password. Please check your inbox (or spam folder, and mark as 'not spam') in a few minutes.";
	}


}


$login_template = 1;
require("templates/interface/php/header.php");

?>

<script>


		var reset_notes = '<h5 class="align_center red_bright tooltip_title">Reset Admin Account By Username</h5>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="red_bright">For security purposes you MUST know the admin username, and a VALID admin \'To\' Email MUST be set in the Admin Config already. Otherwise the password CANNOT be reset by interface form submission. Alternatively, you can MANUALLY delete the file \'/cache/secured/admin_login_XXXXXXXXXXXXX.dat\' in the app directory. This will prompt you to create a new admin login, the next time you use the app.<br /></span></p>'
			
			+'<p> </p>';


</script>

								
<div style="text-align: center;">

<h3 class='bitcoin'>Reset Admin Account</h3>


	<div style='font-weight: bold;' id='login_alert'>
<?php
	foreach ( $reset_result['error'] as $error ) {
	echo "<br clear='all' /><div class='red_bright' style='display: inline-block;  font-weight: bold; padding: 15px; margin: 15px; font-size: 21px; border: 4px dotted #ff4747;'> $error </div>";
	}
	
	foreach ( $reset_result['success'] as $success ) {
	echo "<br clear='all' /><div class='green_bright' style='display: inline-block;  font-weight: bold; padding: 15px; margin: 15px; font-size: 21px; border: 4px dotted #10d602;'> $success </div>";
	}
	
	if ( sizeof($reset_result['success']) > 0 ) {
	echo "<p> <a href='".$base_url."'>Return To The Portfolio Main Page</a> </p>";
	}
?>
	</div>


<?php

if ( !$_POST['admin_submit_reset'] && !$no_password_reset || sizeof($reset_result['error']) > 0 && !$no_password_reset ) {
?>

				<form id='reset_admin' action='' method ='post'>
				
    <div style="display: inline-block; text-align: right; width: 400px;">

	 <p>			
	 
	 <img id='reset_notes' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative; left: 5px;' />  
	 
	 <b>Username:</b> <input type='text' name='reset_username' id='reset_username' value='<?=trim($_POST['reset_username'])?>' style='<?=( $username_field_color ? 'background: ' . $username_field_color : '' )?>' />
	 
		
	 <script>
	
			$('#reset_notes').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: reset_notes,
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
				  
				<p style='padding: 20px;'><input type='submit' value='Reset Admin Account' /></p>
				
				<input type='hidden' name='admin_submit_reset' value='1' />
				
				</form>
	
<?php
}
?>
</div>			


<?php
require("templates/interface/php/footer.php");
?>