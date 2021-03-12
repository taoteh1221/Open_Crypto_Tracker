<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// All "plugin-config.php" PLUGIN CONFIG settings MUST BE INSIDE THE "$plugin_config[$this_plugin]" ARRAY (sub-arrays are allowed)

// EXAMPLES...

// $plugin_config[$this_plugin]['SETTING_NAME_HERE'] = 'mysetting'; 

// $plugin_config[$this_plugin]['SETTING_NAME_HERE'] = array('mysetting1', 'mysetting2');


// What runtime modes this plugin should run during (MANDATORY)
$plugin_config[$this_plugin]['runtime_mode'] = 'cron'; // 'cron', 'ui', 'all' (only 'cron' supported as of 2020-10-29)


// Reminders array (add unlimited reminders as new subarray objects)
$plugin_config[$this_plugin]['reminders'] = array(
																	
																	
																	// PORTFOLIO RE-BALANCE REVIEW REMINDER
																	array(
																			'days' => 30.4167, // Decimals supported (30.4167 days is average length of 1 month)
																			'message' => "Review whether you should re-balance your portfolio (have individual assets take up a different precentage of your portfolio's total " . strtoupper($app_config['general']['btc_primary_currency_pairing']) . " value)." // Reminder message
																			),
																			
																			
																	// VITAMIN D / COVID-19 PREVENTION REMINDER
																	array(
																			'days' => 4, // Decimals supported
																			'message' => "Take 2000 IU of Vitamin D and 500 MG of Vitamin C every 4 days with food, to help prevent Covid-19 and other viral infections." // Reminder message
																			),
																	
																	
																	); // END reminders array




?>


