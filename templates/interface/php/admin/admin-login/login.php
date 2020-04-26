<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


$login_result = array();
		
if ( $_POST['submit_login'] ) {
	
	if ( $_POST['username'] == '' )	{
	$login_result['error'][] = "Please enter your email.";
	}
	elseif ( $securimage->check( $_POST['captcha_code'] ) == false )	{
	$login_result['error'][] = "Captcha code was not correct.";
	}
	else {
		
		// Login if user / pass match
		$query = "SELECT * FROM users WHERE email = '".$_POST['username']."'";
		
		if ($result = mysqli_query($db_connect, $query)) {
			
		   while ( $row = mysqli_fetch_array($result, MYSQLI_ASSOC) ) {
				
				if ( $row["password"] == md5( $_POST['password'] ) && $row["activated"] == 'yes' ) {
				$_SESSION['user'] = $row;
				header("Location: /online-account/summary/");
				mysqli_free_result($result);
				exit;
				}
				elseif ( $row["password"] != md5( $_POST['password'] ) ) {
				$login_result['error'][] = "Wrong password.";
				}
				elseif ( $row["activated"] != 'yes' ) {
				$login_result['error'][] = "Please activate <a href='/online-account/reset/' class='red-underline'>or reset your account</a> first.";
				}
				else {
				$login_result['error'][] = "Please check all form fields.";
				}
			
		   //echo $row["username"]." ".$row["email"]."<br />";
		   
		   }
		   
		   
			if ( $result->num_rows < 1 ) {
			$login_result['error'][] = "Please check all form fields.";
			}
	
		mysqli_free_result($result);
		}
		

	}
	
}

?>

<div style="text-align: center;">

<h3>Account Login</h3>


	<div style='font-weight: bold;' id='login_alert'>
<?php
	foreach ( $login_result['error'] as $error ) {
	echo "<p><b style='color: red;'> $error </b></p>";
	}
?>
	</div>


    <div style="display: inline-block; text-align: right; width: 350px;">

<p style='padding: 20px;'><a href='/online-account/reset/'><b class='red-underline'>Forgot Your Password?</b></a></p>

<?php

if ( !$_POST['submit_login'] || sizeof($login_result['error']) > 0 ) {
?>


<form action='' method ='post'>

<p><b>Username:</b> <input type='text' name='username' value='<?=$_POST['username']?>' /></p>

<p><b>Password:</b> <input type='password' name='password' value='' /></p>


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
  
<p style='padding: 20px;'><input type='submit' value='Login' /></p>

<input type='hidden' name='submit_login' value='1' />

</form>

<?php
}

?>

    </div>
</div>
