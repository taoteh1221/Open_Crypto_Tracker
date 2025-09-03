<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// Version number of this plugin (MANDATORY)
$ct['plug_version'][$this_plug] = '1.01.00'; // VERSION BUMP DATE: 2025/May/7TH


// FULL RESET(s) on specified settings (CAN include subarrays)
// Resets ENTIRE setting, IF upgrading from EARLIER version than param value
$ct['dev']['plugin_allow_resets'][$this_plug] = array(
                                                      // key id, and plugin version number of when the reset was added
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
$plug['conf'][$this_plug]['ui_name'] = 'Address Balance Tracker'; // (defaults to $this_plug if not set)


// Re-allow SAME address balance alert(s) messages after X HOURS (per alert config)
$plug['conf'][$this_plug]['alerts_frequency_maximum'] = 1; // Can be 0, to have no limits


// Privacy mode (restrict alerts from sending detailed data, only sends fiat increase / decrease in value when set to 'on')
$plug['conf'][$this_plug]['privacy_mode'] = 'on'; // 'on' / 'off' (Default: 'on')


// Balance tracking array (add unlimited addresses as new subarray objects)
// (ONLY BTC / ETH / SOL / SPL (SOL subtokens) SUPPORTED AS OF 2021/DEC/2ND)

/*

Solana SPL tokens MUST:
1) Have EITHER a Jupiter Aggregator OR CoinGecko Terminal Solana market in it's asset config, to AUTOMATICALLY be designated as such / included in the SPL ASSETS LIST (as a selection option, for the "Asset" setting)

2) Have EITHER a Bitcoin (BTC) OR Solana (SOL) market in it's asset config, for "Privacy Mode" to work properly

3) Use your wallet's TOKEN account / address, that's specifically associated with this asset (NOT your primary wallet address, as Solana SPL tokens DERIVE *secondary addresses* for each token in your wallet)

*/

$plug['conf'][$this_plug]['tracking'] = array(
																	
																	
												// BTC EXAMPLE
												array(
													'asset' => 'btc', // Ticker Key (LOWERCASE)
													'label' => 'Crypto Tracker BTC Donations', // Description of address
													'crypto_address' => '3Nw6cvSgnLEFmQ1V4e8RSBG23G7pDjF3hW', // BTC address
													),
																			
																			
											    // ETH EXAMPLE
											    // (REQUIRES AN API KEY FOR ETHERSCAN!! [SETUP IN EXTERNAL APIS SECTION OF ADMIN CONFIG])
												array(
													'asset' => 'eth', // Ticker Key (LOWERCASE)
													'label' => 'Crypto Tracker ETH Donations', // Description of address
													'crypto_address' => '0x644343e8D0A4cF33eee3E54fE5d5B8BFD0285EF8', // ETH address
													),
																			
												
											    // SOL EXAMPLE
												array(
													'asset' => 'sol', // Ticker Key (LOWERCASE)
													'label' => 'Crypto Tracker SOL Donations', // Description of address
													'crypto_address' => 'GvX4AU4V9atTBof9dT9oBnLPmPiz3mhoXBdqcxyRuQnU', // SOL address
													),
																			
												
											    // USDC (SPL token on Solana) TOKEN EXAMPLE
												array(
													'asset' => 'sol||usdc', // 'sol||spl_token_symbol' (LOWERCASE)
													'label' => 'Crypto Tracker USDC Donations (on Solana)', // Description of address
													'crypto_address' => '5G2GFz6HrmEtWYVZU85wb9WCVVg5zYypMCgxW2Vgkc9q', // SPL token address
													),
																	
																	
											); // END tracking array




// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>