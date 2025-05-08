<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// Version number of this plugin (MANDATORY)
$plug['conf'][$this_plug]['plug_version'] = '1.01.00'; // VERSION BUMP DATE: 2025/May/7TH


// FULL RESET(s) on specified settings (CAN be an arrays), ONLY IF plugin version has changed
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
$plug['conf'][$this_plug]['ui_name'] = 'Recurring Reminder'; // (defaults to $this_plug if not set)


// Enable / disable "do not disturb" time (#24 HOUR FORMAT#, HOURS / MINUTES ONLY, SET EITHER TO BLANK '' TO DISABLE)
// THIS TAKES INTO ACCOUNT YOUR TIME ZONE OFFSET, IN 'local_time_offset' IN THE MAIN CONFIG OF THIS APP ('GENERAL' SECTION)
$plug['conf'][$this_plug]['do_not_disturb'] = array(
									     // ALWAYS USE THIS FORMAT: '00:00', OR THIS FEATURE WON'T BE ENABLED!
										'on' => '16:45', // DND #START#, Default = '16:45' (4:45 AT NIGHT)
										'off' => '07:15' // DND #END#, Default = '07:15' (7:15 IN MORNING)
									   );


// Reminders array (add unlimited reminders as new subarray objects)
$plug['conf'][$this_plug]['reminders'] = array(
																	
																	
										// PORTFOLIO RE-BALANCE REVIEW REMINDER
										array(
											 'days' => 30,
											 'message' => "Review whether you should re-balance your portfolio (have individual assets take up a different percentage of your portfolio's total " . strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair']) . " value)." // Reminder message
											 ),
																			
																			
										// VITAMIN D / COVID-19 PREVENTION REMINDER
										array(
											 'days' => 4,
											 'message' => "Take 2000 IU of Vitamin D and 500 MG of Vitamin C every 4 days with food, to help prevent Covid-19 and other viral infections." // Reminder message
											 ),
																	
																	
									); // END reminders array




// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>