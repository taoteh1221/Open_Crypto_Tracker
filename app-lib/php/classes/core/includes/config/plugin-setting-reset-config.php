<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// PLUGINS RESET CONFIG
////////////////////////////////////////////////////////////////// 


// IF setting resets exist for this plugin
if (
is_array($ct['dev']['plugin_allow_resets'][$this_plug])
&& sizeof($ct['dev']['plugin_allow_resets'][$this_plug]) > 0
) {

$orig_plug_version = $ct['plug_version'][$this_plug];

$orig_cached_plug_version = ( isset($conf_passed['version_states']['plug_version'][$this_plug]) ? $conf_passed['version_states']['plug_version'][$this_plug] : '' );


     foreach ( $ct['dev']['plugin_allow_resets'][$this_plug] as $reset_key => $reset_val ) {
          
     // Minimize calls
     $plug_current_compare = $ct['gen']->version_compare($orig_plug_version, $reset_val);
     
     $plug_cache_compare = $ct['gen']->version_compare($orig_cached_plug_version, $reset_val);
     
          
          // IF UPGRADING AFTER state versioning was introduced, mark it as 'lesser than' version number,
          // thereby activating upgrade SETTING RESETS
          if ( $plug_cache_compare['base_diff'] === false ) {
          $plug_cache_compare['base_diff'] = -1;
          }
     
     
          // RESETS (if the reset has not run ever yet)
          
          // UPGRADES, if CURRENT version is equal to or greater than $reset_val, and OLD version is less than $reset_val
          // (WE NEED TO CHECK THE CURRENT VERSION TOO, AS WE NEED TO SUPPORT ALL FUTURE VERSIONS [NOT JUST ONE])
          // ($plug_cache_compare['base_diff'] is FALSE, IF NON-numeric version variable [presumably from no cached value])
          if (
          is_bool($plug_cache_compare['base_diff']) !== true
          && $plug_current_compare['base_diff'] >= 0 && $plug_cache_compare['base_diff'] < 0
          ) {
          // DO NOTHING (LEAVE THE SETTING RESET IN PLACE, TO BE USED DURING THE UPGRADE)
          }
          // Otherwise, disable resetting this key
          // (setting reset DOWNGRADES are NOT feasible [we reset ENTIRE plugin for reliability])
          // (WE ALREADY REMOVE ALL UPGRADE STATES IN PLUGINS-INIT.PHP)
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