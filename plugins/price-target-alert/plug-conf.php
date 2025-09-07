<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// Version number of this plugin (MANDATORY)
$ct['plug_version'][$this_plug] = '1.01.00'; // VERSION BUMP DATE: 2025/May/7TH


// FULL RESET(s) on specified plugin settings (the setting CAN include internal subarrays)
// Resets ENTIRE setting, IF upgrading from EARLIER version than param value
// ONLY top-level KEY NAMES WITHIN A PLUGIN CONFIG ARRAY can be reset:
// $plug['conf'][$this_plug]['plugin-setting-key-1']
$ct['dev']['plugin_allow_resets'][$this_plug] = array(
                                                      // setting key id, and plugin version number of when the reset was added
                                                      // NO DUPLICATES, REPLACE KEY'S VALUE WITH LATEST AFFECTED VERSION!
                                                      // 'plugin-setting-key-1' => '0.90.00',
                                                      // 'plugin-setting-key-2' => '1.23.45',
                                                     );


// All PLUGIN CONFIG settings MUST BE INSIDE THE "$plug['conf'][$this_plug]" ARRAY (sub-arrays are allowed)

// EXAMPLES...

// $plug['conf'][$this_plug]['SETTING_NAME_HERE'] = 'mysetting'; 

// $plug['conf'][$this_plug]['SETTING_NAME_HERE'] = array('mysetting1', 'mysetting2');


// What runtime modes this plugin should run during (MANDATORY)
$plug['conf'][$this_plug]['runtime_mode'] = 'cron'; // 'cron', 'webhook', 'ui', 'all'


// If running in the UI, set the preferred location it should show in
$plug['conf'][$this_plug]['ui_location'] = 'tools'; // 'tools', 'more_stats' (defaults to 'tools' if not set)


// If running in the UI, set the preferred plugin name that should show for end-users
$plug['conf'][$this_plug]['ui_name'] = 'Price Target Alert'; // (defaults to $this_plug if not set)


// Re-allow SAME asset price target alert(s) messages after X MINUTES (per alert config)
$plug['conf'][$this_plug]['alerts_frequency_maximum'] = 15; // Can be 0, to have no limits (Default = 15)


// Price targets array (add unlimited price targets as new array objects)
// MUST BE THE #EXACT# MARKETS, THERE IS NO LOCAL CURRENCY CONVERSION AVAILABLE IN THIS PLUGIN!
$plug['conf'][$this_plug]['price_targets'] = array(

						                    // NO COMMAS ALLOWED IN PRICE, ONLY DECIMALS
						                    // USE EXCHANGE IDS USED IN CONFIG.PHP				
										// 'asset-pair-exchange_id = 123.4567',
										'btc-usd-kraken = 125800',
										'sol-eth-binance = 0.0925',
										'sol-usd-kraken = 227.51',
										'amznstock-usd-alphavantage_stock = 256.50',
										
							            ); // END price targets array




// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>