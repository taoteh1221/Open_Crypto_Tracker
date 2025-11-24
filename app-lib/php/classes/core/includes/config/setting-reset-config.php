<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// RESET CONFIG
////////////////////////////////////////////////////////////////// 


// IF setting resets exist for the app
if (
is_array($ct['dev']['config_allow_resets'])
&& sizeof($ct['dev']['config_allow_resets']) > 0
) {

// If we're upgrading, these could change as the runtime progresses, so set the ORIGINAL vals
$orig_app_version = $ct['app_version'];

$orig_cached_app_version = ( isset($conf_passed['version_states']['app_version']) ? $conf_passed['version_states']['app_version'] : '' );


     foreach ( $ct['dev']['config_allow_resets'] as $reset_key => $reset_val ) {
          
     // Minimize calls
     $config_current_compare = $ct['gen']->version_compare($orig_app_version, $reset_val);
     
     $config_cache_compare = $ct['gen']->version_compare($orig_cached_app_version, $reset_val);
     
          
          // IF UPGRADING AFTER state versioning was introduced, mark it as 'lesser than' version number,
          // thereby activating upgrade SETTING RESETS
          if ( $config_cache_compare['base_diff'] === false ) {
          $config_cache_compare['base_diff'] = -1;
          }
     
     
          // RESETS (if the reset has not run ever yet)
          
          // UPGRADES, if CURRENT version is equal to or greater than $reset_val, and OLD version is less than $reset_val
          // (WE NEED TO CHECK THE CURRENT VERSION TOO, AS WE NEED TO SUPPORT ALL FUTURE VERSIONS [NOT JUST ONE])
          // ($config_cache_compare['base_diff'] is FALSE, IF NON-numeric version variable [presumably from no cached value])
          if (
          is_bool($config_cache_compare['base_diff']) !== true
          && $config_current_compare['base_diff'] >= 0 && $config_cache_compare['base_diff'] < 0
          ) {
          // DO NOTHING (LEAVE THE SETTING RESET IN PLACE, TO BE USED DURING THE UPGRADE)
          }
          // Otherwise, disable resetting this key
          // (setting reset DOWNGRADES are NOT feasible [we reset ENTIRE app for reliability])
          // (WE ALREADY REMOVE ALL UPGRADE STATES IN PRIMARY-INIT.PHP)
          else {
          unset($ct['dev']['config_allow_resets'][$reset_key]);
          }
     
     
     $debugging_array = array(
                              'config_current_compare[base_diff]' => $config_current_compare['base_diff'],
                              'config_cache_compare[base_diff]' => $config_cache_compare['base_diff'],
                             );
                             
     //var_dump($debugging_array); // DEBUGGING
     
     }


}


//var_dump($ct['dev']['config_allow_resets']); // DEBUGGING

//////////////////////////////////////////////////////////////////
// END RESET CONFIG 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
 ?>