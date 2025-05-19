<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// PLUGINS RESET CONFIG
////////////////////////////////////////////////////////////////// 


// Get any saved DB upgrade state
if ( file_exists( $ct['plug']->state_cache('plug_setting_resets.dat') ) ) {
$ct['db_upgrade_resets_state']['plug'][$this_plug] = json_decode( trim( file_get_contents( $ct['plug']->state_cache('plug_setting_resets.dat') ) ) , true);
}
// Or set a placeholder, to avoid caching nothing after processing
else {
$ct['db_upgrade_resets_state']['plug'][$this_plug]['placeholder'] = true;
}


// IF setting resets exist for this plugin
if (
is_array($ct['dev']['plugin_allow_resets'][$this_plug])
&& sizeof($ct['dev']['plugin_allow_resets'][$this_plug]) > 0
) {

// If we're upgrading, these could change as the runtime progresses, so set the ORIGINAL vals
$orig_app_version = $ct['app_version'];

$orig_plug_version = $ct['plug_version'][$this_plug];

$orig_cached_plug_version = $ct['cached_plug_version'][$this_plug];


     foreach ( $ct['dev']['plugin_allow_resets'][$this_plug] as $reset_key => $reset_val ) {
          
     // Minimize calls
     $plug_current_compare = $ct['gen']->version_compare($orig_plug_version, $reset_val);
     
     $plug_cache_compare = $ct['gen']->version_compare($orig_cached_plug_version, $reset_val);
     
          
          // IF UPGRADING AFTER plugin versioning was introduced, we have no previous plugin version cache,
          // BUT we can safely mark it as 'lesser than' version number, thereby activating upgrade checks
          // (cached APP version already exists, BUT version_compare() returns FALSE, because
          // cached PLUGIN version does NOT exist yet...so we can presume LACK OF plugin versioning was culprit)
          if ( isset($orig_app_version) && $plug_cache_compare['base_diff'] === false ) {
          $plug_cache_compare['base_diff'] = -1;
          }
     
     
          // RESETS (if the reset has not run ever yet)
          
          // UPGRADES, if CURRENT version is equal to or greater than $reset_val, and OLD version is less than $reset_val
          // (WE NEED TO CHECK THE CURRENT VERSION TOO, AS WE NEED TO SUPPORT ALL FUTURE VERSIONS [NOT JUST ONE])
          // ($plug_cache_compare['base_diff'] is FALSE, IF NON-numeric version variable [presumably from no cached value])
          if (
          is_bool($plug_cache_compare['base_diff']) !== true
          && !isset($ct['db_upgrade_resets_state']['plug'][$this_plug]['upgrade'][$reset_key][$reset_val])
          && $plug_current_compare['base_diff'] >= 0 && $plug_cache_compare['base_diff'] < 0
          ) {
          
          // Version specific, FOR STATE TRACKING (to avoid RE-resetting, we save this state to the cache)
          $ct['db_upgrade_resets_state']['plug'][$this_plug]['upgrade'][$reset_key][$reset_val] = true;
          
          }
          // Otherwise, disable resetting this key
          // (setting reset DOWNGRADES are NOT feasible [we reset ENTIRE plugin for reliability])
          // (WE ALREADY REMOVE ALL UPGRADE STATES IN PLUGINS-CONFIG.PHP)
          else {
          unset($ct['dev']['plugin_allow_resets'][$this_plug][$reset_key]);
          }
     
     
     $debugging_array = array(
                              'plug_current_compare[base_diff]' => $plug_current_compare['base_diff'],
                              'plug_cache_compare[base_diff]' => $plug_cache_compare['base_diff'],
                             );
     
     //var_dump($debugging_array); // DEBUGGING
     
     }


}


//var_dump($ct['dev']['plugin_allow_resets']); // DEBUGGING

//////////////////////////////////////////////////////////////////
// END PLUGINS RESET CONFIG 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
 ?>