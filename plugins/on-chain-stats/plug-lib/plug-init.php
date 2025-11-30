<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:start');

// DEBUGGING
//$debug_data = $this_plug . ' init successful';
//$debug_cache_file = $ct['plug']->debug_cache($this_plug . '_init.dat', $this_plug);
//$ct['cache']->save_file($debug_cache_file, $debug_data);
	

// Only per-minute limits in docs, BUT we add per-second to keep the API server happy
$ct['dev']['throttled_apis']['ip-api.com'] = array(
                                                       'min_cache_time' => null,
                                                       'per_day' => null,
                                                       'per_minute' => 15,
                                                       'per_second' => 5, // 100000 maximum, decimals (0.25 minimum) supported
                                                   );


$onchain_stat_selected_networks = array_map('trim', explode(',', $plug['conf'][$this_plug]['selected_networks']) );


// Setup directory structure for network caches...
foreach ( $onchain_stat_selected_networks as $network_name_key ) {
     
     if ( $network_name_key == '' ) {
     continue;
     }

require($ct['plug']->plug_dir() . '/plug-lib/cache-dirs.php');

}


if ( $no_onchain_stat_light_charts ) {

$ct['gen']->log(
        			'system_error',
        			'cannot create "'.$this_plug.'" plugin LIGHT CHART directories'
        			);
        
}


// WE ONLY WANT TO ALLOW ANY WHITESPACE USED IN INTERFACING TO RUN IN 'UI' RUNTIME MODE!!

// Runtime modes
if ( $runtime_mode == 'ui' ) {
require($ct['plug']->plug_dir() . '/plug-lib/runtime-modes/ui-runtime.php');
}
elseif ( $runtime_mode == 'cron' ) {
require($ct['plug']->plug_dir() . '/plug-lib/runtime-modes/cron-runtime.php');
}
elseif ( $runtime_mode == 'webhook' ) {
require($ct['plug']->plug_dir() . '/plug-lib/runtime-modes/webhook-runtime.php');
}


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:end');


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>