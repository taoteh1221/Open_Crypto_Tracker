<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// MAY HELP, SINCE WE USE SAMESITE=STRICT COOKIES (ESPECIALLY ON SERVERS WITH DOMAIN REDIRECTS)
if ( !preg_match("/index\.php/i", $_SERVER['REQUEST_URI']) ) {
header("Location: index.php");
exit;
}


// Runtime mode
$runtime_mode = 'ui';


require("app-lib/php/init.php");
	

// If an activated password reset is in progress or no admin login has been set yet, prompt user to create an admin user / pass
if ( $password_reset_approved || !is_array($stored_admin_login) ) {
require("templates/interface/php/admin/admin-login/register.php");
exit;
}
else {
require("templates/interface/php/wrap/header.php");
}

?>

		<div id='portfolio' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/portfolio.php"); ?>
		</div>
		
		
		<div id='update' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/update.php"); ?>
		</div>
		
		
		<div id='settings' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/settings.php"); ?>
		</div>
		
		
			<?php
			if ( $ct['conf']['gen']['asset_charts_toggle'] == 'on' ) {
			?>
		<div id='charts' class='tabdiv container-fluid'>
			<?php require("templates/interface/php/user/user-sections/charts.php"); ?>
		</div>
			<?php
			}
			?>
		
		
		<div id='news' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/news.php"); ?>
		</div>
			
			
		<div id='tools' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/tools.php"); ?>
		</div>
		
		
		<div id='mining' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/mining.php"); ?>
		</div>
		
		
		<div id='resources' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/resources.php"); ?>
		</div>

    
<?php
require("templates/interface/php/wrap/footer.php");

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>