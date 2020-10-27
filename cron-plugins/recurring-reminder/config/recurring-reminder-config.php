<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/CRON-PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// Cron plugin configs MUST BE IN AN ARRAY NAMED: $cron_plugin_config
$cron_plugin_config = array(

	// Remind yourself every X days (recurring)
	'reminder_recur_days' => 30.4167, // Decimals supported (30.4167 days is average length of 1 month)
	
	// Reminder message
	'reminder_message' => "Review whether you should re-balance your portfolio (have individual assets take up a different precentage of your portfolio's total ".strtoupper($app_config['general']['btc_primary_currency_pairing'])." value).",

									); // END array

?>


