<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// RESET CONFIG
////////////////////////////////////////////////////////////////// 


// Get any saved DB upgrade state
if ( file_exists($ct['base_dir'] . '/cache/vars/state-tracking/app_setting_resets.dat') ) {
$ct['db_upgrade_resets_state']['app'] = json_decode( trim( file_get_contents($ct['base_dir'] . '/cache/vars/state-tracking/app_setting_resets.dat') ) , true);
}
// Or set a placeholder, to avoid caching nothing after processing
else {
$ct['db_upgrade_resets_state']['app']['placeholder'] = true;
}


// If we're upgrading, these could change as the runtime progresses, so set the ORIGINAL vals
$orig_app_version = $ct['app_version'];

$orig_cached_app_version = $ct['cached_app_version'];


foreach ( $ct['dev']['config_allow_resets'] as $reset_key => $reset_val ) {
     
// Minimize calls
$config_current_compare = $ct['gen']->version_compare($orig_app_version, $reset_val);

$config_cache_compare = $ct['gen']->version_compare($orig_cached_app_version, $reset_val);


     // RESETS (if the reset has not run ever yet)
     
     // UPGRADES, if CURRENT version is equal to or greater than $reset_val, and OLD version is less than $reset_val
     // (WE NEED TO CHECK THE CURRENT VERSION TOO, AS WE NEED TO SUPPORT ALL FUTURE VERSIONS [NOT JUST ONE])
     // ($config_cache_compare['base_diff'] is FALSE, IF NON-numeric version variable [presumably from no cached value])
     if (
     is_bool($config_cache_compare['base_diff']) !== true
     && !isset($ct['db_upgrade_resets_state']['app']['upgrade'][$reset_key][$reset_val])
     && $config_current_compare['base_diff'] >= 0 && $config_cache_compare['base_diff'] < 0
     ) {
     
     $ct['db_upgrade_desc']['app'] = 'UPGRADE';

     // Version specific, FOR STATE TRACKING (to avoid RE-resetting, we save this state to the cache)
     $ct['db_upgrade_resets_state']['app']['upgrade'][$reset_key][$reset_val] = true;
           
     }
     // Otherwise, disable resetting this key
     // (setting reset DOWNGRADES are NOT feasible [we reset ENTIRE app for reliability])
     else {
     
     $ct['db_upgrade_desc']['app'] = 'UPDATE'; // For clean logging (app only [not plugins])

     unset($ct['dev']['config_allow_resets'][$reset_key]);
           
          // We MUST remove any saved UPGRADE state info, since we DOWNGRADED
          // (so we can upgrade again later, if we want to)
          if ( $config_cache_compare['base_diff'] > 0 ) {
          unset($ct['db_upgrade_resets_state']['app']['upgrade'][$reset_key]);
          }

     }


$debugging_array = array(
                         'config_current_compare[base_diff]' => $config_current_compare['base_diff'],
                         'config_cache_compare[base_diff]' => $config_cache_compare['base_diff'],
                        );
                        
//var_dump($debugging_array); // DEBUGGING

}



//var_dump($ct['dev']['config_allow_resets']); // DEBUGGING


// Save $ct['db_upgrade_resets_state']['app'] to cache in json format
$saved_state = json_encode($ct['db_upgrade_resets_state']['app'], JSON_PRETTY_PRINT);
     
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/app_setting_resets.dat', $saved_state);

//////////////////////////////////////////////////////////////////
// END RESET CONFIG 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
 ?>