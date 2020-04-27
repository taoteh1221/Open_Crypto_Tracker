<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 

$reset_result = array();
 
if ( $_POST['submit_reset'] ) {

/*
	// Run checks...
	
	if ( $securimage->check( $_POST['captcha_code'] ) == false )	{
	$reset_result['error'][] = "Captcha code was not correct.";
	}
	
	////////////////
	

	$query = "SELECT * FROM users WHERE email = '".$_POST['set_username']."'";
	
	if ($result = mysqli_query($db_connect, $query)) {
	   while ( $row = mysqli_fetch_array($result, MYSQLI_ASSOC) ) {
	   	
			$reset_key =  $row['reset_key'];
	   }
	mysqli_free_result($result);
	}
	   	if ( !$reset_key ) {
			$reset_result['error'][] = "No account exists with the email address '".$_POST['set_username']."'.";
			}
	

	// Checks cleared, send email ////////
	if ( sizeof($reset_result['error']) < 1 ) {

	
	$message = "

Please confirm your recent account password reset request for the email address ".$_POST['set_username'].". To reset your account password, please visit this link below:
https://".$_SERVER['SERVER_NAME']."/activate-account/".$reset_key."

If you did NOT request this password reset, you can ignore this message, and the account WILL NOT BE RESET.

Thanks,
-".$_SERVER['SERVER_NAME']." Support <".$from_email.">

";
	
	// Mail activation link
	$mail_result = safe_mail( $_POST['set_username'], "Please Confirm To Reset Your Account", $message);
	
	
		if ( $mail_result == true ) {
		$reset_result['success'][] = "An email has been sent to you for resetting your password. Please check your inbox (or spam folder and mark as 'not spam').";
		}
		elseif ( $mail_result['error'] != '' ) {
		$reset_result['error'][] = "Email validation error: " . $mail_result['error'];
		}
	
	
	}

*/

}


require("templates/interface/php/header.php");

?>

								
<div style="text-align: center;">

<h3 class='bitcoin'>Reset Admin Account</h3>


	<div style='font-weight: bold;' id='login_alert'>
<?php
	foreach ( $reset_result['error'] as $error ) {
	echo "<div class='red_bright' style='display: inline-block;  font-weight: bold; padding: 15px; margin: 15px; font-size: 21px; border: 4px dotted #ff4747;'> $error </div>";
	}
	
	foreach ( $reset_result['success'] as $success ) {
	echo "<div class='green_bright' style='display: inline-block;  font-weight: bold; padding: 15px; margin: 15px; font-size: 21px; border: 4px dotted #10d602;'> $success </div>";
	}
?>
	</div>


<?php

if ( !$_POST['submit_reset'] || sizeof($reset_result['error']) > 0 ) {
?>

				<form action='' method ='post'>
				
    <div style="display: inline-block; text-align: right; width: 350px;">

				<p><b>Admin Username:</b> <input type='text' name='set_username' value='<?=$_POST['set_username']?>' /></p>
				
    </div>

  	 
  	 
  	 <br clear='all' />
  
  
  	 <div class='align_center' style='display: inline-block;'>
  	 
  	 <p><img id='captcha_image' src='templates/interface/media/images/captcha.php' alt='' class='image_border' />
  	 <br />
  	 <a href='javascript: refreshImage("captcha_image", "templates/interface/media/images/captcha.php");' class='bitcoin' style='font-weight: bold;'>Get A Different Image</a>
  	 </p>
  	 
  	 </div>
  
  	 
  	 <br clear='all' />


  	 <div style="display: inline-block; text-align: right; width: 350px;">
  
  	 <p><b>Enter Text In Image:</b> <input type='text' name='captcha_code' id='captcha_code' value='' /></p>
	
	<p class='align_left' style='font-size: 19px; font-weight: bold; color: #ff4747;' id='captcha_alert'></p>
  
  	 </div>
  	 
  
  	 <br clear='all' />
				  
				<p style='padding: 20px;'><input type='submit' value='Reset Admin Account' /></p>
				
				<input type='hidden' name='submit_reset' value='1' />
				
				</form>
	
<?php
}
?>
</div>			


<?php
require("templates/interface/php/footer.php");
?>