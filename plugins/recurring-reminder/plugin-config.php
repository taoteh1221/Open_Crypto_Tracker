<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
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

// Remind yourself every X days (recurring)
$plugin_config[$this_plugin]['reminder_recur_days'] = 30.4167; // Decimals supported (30.4167 days is average length of 1 month)

// Reminder message
$plugin_config[$this_plugin]['reminder_message'] = "Review whether you should re-balance your portfolio (have individual assets take up a different precentage of your portfolio's total " . strtoupper($app_config['general']['btc_primary_currency_pairing']) . " value).";




?>


