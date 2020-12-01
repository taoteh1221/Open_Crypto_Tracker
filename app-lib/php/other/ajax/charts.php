<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



require_once($base_dir . '/app-lib/php/other/sub-init/minimized-sub-init.php');


// Charts library

$font_width = 9; // NOT MONOSPACE, SO WE GUESS AN AVERAGE
$link_spacer = 75; // Space beetween lite chart links


// ASSET CHARTS
if ( $_GET['type'] == 'asset' ) {
require_once($base_dir . '/app-lib/php/other/ajax/charts/asset.php');
}
// SYSTEM CHARTS
elseif ( $_GET['type'] == 'system' ) {
require_once($base_dir . '/app-lib/php/other/ajax/charts/system.php');
}
// ASSET PERFORMANCE CHART
elseif ( $_GET['type'] == 'asset_performance' ) {
require_once($base_dir . '/app-lib/php/other/ajax/charts/asset_performance.php');
}


flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

?>