<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// All "plug-conf.php" PLUGIN CONFIG settings MUST BE INSIDE THE "$plug_conf[$this_plug]" ARRAY (sub-arrays are allowed)

// EXAMPLES...

// $plug_conf[$this_plug]['SETTING_NAME_HERE'] = 'mysetting'; 

// $plug_conf[$this_plug]['SETTING_NAME_HERE'] = array('mysetting1', 'mysetting2');


// What runtime modes this plugin should run during (MANDATORY)
$plug_conf[$this_plug]['runtime_mode'] = 'cron'; // 'cron', 'webhook', 'ui', 'all'


// If running in the UI, set the preferred location it should show in
$plug_conf[$this_plug]['ui_location'] = 'tools'; // 'tools', 'more_stats' (defaults to 'tools' if not set)


// If running in the UI, set the preferred plugin name that should show for end-users
$plug_conf[$this_plug]['ui_name'] = 'Price Target Alert'; // (defaults to $this_plug if not set)


// Re-allow SAME asset price target alert(s) messages after X MINUTES (per alert config)
$plug_conf[$this_plug]['alerts_frequency_maximum'] = 30; // Can be 0, to have no limits (Default = 30)


// Price targets array (add unlimited price targets as new array objects)
// MUST BE THE #EXACT# MARKETS, THERE IS NO LOCAL CURRENCY CONVERSION AVAILABLE IN THIS PLUGIN!
$plug_conf[$this_plug]['price_targets'] = array(
																	
												// 'asset-pair-exchange' => '123.4567', // NO COMMAS ALLOWED IN PRICE, ONLY DECIMALS
												'btc-usd-coinbase' => '45800',
												'eth-btc-binance' => '0.08167',
												'eth-usdt-binance' => '2280',
												'sol-eth-binance' => '0.0225',
												'sol-usd-coinbase' => '30.65',
												'polis-sol-jupiter_ag' => '0.00555',
													
												); // END price targets array




// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>