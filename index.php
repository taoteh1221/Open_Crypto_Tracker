<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start_runtime = $time;


// Runtime mode
$runtime_mode = 'ui';


require("config.php");
	

// If an activated password reset is in progress or no admin login has been set yet, prompt user to create an admin user / pass
if ( $password_reset_approved || sizeof($stored_admin_login) != 2 ) {
require("templates/interface/php/admin/admin-login/register.php");
exit;
}
else {
require("templates/interface/php/header.php");
}

?>


		<ul id='top_tab_nav' class='tabs'>
			<li class='tabli'><a href='#portfolio'>Portfolio</a></li>
			<li class='tabli'><a href='#update_assets'>Update Assets</a></li>
			<li class='tabli'><a href='#settings'>Settings</a></li>
			<?php
			if ( $app_config['general']['charts_toggle'] == 'on' ) {
			?>
			<li class='tabli'><a href='#charts'>Charts</a></li>
			<?php
			}
			?>
			<li class='tabli'><a href='#mining_calculators'>Mining Calculators</a></li>
			<li class='tabli'><a href='#tools'>Tools</a></li>
			<li class='tabli'><a href='#resources'>Resources</a></li>
			<li class='tabli'><a href='#news'>News</a></li>
			<li class='tabli'><a href='#help'>Help?</a></li>
		</ul>
		
		
		<div id='portfolio' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/portfolio.php"); ?>
		</div>
		<div id='update_assets' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/update-assets.php"); ?>
		</div>
		<div id='settings' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/settings.php"); ?>
		</div>
			<?php
			if ( $app_config['general']['charts_toggle'] == 'on' ) {
			?>
		<div id='charts' class='tabdiv container-fluid'>
			<?php require("templates/interface/php/user/user-sections/charts.php"); ?>
		</div>
			<?php
			}
			?>
		<div id='mining_calculators' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/mining-calculators.php"); ?>
		</div>
		<div id='tools' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/tools.php"); ?>
		</div>
		<div id='resources' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/resources.php"); ?>
		</div>
		<div id='news' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/news.php"); ?>
		</div>
		<div id='help' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/help.php"); ?>
		</div>


<?php
require("templates/interface/php/footer.php");
?>

