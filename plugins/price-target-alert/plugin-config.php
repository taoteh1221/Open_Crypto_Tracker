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

// Re-allow SAME asset price target alert(s) messages after X hours
$plugin_config[$this_plugin]['alerts_freq_max'] = 6;

// Price targets array (add unlimited price targets as new array objects)
$plugin_config[$this_plugin]['price_targets'] = array(
																	
																	// NO COMMAS ALLOWED, ONLY DECIMALS
																	// 'asset_pairing_exchange' => '123.4567'
																	'btc_usd_coinbase' => '55000',
																	'eth_btc_binance' => '0.035',
																	
																	); // END price targets array




?>


