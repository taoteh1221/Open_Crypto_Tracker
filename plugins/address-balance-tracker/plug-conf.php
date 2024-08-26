<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// PLUGIN setting keys to ALLOW cached config RESETS on (during cached config upgrades)
$ct['dev']['plugin_allow_resets'][$this_plug] = array(
                                                      // 'plugin-setting-key',
                                                     );


// All "plug-conf.php" PLUGIN CONFIG settings MUST BE INSIDE THE "$plug['conf'][$this_plug]" ARRAY (sub-arrays are allowed)

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
// Solana SPL tokens MUST:
// 1) Have a Jupiter Aggregator SOL market in the portfolio assets configuration, for "Privacy Mode" to work properly
// 2) Use your wallet's TOKEN address (NOT your primary wallet address, as Solana SPL tokens
// DERIVE *secondary addresses* for each token in your wallet)
$plug['conf'][$this_plug]['tracking'] = array(
																	
																	
												// BTC EXAMPLE
												array(
													'asset' => 'btc', // Ticker Key (LOWERCASE)
													'label' => 'Crypto Tracker BTC Donations', // Description of address
													'address' => '3Nw6cvSgnLEFmQ1V4e8RSBG23G7pDjF3hW', // BTC address
													),
																			
																			
											    // ETH EXAMPLE
											    // (REQUIRES AN API KEY FOR ETHERSCAN!! [SETUP IN EXTERNAL APIS SECTION OF ADMIN CONFIG])
												array(
													'asset' => 'eth', // Ticker Key (LOWERCASE)
													'label' => 'Crypto Tracker ETH Donations', // Description of address
													'address' => '0x644343e8D0A4cF33eee3E54fE5d5B8BFD0285EF8', // ETH address
													),
																			
												
											    // SOL EXAMPLE
												array(
													'asset' => 'sol', // Ticker Key (LOWERCASE)
													'label' => 'Crypto Tracker SOL Donations', // Description of address
													'address' => 'GvX4AU4V9atTBof9dT9oBnLPmPiz3mhoXBdqcxyRuQnU', // SOL address
													),
																			
												
											    // USDC (SPL token on Solana) TOKEN EXAMPLE
												array(
													'asset' => 'sol||usdc', // 'sol||spl_token_symbol' (LOWERCASE)
													'label' => 'Crypto Tracker USDC Donations (on Solana)', // Description of address
													'address' => '5G2GFz6HrmEtWYVZU85wb9WCVVg5zYypMCgxW2Vgkc9q', // SPL token address
													),
																	
																	
											); // END tracking array




// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>