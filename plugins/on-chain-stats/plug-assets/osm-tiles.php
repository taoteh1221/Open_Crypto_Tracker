<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

 
// Runtime mode
$runtime_mode = 'osm_tiles';

// Change directory
chdir("../../../");

// FLAG a fast runtime library to run (that we auto-exit after running),
// to speed up runtime
$fast_runtime_lib = "plugins/on-chain-stats/plug-lib/osm-tiles-lib.php";

require('app-lib/php/init.php');

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>