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


// Re-allow SAME address balance alert(s) messages after X minutes (per alert config)
$plug_conf[$this_plug]['alerts_freq_max'] = 15; // Can be 0, to have no limits


// Balance tracking array (add unlimited addresses as new subarray objects)
// (ONLY BTC / ETH SUPPORTED)
$plug_conf[$this_plug]['tracking'] = array(
																	
																	
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
																			
												
											    // HNT EXAMPLE
												array(
													'asset' => 'hnt', // Asset symbol (LOWERCASE)
													'address' => '13xs559435FGkh39qD9kXasaAnB8JRF8KowqPeUmKHWU46VYG1h', // Recieving address
													'label' => 'Portfolio Tracker HNT Donations' // Description of address
													),
																	
																	
											); // END tracking array




// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>