<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

 
// Runtime mode
$runtime_mode = 'ajax';

// Running BEFORE calling config.php

if ( !isset($_GET['type']) ) {
exit;
}

// Change directory
chdir("../../../");

require('app-lib/php/init.php');


// Running AFTER calling init.php

if ( $_GET['type'] == 'chart' && $_GET['mode'] == 'sol_nodes' ) {
require_once($ct['plug']->plug_dir(false, 'on-chain-stats') . '/plug-assets/ajax/charts/solana-nodes-chart.php');
}
elseif ( $_GET['type'] == 'map' && $_GET['mode'] == 'sol_geolocation' ) {
require_once($ct['plug']->plug_dir(false, 'on-chain-stats') . '/plug-assets/ajax/maps/solana-nodes-map.php');
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>