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
$plug_conf[$this_plug]['ui_name'] = 'Address Balance Tracker'; // (defaults to $this_plug if not set)


// Re-allow SAME address balance alert(s) messages after X minutes (per alert config)
$plug_conf[$this_plug]['alerts_freq_max'] = 15; // Can be 0, to have no limits


// Privacy mode (restrict alerts from sending detailed data, only sends fiat increase / decrease in value when set to 'on')
$plug_conf[$this_plug]['privacy_mode'] = 'on'; // 'on' / 'off' (Default: 'on')


// Balance tracking array (add unlimited addresses as new subarray objects)
// (ONLY BTC / ETH / HNT / SOL / SPL (SOL subtokens) SUPPORTED AS OF 2021/DEC/2)
// ASSET #MUST EXIST# IN EITHER THE $ct_conf['power']['crypto_pair_pref_mrkts'] CONFIG,
// OR THE $ct_conf['power']['btc_currency_mrkts'] CONFIG (BOTH LOCATED IN THE POWER USER SECTION)
// SEE THOSE SECTIONS FOR MORE INFO ON ADDING NEW ASSETS TO THEM
$plug_conf[$this_plug]['tracking'] = array(
																	
																	
												// BTC EXAMPLE
												array(
													'asset' => 'btc', // Ticker Key (LOWERCASE)
													'address' => '3Nw6cvSgnLEFmQ1V4e8RSBG23G7pDjF3hW', // BTC address
													'label' => 'Crypto Tracker BTC Donations' // Description of address
													),
																			
																			
											    // ETH EXAMPLE
											    // (REQUIRES AN API KEY FOR ETHERSCAN!! [SETUP IN GENERAL SECTION OF ADMIN CONFIG])
												array(
													'asset' => 'eth', // Ticker Key (LOWERCASE)
													'address' => '0x644343e8D0A4cF33eee3E54fE5d5B8BFD0285EF8', // ETH address
													'label' => 'Crypto Tracker ETH Donations' // Description of address
													),
																			
												
											    // SOL EXAMPLE
												array(
													'asset' => 'sol', // Ticker Key (LOWERCASE)
													'address' => 'GvX4AU4V9atTBof9dT9oBnLPmPiz3mhoXBdqcxyRuQnU', // SOL address
													'label' => 'Crypto Tracker SOL Donations' // Description of address
													),
																			
												
											    // USDC (SPL token on Solana) TOKEN EXAMPLE
												array(
													'asset' => 'sol||usdc', // 'sol||spl_token_symbol' (LOWERCASE)
													'address' => '5G2GFz6HrmEtWYVZU85wb9WCVVg5zYypMCgxW2Vgkc9q', // SPL token address
													'label' => 'Crypto Tracker USDC Donations' // Description of address
													),
																	
																	
											); // END tracking array




// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>