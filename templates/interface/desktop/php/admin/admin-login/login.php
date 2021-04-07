<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


$login_result = array();
		
if ( $_POST['admin_submit_login'] ) {
	
	if ( trim($_POST['captcha_code']) == '' || trim($_POST['captcha_code']) != '' && strtolower( trim($_POST['captcha_code']) ) != strtolower($_SESSION['captcha_code']) )	{
	$login_result['error'][] = "Captcha image code was not correct.";
	$captcha_field_color = '#ff4747';
	}
	else {
				
				// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
				if ( $pt_gen->id() != false && isset($_SESSION['nonce']) && trim($_POST['admin_username']) != '' && $_POST['admin_password'] != '' 
				&& $_POST['admin_username'] == $stored_admin_login[0] && $pt_gen->check_pepper_hashed_pass($_POST['admin_password'], $stored_admin_login[1]) == true ) {
					
				// Login now (set admin security cookie / 'auth_hash' session var), before redirect
				
				// WE SPLIT THE LOGIN AUTH BETWEEN COOKIE AND SESSION DATA (TO BETTER SECURE LOGIN AUTHORIZATION)
				
				$cookie_nonce = $pt_gen->rand_hash(32); // 32 byte
		
				$pt_gen->store_cookie('admin_auth_' . $pt_gen->id(), $cookie_nonce, mktime() + ($pt_conf['power']['admin_cookie_expire'] * 3600) );
				
				$_SESSION['admin_logged_in']['auth_hash'] = $pt_gen->admin_hashed_nonce($cookie_nonce, 'force'); // Force set, as we're not logged in fully yet
				
				header("Location: admin.php");
				exit;
				
				}
				else {
				$login_result['error'][] = "Wrong username / password.";
				$_POST['admin_username'] = '';
				$_POST['admin_password'] = '';
				}
			

	}
	
}


$login_template = 1;
require("templates/interface/desktop/php/header.php");

?>


<script>


		var admin_cookies = '<h5 class="align_center bitcoin tooltip_title">Admin Login Requires Browser Cookies</h5>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="bitcoin">For greater security after a SUCCESSFUL admin login (with the correct username and password), a 32-byte random key is saved inside a cookie in your web browser. A DIFFERENT 32-byte random key is saved on the app server in temporary session data, along with the result of concatenating the two 32-byte keys together and getting a digest (fingerprint) hash, which is your login authorization.</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="bitcoin">Whenever you visit the Admin Config page / view the "Admin Config - Quick Links" on the Portfolio page / etc, the app asks your browser for it\s 32-byte key to prove you are logged in.</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="bitcoin">By splitting the secured login credentials between your web browser cookie data and the app server\'s temporary session data, it makes it a bit harder for a hacker to view your login area, at least if your app server automatically clears all it\'s temporary session data a few times per day (some DO NOT).</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="bitcoin">REGARDLESS as to whether your particular app server automatically clears it\'s temporary session data or not, whenever you logout the 32-byte key in your browser is deleted, along with all the session data on the app server.</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="bitcoin">If your app server DOES automatically clears session data often, you will also be logged out AUTOMATICALLY at that time. ADDITIONALLY, the 32-byte random key that is saved inside a cookie in your web browser EXPIRES (automatically deletes itself) AFTER <?=$pt_conf['power']['admin_cookie_expire']?> HOURS (you can adjust this time period in the Admin Config POWER USER section).</span></p>'
			
			
			+'<p> </p>';


</script>

<div style="text-align: center;">

<h3 class='bitcoin'>Admin Login</h3>

<p class='bitcoin' style='font-size: 19px; font-weight: bold;'>Cookies MUST be enabled in your browser to login.
	 <img id='admin_cookies' src='templates/interface/media/images/info-orange.png' alt='' width='30' style='position: relative;' /> 
	 </p>

	 <script>
	
			$('#admin_cookies').balloon({
			html: true,
			position: "bottom",
  			classname: 'balloon-tooltips',
			contents: admin_cookies,
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

	<div style='font-weight: bold;' id='login_alert'>
<?php
	foreach ( $login_result['error'] as $error ) {
	echo "<br clear='all' /><div class='red' style='display: inline-block;  font-weight: bold; padding: 15px; margin: 15px; font-size: 21px; border: 4px dotted #ff4747;'> $error </div>";
	}
?>
	</div>
  	 
  	 
  	 <br clear='all' />
  
  
<?php

if ( !$_POST['submit_login'] || sizeof($login_result['error']) > 0 ) {
?>


<form id='admin_login' action='' method='post'>

    <div style="display: inline-block; text-align: right; width: 400px;">

<p><b>Username:</b> <input type='text' name='admin_username' id='admin_username' value='<?=trim($_POST['admin_username'])?>' /></p>

<p><b>Password:</b> <input type='password' name='admin_password' id='admin_password' value='<?=$_POST['admin_password']?>' /></p>

    </div>
  	 
  	 
  	 <br clear='all' />
  
  
  	 <div class='align_center' style='display: inline-block;'>
  	 
  	 <p><img id='captcha_image' src='templates/interface/media/images/captcha.php' alt='' title='CAPTCHA image text contrast / maximum angle can be adjusted in Admin Config, within the "Power User" section.

Custom TTF fonts can be automatically added by placing them in the /templates/interface/fonts/ folder.' class='image_border' />
  	 <br />
  	 <a href='javascript: refreshImage("captcha_image", "templates/interface/media/images/captcha.php");' class='bitcoin' style='font-weight: bold;' title='CAPTCHA image text contrast / maximum angle can be adjusted in Admin Config, within the "Power User" section.

Custom TTF fonts can be automatically added by placing them in the /templates/interface/fonts/ folder.'>Get A Different Image</a>
  	 </p>
  	 
  	 </div>
  
  	 
  	 <br clear='all' />


  	 <div style="display: inline-block; text-align: right; width: 400px;">
  
  	 <p><b>Enter Image Text:</b> <input type='text' name='captcha_code' id='captcha_code' value='' style='<?=( $captcha_field_color ? 'background: ' . $captcha_field_color : '' )?>' /></p>
	
	<p class='align_left' style='font-size: 19px; font-weight: bold; color: #ff4747;' id='captcha_alert'></p>
  
  	 </div>
  	 
  
  	 <br clear='all' />
  	 
  
<p style='padding: 20px;'><input type='submit' value='Login As Admin' /></p>

<input type='hidden' name='admin_submit_login' value='1' />

</form>

<?php
}

?>


<p><a style='font-size: 22px; !important' href='password-reset.php' class='red' title='Reset you password here.'><b>Forgot Your Password?</b></a></p>

<p> &nbsp; </p>

<p style='font-weight: bold;'> <a href='<?=$base_url?>'>Return To The Portfolio Main Page</a> </p>

</div>


<?php
require("templates/interface/desktop/php/footer.php");
?>
