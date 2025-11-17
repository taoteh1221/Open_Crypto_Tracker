<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

		
// DEBUGGING
//$debug_data = $this_plug . ' cron init successful';
//$debug_cache_file = $ct['plug']->debug_cache($this_plug . '_cron_init.dat', $this_plug);
//$ct['cache']->save_file($debug_cache_file, $debug_data);


require($ct['plug']->plug_dir() . '/plug-lib/runtime-modes/cron/solana/cron-solana-nodes.php');

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>