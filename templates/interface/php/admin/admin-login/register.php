<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


$is_admin = true;

$is_login_form = true;

// SECURITY CHECKS FIRST! (ALL IF STATEMENTS #ON PURPOSE#, TO AVOID ANY UNFORSEEN EDGE CASES!)

// REGISTRATION SECURITY 
if ( !isset($_GET['new_reset_key']) && is_array($stored_admin_login) ) {
$ct_gen->log('security_error', 'aborted admin registration attempt ('.$_SERVER['REQUEST_URI'].'), admin account ALREADY EXISTS');
$ct_cache->error_log();
echo "Aborted, admin account ALREADY EXISTS.";
exit;
}


// RESET SECURITY 
if ( isset($_GET['new_reset_key']) && $password_reset_denied == 1 ) {
$ct_gen->log('security_error', 'aborted password reset attempt ('.$_SERVER['REQUEST_URI'].'), verification MISMATCH / NOT APPROVED');
$ct_cache->error_log();
echo "Aborted, password reset verification MISMATCH / NOT APPROVED.";
exit;
}
	


$register_result = array();

	
if ( !$_GET['new_reset_key'] && !$_POST['admin_submit_register'] && is_array($stored_admin_login) && $valid_to_email ) {
$register_result['error'][] = "An admin login already exists, and you HAVE properly added a VALID 'To' email in the communications configuration. Try <a href='password-reset.php' class='red'>resetting your password</a> instead.";
}
	
	
if ( $_POST['admin_submit_register'] ) {

	// Run checks...
	
	if ( $ct_gen->valid_username( trim($_POST['set_username']) ) != 'valid' ) {
	$register_result['error'][] = $ct_gen->valid_username( trim($_POST['set_username']) );
	$username_field_color = '#ff4747';
	}
	
	//////////////
	
	////////////////
	
	
	if ( $ct_gen->pass_strength($_POST['set_password'], 12, 40) != 'valid'  ) {
	$register_result['error'][] = $ct_gen->pass_strength($_POST['set_password'], 12, 40);
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
			

		var username_notes = '<h5 class="align_center red tooltip_title">Username Format Requirements</h5>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="red">All lower case<br />Starts with a letter<br />Numbers allowed <br />No symbols <br />No spaces <br />Between 4 - 30 characters <br /></span></p>'
			
			+'<p> </p>';



		var password_notes = '<h5 class="align_center red tooltip_title">Password Format Requirements</h5>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="red">At least one upper case letter<br />At least one lower case letter<br />At least one number <br />At least one symbol <br />No spaces <br />Between 12 - 40 characters <br /></span></p>'
			
			+'<p> </p>';


</script>

<div style="text-align: center;">

	<div id='login_alert'>
<?php
	foreach ( $register_result['error'] as $error ) {
	echo "<br clear='all' /><div class='red admin_login_alerts' style='border: 4px dotted #ff4747;'> $error </div>";
	}


	foreach ( $register_result['success'] as $success ) {
	echo "<br clear='all' /><div class='green_bright admin_login_alerts' style='border: 4px dotted #10d602;'> $success </div>";
	}
?>
	</div>
	
	<div class='green_bright' style='display: none; font-weight: bold; padding: 15px; margin: 15px; font-size: 21px; border: 4px dotted #10d602;' id='submit_alert'></div>

<?php
if ( $_GET['new_reset_key'] ) {
?>

<h3 class='bitcoin'>Admin Login Reset</h3>

<p class='red' style='font-size: 19px; font-weight: bold;'>Reset your username / password for the Admin Config area.</p>

<?php
}
else {
?>

<h3 class='bitcoin'>Admin Login Creation</h3>

<p class='red' style='font-size: 19px; font-weight: bold;'>Create a username / password to secure the Admin Config area.</p>

<?php
}
?>

<p class='red' style='font-size: 19px; font-weight: bold;'>Cookies MUST be ENABLED in your browser to login.
	 <img id='admin_cookies' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative;' /> 
	 </p>

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
		 


<script>

if ( get_cookie('priv_toggle') == 'on' ) {

document.write("<p class='red align_center' style='font-size: 19px; font-weight: bold;'>"

+ "PRIVACY MODE MUST BE DISABLED to submit data: "

+ "<span id='pm_link' class='bitcoin' onclick='privacy_mode(true);' title=''>Disable Privacy Mode</span>"

+ "</p>");
	 
}

</script>


<?php
if ( !$_POST['submit_registration'] || is_array($register_result['error']) && sizeof($register_result['error']) > 0 ) {
?>

<form name='set_admin' id='set_admin' action='' method='post'>


    <div style="display: inline-block; text-align: right; width: 400px;">
    
	 <p>
	 
	 <img id='username_notes' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative; left: 5px;' />  
	 
	 <b>Username:</b> 
	 
	 <input type='text' id='set_username' name='set_username' value='<?=trim($_POST['set_username'])?>' style='<?=( $username_field_color ? 'background: ' . $username_field_color : '' )?>' />
		 
	 </p>

	 <p>
	 
	 <img id='password_notes' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative; left: 5px;' /> 
	 
	 <b>Password:</b> 
	 	 
	 <input type='password' id='set_password' name='set_password' value='<?=$_POST['set_password']?>' style='<?=( $password_field_color ? 'background: ' . $password_field_color : '' )?>' />
	 
	 </p>

	 <p><b>Repeat Password:</b> <input type='password' id='set_password2' name='set_password2' value='<?=$_POST['set_password2']?>' style='<?=( $password2_field_color ? 'background: ' . $password2_field_color : '' )?>' /></p>
    	
    	
		<p class='align_left' style='font-size: 19px; font-weight: bold; color: #ff4747;' id='user_alert'></p>
	
		<p class='align_left' style='font-size: 19px; font-weight: bold; color: #ff4747;' id='pass_alert'></p>
		
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
  
  
<input type='hidden' name='admin_submit_register' value='1' />

<?php 
if ( isset($_GET['new_reset_key']) ) {
?>
<input type='hidden' name='new_reset_key' value='<?=$_GET['new_reset_key']?>' />
<?php
}
?>
		 
    
<p style='padding: 20px;'>

<input type='submit' id='admin_register_button' class='force_button_style' onclick='

// Remove any previous submission error notices (captcha error etc)
document.getElementById("login_alert").style.display = "none";

if ( string_check("user_alert", "set_username", "Username") == true && check_pass("pass_alert", "set_password", "set_password2") == true ) {

var captcha_code = document.getElementById("captcha_code");
var captcha_alert = document.getElementById("captcha_alert");

var goodColor = "#10d602";
var badColor = "#ff4747";

	if ( captcha_code.value != "" ) {
		
	document.getElementById("submit_alert").style.display = "inline-block";
	document.getElementById("submit_alert").innerHTML = "Creating your new admin login, please wait...";
	
    captcha_code.style.backgroundColor = goodColor;
    captcha_alert.style.color = goodColor;
    captcha_alert.innerHTML = "Captcha code included."
   
    document.getElementById("admin_register_button").value = "Submitting, please wait...";
	$("#set_admin").submit(); // Triggers "app reloading" sequence
	
	}
	else {
		
    captcha_code.style.backgroundColor = badColor;
    captcha_alert.style.color = badColor;
    captcha_alert.innerHTML = "Captcha code MUST be included.";
    return false;
    
	}

}
else {
return false;
}

' value='<?=( $_GET['new_reset_key'] ? 'Reset' : 'Create' )?> Admin Login' />

</p>


</form>
  
		
	 <script>
	
			$('#username_notes').balloon({
			html: true,
			position: "bottom",
  			classname: 'balloon-tooltips',
			contents: username_notes,
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
		
	
			$('#password_notes').balloon({
			html: true,
			position: "bottom",
  			classname: 'balloon-tooltips',
			contents: password_notes,
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

<?php
}

?>


<p style='font-weight: bold;'> <a href='<?=$base_url?>'>Return To The Portfolio Main Page</a> </p>


</div>


<?php
require("templates/interface/php/footer.php");
?>