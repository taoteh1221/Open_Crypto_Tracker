<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
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

// Re-allow SAME asset price target alert(s) messages after X hours (per alert config)
$plugin_config[$this_plugin]['alerts_freq_max'] = 6; // Can be 0, to have no limits

// Price targets array (add unlimited price targets as new array objects)
$plugin_config[$this_plugin]['price_targets'] = array(
																	
																	// 'asset-pairing-exchange' => '123.4567', // NO COMMAS ALLOWED IN PRICE, ONLY DECIMALS
																	'btc-usd-coinbase' => '51000',
																	'eth-btc-binance' => '0.034',
																	
																	); // END price targets array




?>


