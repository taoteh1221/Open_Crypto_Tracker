<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

 // Start measuring page load time
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

require("config.php");

require("templates/default/header.php");

?>


		<ul class='tabs'>
			<li><a href='#tab1'>Your Coin Values</a></li>
			<li><a href='#tab6'>External Resource Links</a></li>
			<li><a href='#tab4'>Mining and Interest Calculators</a></li>
			<li><a href='#tab2'>Update Coin Amounts</a></li>
			<li><a href='#tab3'>Program Settings</a></li>
			<li><a style='color:red;' href='#tab5'>Help</a></li>
		</ul>
		<div id='tab1' class='tabdiv'>
			<h3 style='display: inline;'>Your Coin Values</h3> &nbsp; &nbsp; <a href='javascript:location.reload(true);' style='font-weight: bold;'>Reload Values</a> &nbsp; <select name='select_auto_refresh' id='select_auto_refresh' onchange='auto_reload(this.value);'>
				<option value=''> Manually </option>
				<option value='60' <?=( $_COOKIE['coin_reload'] == '60' ? 'selected' : '' )?>> Every Minute </option>
				<option value='120' <?=( $_COOKIE['coin_reload'] == '120' ? 'selected' : '' )?>> Every 2 Minutes </option>
				<option value='300' <?=( $_COOKIE['coin_reload'] == '300' ? 'selected' : '' )?>> Every 5 Minutes </option>
				<option value='600' <?=( $_COOKIE['coin_reload'] == '600' ? 'selected' : '' )?>> Every 10 Minutes </option>
				<option value='900' <?=( $_COOKIE['coin_reload'] == '900' ? 'selected' : '' )?>> Every 15 Minutes </option>
			</select> &nbsp; <span id='reload_countdown' style='color: red;'></span>
			<p><?php require("sections/coin.values.php"); ?></p>
		</div>
		<div id='tab6' class='tabdiv'>
			<h3>External Resource Links</h3>
			<?php require("sections/external-resource-links.php"); ?>
		</div>
		<div id='tab4' class='tabdiv'>
			<h3>Mining and Interest Calculators</h3>
			<?php require("sections/calculators.php"); ?>
		</div>
		<div id='tab2' class='tabdiv'>
			<h3>Update Coin Amounts</h3>
			<p><?php require("sections/form.php"); ?></p>
		</div>
		<div id='tab3' class='tabdiv'>
			<h3>Program Settings</h3>
			<?php require("sections/settings.php"); ?>
		</div>
		<div id='tab5' class='tabdiv'>
			<h3 style='color: red;'>Help</h3>
			<?php require("sections/help.php"); ?>
		</div>




<?php
require("templates/default/footer.php");
?>

