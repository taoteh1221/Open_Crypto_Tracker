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


// Enable / disable "do not disturb" time (24 HOUR FORMAT, HOURS / MINUTES ONLY, SET EITHER TO BLANK '' TO DISABLE)
$plug_conf[$this_plug]['do_not_dist'] = array(
															// ALWAYS USE THIS FORMAT: '00:00', OR THIS FEATURE WON'T BE ENABLED!
															'on' => '17:15', // Default = '17:15' (5:15pm)
															'off' => '10:30' // Default = '10:30' (10:30am)
															);


// Reminders array (add unlimited reminders as new subarray objects)
$plug_conf[$this_plug]['reminders'] = array(
																	
																	
																// PORTFOLIO RE-BALANCE REVIEW REMINDER
																array(
																		'days' => 30.4167, // Decimals supported (30.4167 days is AVERAGE LENGTH of 1 month)
																		'message' => "Review whether you should re-balance your portfolio (have individual assets take up a different precentage of your portfolio's total " . strtoupper($pt_conf['gen']['btc_prim_currency_pairing']) . " value)." // Reminder message
																		),
																			
																			
																// VITAMIN D / COVID-19 PREVENTION REMINDER
																array(
																		'days' => 4, // Decimals supported
																		'message' => "Take 2000 IU of Vitamin D and 500 MG of Vitamin C every 4 days with food, to help prevent Covid-19 and other viral infections." // Reminder message
																		),
																	
																	
																); // END reminders array




// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>