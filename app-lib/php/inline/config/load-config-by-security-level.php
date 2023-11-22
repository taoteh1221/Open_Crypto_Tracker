<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// LOAD CONFIG
//////////////////////////////////////////////////////////////////


// Checks on VALIDATED / SECURE config updates IN PROGRESS
$verified_update_request = $ct['admin']->valid_secure_config_update_request();


// load_cached_config() LOADS *BEFORE* PLUGIN CONFIGS IN *MEDIUM / NORMAL* ADMIN SECURITY MODES
// (UNLESS IT'S A CT_CONF USER-INITIATED RESET)
// ALSO QUEUE ANY REQUESTED UPDATE AFTER LOADING, IF AUTHORIZED
// (WE PROCESS IT AT THE BOTTOM OF THIS FILE [SAVE IT TO FILE STORAGE])
if ( $admin_area_sec_level != 'high' && !$reset_config ) {
$ct['cache']->load_cached_config();
require('app-lib/php/inline/config/after-load-config.php'); // MUST BE IMMEADIATELY AFTER CACHED CONFIG LOADING
}


// Refresh available plugins / register active plugins 
require('app-lib/php/inline/config/plugins-config.php');


// If no comparison digest of the default config yet, save it now to the cache (MUST be done AFTER registering active plugins)
if ( $check_default_ct_conf == null ) {
$check_default_ct_conf = md5( serialize($default_ct_conf) );
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/default_ct_conf_md5.dat', $check_default_ct_conf);
sleep(1); // Chill for a second, since we just saved the default conf digest
}


// If we are mid-flight on activating / deactivating proxies in the admin interface, then use that 'allow_proxies' value instead
// (so we can run checks on the functionality of any newly-added proxies in queue_config_update() DIRECTLY BELOW)
// (WE DON'T WANT TO INADVERTANTLY OVERWRITE THE CONFIG VALUE IN CERTAIN EDGE CASES, SO WE SET / USE A DIFFERENT VAR: $activate_proxies)
if ( isset($verified_update_request['allow_proxies']) ) {
$activate_proxies = $verified_update_request['allow_proxies'];
}
else {
$activate_proxies = $ct['conf']['proxy']['allow_proxies'];
}


// Queue up any user updates to the config (sets $update_config flag if there are any, MUST be done AFTER registering active plugins)
if ( $admin_area_sec_level != 'high' ) {
$ct['admin']->queue_config_update();
}


// IF ct_conf CACHE RESET (MUST be done AFTER registering active plugins / OVERRIDE ANY $update_config)
if ( $reset_config ) {
$ct['conf'] = $ct['cache']->update_cached_config(false, false, true); // Reset flag
require('app-lib/php/inline/config/after-load-config.php'); // MUST BE IMMEADIATELY AFTER CACHED CONFIG LOADING
}
// Updating cached config (APP OR USER INITIATED...SO THIS CAN BE ANY SECURITY MODE)
else if ( $update_config ) {
$ct['conf'] = $ct['cache']->update_cached_config($ct['conf']);
$update_config = false; // Set back to false IMMEADIATELY, since this is a global var
require('app-lib/php/inline/config/after-load-config.php'); // MUST BE IMMEADIATELY AFTER CACHED CONFIG LOADING
}


// load_cached_config() IN *HIGH* ADMIN SECURITY MODE, OR FOR A *PLUGINS* UPGRADE CHECK (NOW THAT WE REGISTERED ANY ACTIVE PLUGINS)
// (MUST be done AFTER registering active plugins / AFTER *ANY* $reset_config)
// (ONLY IF NO $reset_config WAS ALREADY TRIGGERED [IN WHICH CASE WE'D *ALREADY* HAVE THE CACHED CONFIG *FULLY* REFRESHED / RELOADED])
// FOR UPGRADES: WE DON'T COMBINE ALL UPGRADE CHECKS HERE, BECAUSE THE EARLIER WE RUN UPGRADES ON THE ***MAIN CONFIG***,
// THE EARLIER WE CATCH AND AUTO-REPAIR OR UPGRADE VALUES TO PREVENT THE APP FROM CRASHING (USING AN OUTDATED / CORRUPT CONFIG)
if ( $app_upgrade_check && !$reset_config || $admin_area_sec_level == 'high' && !$reset_config ) {
$ct['cache']->load_cached_config();
require('app-lib/php/inline/config/after-load-config.php'); // MUST BE IMMEADIATELY AFTER CACHED CONFIG LOADING
}


gc_collect_cycles(); // Clean memory cache


//////////////////////////////////////////////////////////////////
// END LOAD CONFIG
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>