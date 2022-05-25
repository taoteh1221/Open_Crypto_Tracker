<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// All "plug-conf.php" PLUGIN CONFIG settings MUST BE INSIDE THE "$plug_conf[$this_plug]" ARRAY (sub-arrays are allowed)

// EXAMPLES...

// $plug_conf[$this_plug]['SETTING_NAME_HERE'] = 'mysetting'; 

// $plug_conf[$this_plug]['SETTING_NAME_HERE'] = array('mysetting1', 'mysetting2');


// What runtime modes this plugin should run during (MANDATORY)
$plug_conf[$this_plug]['runtime_mode'] = 'cron'; // 'cron', 'ui', 'all' (only 'cron' supported as of 2020-10-29)


// Re-allow SAME asset price target alert(s) messages after X MINUTES (per alert config)
$plug_conf[$this_plug]['alerts_freq_max'] = 30; // Can be 0, to have no limits (Default = 30)


// Price targets array (add unlimited price targets as new array objects)
// MUST BE THE #EXACT# MARKETS, THERE IS NO LOCAL CURRENCY CONVERSION AVAILABLE IN THIS PLUGIN!
$plug_conf[$this_plug]['price_targets'] = array(
																	
												// 'asset-pair-exchange' => '123.4567', // NO COMMAS ALLOWED IN PRICE, ONLY DECIMALS
												'btc-usd-coinbase' => '55100',
												'eth-btc-binance' => '0.085',
												'eth-usdt-binance' => '3950',
												'sol-eth-binance' => '0.045',
												'sol-usd-coinbase' => '125',
												'slc-usd-generic_usd' => '0.75',
												'luna-usd-ftx' => '0.25',
													
												); // END price targets array




// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>