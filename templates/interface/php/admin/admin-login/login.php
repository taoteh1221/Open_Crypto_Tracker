<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


$login_result = array();
		
if ( $_POST['admin_submit_login'] ) {
	
	if ( trim($_POST['captcha_code']) == '' || trim($_POST['captcha_code']) != '' && strtolower($_POST['captcha_code']) != strtolower($_SESSION['captcha_code']) )	{
	$login_result['error'][] = "Captcha code was not correct.";
	}
	else {
		
				if ( sizeof($admin_login) == 2 && trim($_POST['admin_username']) != '' && isset($_POST['admin_password']) 
				&& $_POST['admin_username'] == $admin_login[0] && check_pepper_hashed_password($_POST['admin_password'], $admin_login[1]) == true ) {
				$_SESSION['admin_login'] = $admin_login;
				header("Location: admin.php");
				exit;
				}
				else {
				$login_result['error'][] = "Wrong username / password.";
				}
			

	}
	
}


require("templates/interface/php/header.php");

?>

<div style="text-align: center;">

<h3 class='bitcoin'>Admin Login</h3>


	<div style='font-weight: bold;' id='login_alert'>
<?php
	foreach ( $login_result['error'] as $error ) {
	echo "<div class='red_bright' style='display: inline-block;  font-weight: bold; padding: 15px; margin: 15px; font-size: 21px; border: 4px dotted #ff4747;'> $error </div>";
	}
?>
	</div>


    <div class='align_center'>

	<p style='padding: 10px;'><a href='password-reset.php' class='red_bright'><b>Forgot Your Password?</b></a></p>

    </div>
  	 
  	 
  	 <br clear='all' />
  
  
<?php

if ( !$_POST['submit_login'] || sizeof($login_result['error']) > 0 ) {
?>


<form action='' method='post'>

    <div style="display: inline-block; text-align: right; width: 350px;">

<p><b>Admin Username:</b> <input type='text' name='admin_username' value='<?=$_POST['admin_username']?>' /></p>

<p><b>Admin Password:</b> <input type='password' name='admin_password' value='<?=$_POST['admin_password']?>' /></p>

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
  	 
  
<p style='padding: 20px;'><input type='submit' value='Login As Admin' /></p>

<input type='hidden' name='admin_submit_login' value='1' />

</form>

<?php
}

?>

</div>


<?php
require("templates/interface/php/footer.php");
?>
