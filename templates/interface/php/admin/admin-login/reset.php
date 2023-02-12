<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
 

$is_admin = true;

$is_login_form = true;

$reset_result = array();


// If we are not activating an existing reset session, run checks before rendering anything...
if ( !$_GET['new_reset_key'] && !$_POST['admin_submit_reset'] ) {
	
	if ( $ct_gen->valid_email($ct_conf['comms']['to_email']) != 'valid'  ) {
	$reset_result['error'][] = "A VALID admin's 'To' Email has NOT been properly set in the Admin Config yet, therefore the password CANNOT be reset by interface form submission. Alternatively, you can MANUALLY delete the file '/cache/secured/admin_login_XXXXXXXXXXXXX.dat' in the app directory. This will prompt you to create a new admin login, the next time you use the app.";
	$no_password_reset = 1;
	}
	
	if ( !is_array($stored_admin_login) ) {
	$reset_result['error'][] = "No admin account exists to reset.";
	$no_password_reset = 1;
	}
	
}
elseif ( $password_reset_denied ) {
$reset_result['error'][] = "Password reset key does not match or exist.";
$no_password_reset = 1;
}




// Submitted reset request
if ( $_POST['admin_submit_reset'] && !$no_password_reset ) {


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
	if ( 
	!is_array($reset_result['error']) && trim($_POST['reset_username']) != '' && trim($_POST['reset_username']) == $stored_admin_login[0]
	|| is_array($reset_result['error']) && sizeof($reset_result['error']) < 1 && trim($_POST['reset_username']) != '' && trim($_POST['reset_username']) == $stored_admin_login[0]
	) {

	$new_reset_key = $ct_gen->rand_hash(32);
	
	$msg = "

Please confirm your request to reset the admin password for username '".trim($_POST['reset_username'])."', in your Open Crypto Tracker application.

To complete resetting your admin password, please visit this link below:
". $base_url . "password-reset.php?new_reset_key=".$new_reset_key."

This link expires in 1 day, or after you use it successfully (whichever comes first).

If you did NOT request this password reset (originating from ip address ".$remote_ip."), you can ignore this message, and the account WILL NOT BE RESET.

";
	
  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
   $send_params = array(
          					'email' => array(
          											'subject' => 'Open Crypto Tracker - Admin Password Reset',
     													'message' => $msg
          											)
          					);
          	
   // Send notifications
   @$ct_cache->queue_notify($send_params);
          	
	$ct_cache->save_file($base_dir . '/cache/secured/activation/password_reset_' . $ct_gen->rand_hash(16) . '.dat', $new_reset_key); // Store password reset activation code, to confirm via clicked email link later

	
	}



	// Fake success message, even if the username was not found (so 3rd parties cannot hunt for a valid username)
	if ( !is_array($reset_result['error']) || is_array($reset_result['error']) && sizeof($reset_result['error']) < 1 ) {
	$reset_result['success'][] = "IF THE USERNAME EXISTS, a message has been sent to the registered admin email address for resetting the admin password. Please check your inbox (or spam folder, and mark as 'not spam') in a few minutes.";
	}


}


$login_template = 1;
require("templates/interface/php/header.php");

?>



<script>

// If we are in an iframe, break out of it
this.top.location !== this.location && (this.top.location = this.location);


var admin_cookies = '<h5 class="align_center bitcoin tooltip_title">Admin Login Requires Browser Cookies</h5>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="bitcoin">For greater security after a SUCCESSFUL admin login (with the correct username and password), a 32-byte random key is saved inside a cookie in your web browser. A DIFFERENT 32-byte random key is saved on the app server in temporary session data, along with the result of concatenating the two 32-byte keys together and getting a digest (fingerprint) hash, which is your login authorization.</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="bitcoin">Whenever you visit the Admin Config page / view the "Admin Config - Quick Links" on the Portfolio page / etc, the app asks your browser for it\s 32-byte key to prove you are logged in.</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="bitcoin">By splitting the secured login credentials between your web browser cookie data and the app server\'s temporary session data, it makes it a bit harder for a hacker to view your login area, at least if your app server automatically clears all it\'s temporary session data a few times per day (this app attempts to clear your session data EVERY 6 HOURS).</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="bitcoin">REGARDLESS as to whether your particular app server automatically clears it\'s temporary session data or not, whenever you logout the 32-byte key in your browser is deleted, along with all the session data on the app server.</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="bitcoin">If your app server DOES automatically clears session data often, you will also be logged out AUTOMATICALLY at that time. ADDITIONALLY, the 32-byte random key that is saved inside a cookie in your web browser EXPIRES (automatically deletes itself) AFTER <?=$ct_conf['sec']['admin_cookie_expire']?> HOURS (adjustable in the Admin Config SECURITY section).</span></p>'
			
			
			+'<p> </p>';
			

		var reset_notes = '<h5 class="align_center red tooltip_title">Reset Admin Account By Username</h5>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="red">For security purposes you MUST know the CURRENT admin username, and a VALID admin \'To\' Email MUST be set in the Admin Config already. Otherwise the password CANNOT be reset by interface form submission. Alternatively, you can MANUALLY delete the file \'/cache/secured/admin_login_XXXXXXXXXXXXX.dat\' in the app directory. This will prompt you to create a new admin login, the next time you use the app.<br /></span></p>'
			
			+'<p> </p>';


</script>

								
<div style="text-align: center;">

<h3 class='bitcoin'>Reset Admin Account</h3>

<p class='bitcoin' style='font-size: 19px; font-weight: bold;'>Cookies MUST be enabled in your browser to login.
	 <img id='admin_cookies' src='templates/interface/media/images/info-orange.png' alt='' width='30' style='position: relative;' /> 
	 </p>


<script>

if ( get_cookie('priv_toggle') == 'on' ) {

document.write("<p class='red align_center' style='font-size: 19px; font-weight: bold;'>"

+ "PRIVACY MODE MUST BE DISABLED to submit data: "

+ "<span id='pm_link' class='bitcoin' onclick='privacy_mode(true);' title=''>Disable Privacy Mode</span>"

+ "</p>");
	 
}

</script>



	 <script>
	
			$('#admin_cookies').balloon({
			html: true,
			position: "bottom",
  			classname: 'balloon-tooltips',
			contents: admin_cookies,
			css: {
					fontSize: "<?=$default_font_size?>em",
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

	<div style='font-weight: bold;' id='login_alert'>
<?php

	foreach ( $reset_result['error'] as $error ) {
	echo "<br clear='all' /><div class='red admin_login_alerts' style='border: 4px dotted #ff4747;'> $error </div>";
	}
	
	foreach ( $reset_result['success'] as $success ) {
	echo "<br clear='all' /><div class='green_bright admin_login_alerts' style='border: 4px dotted #10d602;'> $success </div>";
	}
	
?>
	</div>


<?php

if (
!$_POST['admin_submit_reset'] && !$no_password_reset
|| is_array($reset_result['error']) && sizeof($reset_result['error']) > 0 && !$no_password_reset
) {
?>
 
	<form id='reset_admin' action='' method ='post'>
				
    <div style="display: inline-block; text-align: right; width: 400px;">

	 <p>			
	 
	 <img id='reset_notes' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative; left: 5px;' />  
	 
	 <b>CURRENT Username:</b> <input type='text' name='reset_username' id='reset_username' value='<?=trim($_POST['reset_username'])?>' style='<?=( $username_field_color ? 'background: ' . $username_field_color : '' )?>' />
	 
		
	 <script>
	
			$('#reset_notes').balloon({
			html: true,
			position: "bottom",
  			classname: 'balloon-tooltips',
			contents: reset_notes,
			css: {
					fontSize: "<?=$default_font_size?>em",
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
  	 
  	 <p><img id='captcha_image' src='templates/interface/media/images/captcha.php' alt='' title='CAPTCHA image text contrast / maximum angle can be adjusted in Admin Config, within the "Security" section.

Custom TTF fonts can be automatically added by placing them in the /templates/interface/media/fonts/ folder.

Google Fonts is supported (fonts.google.com).' class='image_border' />
  	 <br />
  	 <a href='javascript: refresh_image("captcha_image", "templates/interface/media/images/captcha.php");' class='bitcoin' style='font-weight: bold;' title='CAPTCHA image text contrast / maximum angle can be adjusted in Admin Config, within the "Security" section.

Custom TTF fonts can be automatically added by placing them in the /templates/interface/media/fonts/ folder.

Google Fonts is supported (fonts.google.com).'>Get A Different Image</a>
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



<p style='font-weight: bold;'> <a href='<?=$base_url?>'>Return To The Portfolio Main Page</a> </p>


</div>			


<?php
require("templates/interface/php/footer.php");
?>