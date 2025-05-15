<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// PLUGINS RESET CONFIG
////////////////////////////////////////////////////////////////// 


// Get any saved DB upgrade state
if ( file_exists( $ct['plug']->state_cache('db_upgrade_resets.dat') ) ) {
$ct['db_upgrade_resets_state']['plug'][$this_plug] = json_decode( trim( file_get_contents( $ct['plug']->state_cache('db_upgrade_resets.dat') ) ) , true);
}


foreach ( $ct['dev']['plugin_allow_resets'][$this_plug] as $reset_key => $reset_val ) {
     
// Minimize calls
$plug_current_compare = $ct['gen']->version_compare($plug['conf'][$this_plug]['plug_version'], $reset_val);

$plug_cache_compare = $ct['gen']->version_compare($ct['cached_plug_version'][$this_plug], $reset_val);


     // RESETS (if the reset has not run ever yet)
     
     // UPGRADES, if NEW version is equal to or greater than $reset_val, and OLD version is less than $reset_val
     if (
     !isset($ct['db_upgrade_resets_state']['plug']['upgrade'][$this_plug][$reset_key][$reset_val])
     || $plug_current_compare['base_diff'] >= 0 && $plug_cache_compare['base_diff'] < 0
     ) {
           
     // Version specific, FOR STATE TRACKING (to avoid RE-resetting, we save this state to the cache)
     $ct['db_upgrade_resets_state']['plug']['upgrade'][$this_plug][$reset_key][$reset_val] = true;
           
     // We can safely remove any saved DOWNGRADE state info, since we UPGRADED
     unset($ct['db_upgrade_resets_state']['plug']['downgrade'][$this_plug][$reset_key]);
     
     }
     // Otherwise, disable resetting this key
     else {
     unset($ct['dev']['plugin_allow_resets'][$this_plug][$reset_key]);
     }
     
     
     // DOWNGRADES, if NEW version is less than $reset_val, and OLD version is equal to or greater than $reset_val
     if (
     !isset($ct['db_upgrade_resets_state']['plug']['downgrade'][$this_plug][$reset_key][$reset_val])
     || $plug_current_compare['base_diff'] < 0 && $plug_cache_compare['base_diff'] >= 0
     ) {
           
     // Version specific, FOR STATE TRACKING (to avoid RE-resetting, we save this state to the cache)
     $ct['db_upgrade_resets_state']['plug']['downgrade'][$this_plug][$reset_key][$reset_val] = true;
           
     // We can safely remove any saved UPGRADE state info, since we DOWNGRADED
     unset($ct['db_upgrade_resets_state']['plug']['upgrade'][$this_plug][$reset_key]);
     
     }
     // Otherwise, disable resetting this key
     else {
     unset($ct['dev']['plugin_allow_resets'][$this_plug][$reset_key]);
     }


var_dump($ct['dev']['plugin_allow_resets']); // DEBUGGING

}


// Save $ct['db_upgrade_resets_state']['plug'][$this_plug] to cache in json format
$saved_state = json_encode($ct['db_upgrade_resets_state']['plug'][$this_plug], JSON_PRETTY_PRINT);
     
$ct['cache']->save_file( $ct['plug']->state_cache('db_upgrade_resets.dat') , $saved_state);

//////////////////////////////////////////////////////////////////
// END PLUGINS RESET CONFIG 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
 ?>