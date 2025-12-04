<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// Version number of this plugin (MANDATORY)
$ct['plug_version'][$this_plug] = '0.91.00'; // VERSION BUMP DATE: 2025/November/5TH


// FULL RESET(s) on specified plugin settings (the setting CAN include internal subarrays)
// Resets ENTIRE setting, IF upgrading from EARLIER version than param value
// ONLY top-level KEY NAMES WITHIN A PLUGIN CONFIG ARRAY can be reset:
// $plug['conf'][$this_plug]['plugin-setting-key-1']
$ct['dev']['plugin_allow_resets'][$this_plug] = array(
                                                      // setting key id, and plugin version number of when the reset was added
                                                      // NO DUPLICATES, REPLACE KEY'S VALUE WITH LATEST AFFECTED VERSION!
                                                      // 'plugin-setting-key-1' => '0.90.00',
                                                      'ui_location' => '0.91.00',
                                                     );


// All PLUGIN CONFIG settings MUST BE INSIDE THE "$plug['conf'][$this_plug]" ARRAY (sub-arrays are allowed)

// EXAMPLES...

// $plug['conf'][$this_plug]['SETTING_NAME_HERE'] = 'mysetting'; 

// $plug['conf'][$this_plug]['SETTING_NAME_HERE'] = array('mysetting1', 'mysetting2');


// What runtime modes this plugin should run during (MANDATORY)
$plug['conf'][$this_plug]['runtime_mode'] = 'all'; // 'cron', 'webhook', 'ui', 'all'


// If running in the UI, set the preferred location it should show in
// 'none', 'nav_menu_tab', 'nav_menu_page', 'tools', 'more_stats' (defaults to 'none' if not set)
$plug['conf'][$this_plug]['ui_location'] = 'nav_menu_tab'; 


// If running in the UI, set the preferred plugin name that should show for end-users
$plug['conf'][$this_plug]['ui_name'] = 'On-Chain Stats'; // (defaults to $this_plug if not set)


// Default settings for Node Stats chart height / menu size (in the 'View More Stats' modal window, linked at bottom of Portfolio page)
// CHART HEIGHT MIN/MAX = 400/900 (increments of 100), MENU SIZE MIN/MAX (increments of 1) = 7/16
$plug['conf'][$this_plug]['node_count_chart_defaults'] = '400||16'; // 'chart_height||menu_size' (default = '400||16')


// Default settings for TPS chart height / menu size (in the 'View More Stats' modal window, linked at bottom of Portfolio page)
// CHART HEIGHT MIN/MAX = 400/900 (increments of 100), MENU SIZE MIN/MAX (increments of 1) = 7/16
$plug['conf'][$this_plug]['tps_chart_defaults'] = '400||16'; // 'chart_height||menu_size' (default = '400||16')


// Default settings for geolocation map height (in the 'View More Stats' modal window, linked at bottom of Portfolio page)
// CHART HEIGHT MIN/MAX = 400/900 (increments of 100)
$plug['conf'][$this_plug]['node_geolocation_map_height_default'] = '500'; // (default = '500')


// Comma-separated list of the blockchain networks you want to track onchain stats for
$plug['conf'][$this_plug]['selected_networks'] = 'bitcoin,solana'; // (all options = 'bitcoin,solana')

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>