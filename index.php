<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
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
if ( $password_reset_approved || !is_array($stored_admin_login) ) {
require("templates/interface/desktop/php/admin/admin-login/register.php");
exit;
}
else {
require("templates/interface/desktop/php/header.php");
}

?>


		<ul id='top_tab_nav' class='tabs'>
			<li class='tabli'><a href='#portfolio' title='View your portfolio.'>Portfolio</a></li>
			<li class='tabli'><a id='update_link' href='#update' title='Update your portfolio data.'>Update</a></li>
			<li class='tabli'><a href='#settings' title='Update your user settings.'>Settings</a></li>
			<?php
			if ( $ct_conf['gen']['asset_charts_toggle'] == 'on' ) {
			?>
			<li class='tabli'><a href='#charts' title='View price charts.'>Charts</a></li>
			<?php
			}
			?>
			<li class='tabli'><a href='#news' title='View News Feeds.'>News</a></li>
			<li class='tabli'><a href='#tools' title='Use various crypto tools.'>Tools</a></li>
			<li class='tabli'><a href='#mining' title='Calculate coin mining profits.'>Mining</a></li>
			<li class='tabli'><a href='#resources' title='View 3rd party resources.'>Resources</a></li>
			<li class='tabli'><a href='#help' title='Get help using this app.'>Help?</a></li>
		</ul>
		
		
		<div id='portfolio' class='tabdiv'>
			<?php require("templates/interface/desktop/php/user/user-sections/portfolio.php"); ?>
		</div>
		
		<div id='update' class='tabdiv'>
			<?php require("templates/interface/desktop/php/user/user-sections/update.php"); ?>
		</div>
		
		<div id='settings' class='tabdiv'>
			<?php require("templates/interface/desktop/php/user/user-sections/settings.php"); ?>
		</div>
		
			<?php
			if ( $ct_conf['gen']['asset_charts_toggle'] == 'on' ) {
			?>
		<div id='charts' class='tabdiv container-fluid'>
			<?php require("templates/interface/desktop/php/user/user-sections/charts.php"); ?>
		</div>
			<?php
			}
			?>
		
		<div id='news' class='tabdiv'>
			<?php require("templates/interface/desktop/php/user/user-sections/news.php"); ?>
		</div>
			
		<div id='tools' class='tabdiv'>
			<?php require("templates/interface/desktop/php/user/user-sections/tools.php"); ?>
		</div>
		
		<div id='mining' class='tabdiv'>
			<?php require("templates/interface/desktop/php/user/user-sections/mining.php"); ?>
		</div>
		
		<div id='resources' class='tabdiv'>
			<?php require("templates/interface/desktop/php/user/user-sections/resources.php"); ?>
		</div>
		
		<div id='help' class='tabdiv'>
			<?php require("templates/interface/desktop/php/user/user-sections/help.php"); ?>
		</div>


<?php
require("templates/interface/desktop/php/footer.php");

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>