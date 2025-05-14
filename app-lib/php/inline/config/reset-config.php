<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// RESET CONFIG
////////////////////////////////////////////////////////////////// 


// Get any saved DB upgrade state
if ( file_exists($ct['base_dir'] . '/cache/vars/state-tracking/db_upgrade_resets.dat') ) {
$ct['db_upgrade_resets_state']['app'] = json_decode( trim( file_get_contents($ct['base_dir'] . '/cache/vars/state-tracking/db_upgrade_resets.dat') ) , true);
}


foreach ( $ct['dev']['config_allow_resets'] as $reset_key => $reset_val ) {


     // RESETS (if the reset has not run ever yet)
     if ( !isset($ct['db_upgrade_resets_state']['app'][$reset_key][$reset_val]) ) {
     
     // Minimize calls
     $config_current_compare = $ct['gen']->version_compare($ct['app_version'], $reset_val);

     $config_cache_compare = $ct['gen']->version_compare($ct['cached_app_version'], $reset_val);


           if (
           // UPGRADES, if NEW version is equal to or greater than $reset_val, and OLD version is less than $reset_val
           $config_current_compare['base_diff'] >= 0 && $config_cache_compare['base_diff'] < 0
           // DOWNGRADES, if NEW version is less than $reset_val, and OLD version is equal to or greater than $reset_val
           || $config_current_compare['base_diff'] < 0 && $config_cache_compare['base_diff'] >= 0
           ) {
           
           // Version specific, FOR STATE TRACKING (to avoid RE-resetting, we save this state to the cache)
           $ct['db_upgrade_resets_state']['app'][$reset_key][$reset_val] = true;
           
           }
           // Otherwise, disable resetting this key
           else {
           unset($ct['dev']['config_allow_resets'][$reset_key]);
           }
     
     
     }


var_dump($ct['dev']['config_allow_resets']); // DEBUGGING

}


// Save $ct['db_upgrade_resets_state']['app'] to cache in json format
$saved_state = json_encode($ct['db_upgrade_resets_state']['app'], JSON_PRETTY_PRINT);
     
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/db_upgrade_resets.dat', $saved_state);

//////////////////////////////////////////////////////////////////
// END RESET CONFIG 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
 ?>