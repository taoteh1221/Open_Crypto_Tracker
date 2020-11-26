<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
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
			<li class='tabli'><a href='#update'>Update</a></li>
			<li class='tabli'><a href='#settings'>Settings</a></li>
			<li class='tabli'><a href='#news'>News</a></li>
			<li class='tabli'><a href='#resources'>Resources</a></li>
			<?php
			if ( $app_config['general']['asset_charts_toggle'] == 'on' ) {
			?>
			<li class='tabli'><a href='#charts'>Charts</a></li>
			<?php
			}
			?>
			<li class='tabli'><a href='#tools'>Tools</a></li>
			<li class='tabli'><a href='#mining'>Mining</a></li>
			<li class='tabli'><a href='#help'>Help?</a></li>
		</ul>
		
		
		<div id='portfolio' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/portfolio.php"); ?>
		</div>
		
		<script>
		//console.log("Portfolio tab loaded."); // DEBUGGING
		</script>
		
		<div id='update' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/update.php"); ?>
		</div>
		
		<script>
		//console.log("Update tab loaded."); // DEBUGGING
		</script>
		
		<div id='settings' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/settings.php"); ?>
		</div>
		
		<script>
		//console.log("Settings tab loaded."); // DEBUGGING
		</script>
		
		<div id='news' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/news.php"); ?>
		</div>
		
		<script>
		//console.log("News tab loaded."); // DEBUGGING
		</script>
		
		<div id='resources' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/resources.php"); ?>
		</div>
		
		<script>
		//console.log("Resources tab loaded."); // DEBUGGING
		</script>
		
			<?php
			if ( $app_config['general']['asset_charts_toggle'] == 'on' ) {
			?>
		<div id='charts' class='tabdiv container-fluid'>
			<?php require("templates/interface/php/user/user-sections/charts.php"); ?>
		</div>
		
		<script>
		//console.log("Charts tab loaded."); // DEBUGGING
		</script>
			<?php
			}
			?>
			
		<div id='tools' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/tools.php"); ?>
		</div>
		
		<script>
		//console.log("Tools tab loaded."); // DEBUGGING
		</script>
		
		<div id='mining' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/mining.php"); ?>
		</div>
		
		<script>
		//console.log("Mining tab loaded."); // DEBUGGING
		</script>
		
		<div id='help' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/help.php"); ?>
		</div>
		
		<script>
		//console.log("Help tab loaded."); // DEBUGGING
		</script>


<?php
require("templates/interface/php/footer.php");
?>

