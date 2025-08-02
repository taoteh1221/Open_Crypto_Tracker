<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// LOAD CONFIG
//////////////////////////////////////////////////////////////////


// Checks on VALIDATED / SECURE config updates IN PROGRESS
$ct['verified_update_request'] = $ct['sec']->valid_secure_config_update_request();


// load_cached_config() LOADS *BEFORE* PLUGIN CONFIGS IN *MEDIUM / NORMAL* ADMIN SECURITY MODES
// (UNLESS IT'S A CT_CONF USER-INITIATED RESET)
// ALSO QUEUE ANY REQUESTED UPDATE AFTER LOADING, IF AUTHORIZED
// (WE PROCESS IT AT THE BOTTOM OF THIS FILE [SAVE IT TO FILE STORAGE])
if ( $ct['admin_area_sec_level'] != 'high' && !$ct['reset_config'] ) {
$ct['cache']->load_cached_config();
require($ct['base_dir'] . '/app-lib/php/inline/config/after-load-config.php'); // MUST BE IMMEADIATELY AFTER CACHED CONFIG LOADING
}


// Refresh available plugins / register active plugins 
require($ct['base_dir'] . '/app-lib/php/inline/init/plugins-init.php');


// If no comparison digest of the default config yet, save it now to the cache (MUST be done AFTER registering active plugins)
if ( $check_default_ct_conf == null ) {
$check_default_ct_conf = md5( serialize($default_ct_conf) );
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/default_ct_conf_md5.dat', $check_default_ct_conf);
sleep(1); // Chill for a second, since we just saved the default conf digest
}


// If we are mid-flight on activating / deactivating proxies in the admin interface, then use that 'allow_proxies' value instead
// (so we can run checks on the functionality of any newly-added proxies in queue_config_update() DIRECTLY BELOW)
// (WE DON'T WANT TO INADVERTANTLY OVERWRITE THE CONFIG VALUE IN CERTAIN EDGE CASES, SO WE SET / USE A DIFFERENT VAR: $ct['activate_proxies'])
if ( isset($ct['verified_update_request']['allow_proxies']) ) {
$ct['activate_proxies'] = $ct['verified_update_request']['allow_proxies'];
}
else {
$ct['activate_proxies'] = $ct['conf']['proxy']['allow_proxies'];
}


// Queue up any user updates to the config (sets $ct['update_config'] flag if there are any, MUST be done AFTER registering active plugins)
if ( $ct['verified_update_request'] && $ct['admin_area_sec_level'] != 'high' ) {
$ct['admin']->queue_config_update();
}


// IF ct_conf CACHE RESET (MUST be done AFTER registering active plugins / OVERRIDE ANY $ct['update_config'])
if ( $ct['reset_config'] ) {
$ct['conf'] = $ct['cache']->update_cached_config(false, false, true); // Reset flag
require($ct['base_dir'] . '/app-lib/php/inline/config/after-load-config.php'); // MUST BE IMMEADIATELY AFTER CACHED CONFIG LOADING
}
// Updating cached config (APP OR USER INITIATED...SO THIS CAN BE ANY SECURITY MODE)
else if ( $ct['update_config'] ) {
$ct['conf'] = $ct['cache']->update_cached_config($ct['conf']);
$ct['update_config'] = false; // Set back to false IMMEADIATELY, since this is a global var
require($ct['base_dir'] . '/app-lib/php/inline/config/after-load-config.php'); // MUST BE IMMEADIATELY AFTER CACHED CONFIG LOADING
}


// load_cached_config() IN *HIGH* ADMIN SECURITY MODE, OR FOR A *PLUGINS* CONFIG UPGRADE CHECK (NOW THAT WE REGISTERED ANY ACTIVE PLUGINS)
// (MUST be run AFTER registering active plugins!)
// (ONLY RUN IF NO $ct['reset_config'] WAS ALREADY TRIGGERED [IN WHICH CASE WE'D *ALREADY* HAVE THE CACHED CONFIG *FULLY* UPGRADED / RELOADED])
// FOR UPGRADES: WE DON'T COMBINE ALL UPGRADE CHECKS HERE [*MAIN* CONFIG UPGRADE CHECK IS RUN **EARLIER**], BECAUSE THE EARLIER WE RUN UPGRADES
// ON THE ***MAIN CONFIG***, THE EARLIER WE CATCH AND AUTO-REPAIR OR UPGRADE VALUES TO PREVENT THE APP FROM CRASHING (IF IT'S USING AN OUTDATED / CORRUPT CONFIG)
if ( $ct['admin_area_sec_level'] == 'high' && !$ct['reset_config'] || $ct['plugin_upgrade_check'] && !$ct['reset_config'] ) {
$ct['cache']->load_cached_config();
require($ct['base_dir'] . '/app-lib/php/inline/config/after-load-config.php'); // MUST BE IMMEADIATELY AFTER CACHED CONFIG LOADING
}


gc_collect_cycles(); // Clean memory cache


//////////////////////////////////////////////////////////////////
// END LOAD CONFIG
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>