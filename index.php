<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


require("templates/interface/php/header.php");

?>


		<ul class='tabs' style='display: inline-block; width: 100%; text-align: center;'>
			<li class='tabli'><a href='#portfolio'>Portfolio</a></li>
			<li class='tabli'><a href='#update_assets'>Update Assets</a></li>
			<li class='tabli'><a href='#settings'>Settings</a></li>
			<?php
			if ( $app_config['charts_page'] == 'on' ) {
			?>
			<li class='tabli'><a href='#charts'>Charts</a></li>
			<?php
			}
			?>
			<li class='tabli'><a href='#mining_calculators'>Mining Calculators</a></li>
			<li class='tabli'><a href='#tools'>Tools</a></li>
			<li class='tabli'><a href='#resources'>Resources</a></li>
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
			if ( $app_config['charts_page'] == 'on' ) {
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
		<div id='help' class='tabdiv'>
			<?php require("templates/interface/php/user/user-sections/help.php"); ?>
		</div>


<?php
require("templates/interface/php/footer.php");
?>

