<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

		
// DEBUGGING
//$debug_data = $this_plug . ' cron init successful';
//$debug_cache_file = $ct['plug']->debug_cache($this_plug . '_cron_init.dat', $this_plug);
//$ct['cache']->save_file($debug_cache_file, $debug_data);


foreach ( $onchain_stat_selected_networks as $network_name_key ) {
     
     if ( $network_name_key == '' ) {
     continue;
     }

require($ct['plug']->plug_dir() . '/plug-lib/runtime-modes/cron/'.$network_name_key.'/cron-'.$network_name_key.'-nodes.php');

}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>