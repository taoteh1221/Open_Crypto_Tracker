<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/CRON-PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// Configs MUST BE IN AN ARRAY NAMED: $app_config['cron_plugins'][$cron_plugin_name]
$app_config['cron_plugins'][$cron_plugin_name] = array(

	'reminder_recur_days' => 30.4167, // Decimals supported (30.4167 days is average length of 1 month)
	'reminder_message' => "Review whether you should re-balance your portfolio (have individual assets take up a different precentage of your portfolio's total ".strtoupper($app_config['general']['btc_primary_currency_pairing'])." value).",

																		); // END array

?>


