<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:start');


// Only per-minute limits in docs, BUT we add per-second to keep the API server happy
$ct['dev']['throttled_apis']['ip-api.com'] = array(
                                                       'min_cache_time' => null,
                                                       'per_day' => null,
                                                       'per_minute' => 15,
                                                       'per_second' => 5, // 100000 maximum, decimals (0.25 minimum) supported
                                                   );


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