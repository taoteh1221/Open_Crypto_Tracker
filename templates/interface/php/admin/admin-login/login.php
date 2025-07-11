<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$is_admin = true;

$ct['is_login_form'] = true;

$login_result = array();
		
if ( $_POST['admin_submit_login'] ) {
	
	
	if ( trim($_POST['captcha_code']) == '' || trim($_POST['captcha_code']) != '' && strtolower( trim($_POST['captcha_code']) ) != strtolower($_SESSION['captcha_code']) )	{
	$login_result['error'][] = "Captcha image code was invalid.";
	$captcha_field_color = '#ff4747';
	}
		
		
	if ( !$ct['sec']->valid_2fa() ) {
     $login_result['error'][] = $ct['check_2fa_error'] . '.';
     }
	
	
	// If no errors were logged above, check the user / pass
	if ( !is_array($login_result['error']) ) {
				
		// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
		if (
		trim($_POST['admin_username']) != '' && $_POST['admin_password'] != '' 
		&& $_POST['admin_username'] == $stored_admin_login[0]
		&& $ct['sec']->check_pepper_hashed_pass($_POST['admin_password'], $stored_admin_login[1]) == true
		) {
          $ct['sec']->do_admin_login();
		}
		else {
		$login_result['error'][] = "Wrong username / password.";
		}

	}
	
	

	
	
}


$login_template = 1;
require("templates/interface/php/wrap/header.php");

?>


<div class='full_width_wrapper'>


<script>

// If we are in an iframe, break out of it
this.top.location !== this.location && (this.top.location = 'admin.php');


var admin_cookies = '<h5 class="align_center bitcoin tooltip_title">Admin Login Requires Browser Cookies</h5>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; "><span class="bitcoin">For greater security after a SUCCESSFUL admin login (with the correct username and password), a 256-bit random key is saved inside a cookie in your web browser. A DIFFERENT 256-bit random key is saved on the app server in temporary session data, along with the result of concatenating the two 256-bit keys together and getting a digest (fingerprint) hash, which is your login authorization.</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; "><span class="bitcoin">Whenever you visit the Admin Config pages, the app asks your browser for it\s 256-bit key to prove you are logged in.</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; "><span class="bitcoin">By splitting the secured login credentials between your web browser cookie data and the app server\'s temporary session data, it makes it RELATIVELY even harder for a hacker to view your login area, at least if your app server automatically clears all it\'s temporary session data a few times per day (this app attempts to clear your session data EVERY 6 HOURS).</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; "><span class="bitcoin">REGARDLESS as to whether your particular app server automatically clears it\'s temporary session data or not, whenever you logout, the 256-bit key in your browser is deleted, along with all the session data on the app server.</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; "><span class="bitcoin">If your app server DOES automatically clear session data often, you will also be logged out AUTOMATICALLY at that time. ADDITIONALLY, the 256-bit random key that is saved inside a cookie in your web browser EXPIRES (automatically deletes itself) AFTER <?=$ct['conf']['sec']['admin_cookie_expires']?> HOURS (adjustable in the Admin Config SECURITY section).</span></p>'
			
			
			+'<p> </p>';


</script>

<div style="text-align: center; margin-top: 1.5em;">

<h3 class='bitcoin'>Admin Login</h3>

<p class='bitcoin' style='font-weight: bold;'>Cookies MUST be enabled in your browser to login.
	 <img class='tooltip_style_control' id='admin_cookies' src='templates/interface/media/images/info-orange.png' alt='' width='30' style='position: relative;' /> 
	 </p>


<script>

if ( localStorage.getItem(priv_toggle_storage) == 'on' ) {

document.write("<p class='red align_center' style='font-weight: bold;'>"

+ "PRIVACY MODE MUST BE DISABLED to submit data: "

+ "<span id='pm_link3' class='bitcoin' onclick='privacy_mode(true);' title=''>Disable Privacy Mode</span>"

+ "</p>");
	 
}

</script>



	 <script>
	
			$('#admin_cookies').balloon({
			html: true,
			position: "bottom",
  			classname: 'balloon-tooltips',
			contents: admin_cookies,
			css: balloon_css()
			});
		
		 </script>

	<div style='font-weight: bold;' id='login_alert'>
<?php
	foreach ( $login_result['error'] as $error ) {
	echo "<br clear='all' /><div class='red admin_login_alerts' style='border: 4px dotted #ff4747;'> $error </div>";
	}
?>
	</div>
  	 
  	 
  	 <br clear='all' />
  
  
<?php

if ( !$_POST['submit_login'] || is_array($login_result['error']) && sizeof($login_result['error']) > 0 ) {
?>


<form id='admin_login' action='admin.php' method='post'>

    <div style="display: inline-block; padding-top: 1em; text-align: center; width: auto;">

         <p><b>Username:</b> <input type='text' name='admin_username' id='admin_username' value='<?=trim($_POST['admin_username'])?>' /></p>

         <div class="password-container">
     
              <p>
                 
                 <b>Password:</b> <input type='password' data-name="admin_password" name='admin_password' id='admin_password' value='<?=$_POST['admin_password']?>' />
                 
                 <i class="gg-eye-alt toggle-show-password" data-name="admin_password"></i>
               
              </p>
            
         </div>

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


  	 <div style="display: inline-block; width: 650px;">
 
  	 <p><b class='bitcoin'>Enter Image Text:</b></p>
  
  	 <p><input type='text' name='captcha_code' id='captcha_code' value='' style='<?=( $captcha_field_color ? 'background: ' . $captcha_field_color : '' )?>' /></p>
	
	<p class='align_left' style='font-weight: bold; color: #ff4747;' id='captcha_alert'></p>
  
  	 </div>
  	 
  
  	 <br clear='all' />
	
	
	<?=$ct['gen']->input_2fa()?>
  	 
  
<p style='padding: 20px;'><input type='submit' value='Login As Admin' /></p>

<input type='hidden' name='admin_submit_login' value='1' />

</form>

<?php
}

?>


<p><a style='font-size: 22px; !important' href='password-reset.php' class='red' title='Reset you password here.'><b>Forgot Your Password?</b></a></p>

<p> &nbsp; </p>

<p style='font-weight: bold;'> <a href='<?=$ct['base_url']?>'>Return To The Portfolio Main Page</a> </p>

</div>


</div> <!-- END .full_width_wrapper -->


<?php
require("templates/interface/php/wrap/footer.php");
?>
