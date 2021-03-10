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

// Re-allow SAME address balance alert(s) messages after X minutes (per alert config)
$plugin_config[$this_plugin]['alerts_freq_max'] = 15; // Can be 0, to have no limits


// Balance tracking array (add unlimited addresses as new subarray objects)
// (ONLY BTC / ETH SUPPORTED)
$plugin_config[$this_plugin]['tracking'] = array(
																	
																	
																	// BTC EXAMPLE
																	array(
																			'asset' => 'btc', // Asset symbol (LOWERCASE)
																			'address' => '3Nw6cvSgnLEFmQ1V4e8RSBG23G7pDjF3hW', // Recieving address
																			'label' => 'Portfolio Tracker BTC Donations' // Description of address
																			),
																			
																			
																	// ETH EXAMPLE
																	array(
																			'asset' => 'eth', // Asset symbol (LOWERCASE)
																			'address' => '0x644343e8D0A4cF33eee3E54fE5d5B8BFD0285EF8', // Recieving address
																			'label' => 'Portfolio Tracker ETH Donations' // Description of address
																			),
																	
																	
																	); // END reminders array




?>


