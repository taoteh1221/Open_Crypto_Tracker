<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
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
$plug_conf[$this_plug]['price_targets'] = array(
																	
												// 'asset-pairing-exchange' => '123.4567', // NO COMMAS ALLOWED IN PRICE, ONLY DECIMALS
												'btc-usd-coinbase' => '57500',
												'eth-btc-binance' => '0.095',
												'eth-usdt-binance' => '4950',
												'lrc-usd-coinbase' => '0.655',
													
												); // END price targets array




// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>