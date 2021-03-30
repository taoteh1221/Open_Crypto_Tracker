<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
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

// Re-allow SAME asset price target alert(s) messages after X hours (per alert config)
$plug_conf[$this_plug]['alerts_freq_max'] = 6; // Can be 0, to have no limits

// Price targets array (add unlimited price targets as new array objects)
$plug_conf[$this_plug]['price_targets'] = array(
																	
																	// 'asset-pairing-exchange' => '123.4567', // NO COMMAS ALLOWED IN PRICE, ONLY DECIMALS
																	'btc-usd-coinbase' => '51000',
																	'eth-btc-binance' => '0.034',
																	
																	); // END price targets array




// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG, SINCE WE INCLUDE THIS IN INIT PHP!

?>