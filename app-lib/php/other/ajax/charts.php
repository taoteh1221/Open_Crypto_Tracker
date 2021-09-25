<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



require_once($base_dir . '/app-lib/php/other/sub-init/minimized-sub-init.php');


// Charts library

$font_width = 9; // NOT MONOSPACE, SO WE GUESS AN AVERAGE
$link_spacer = 75; // Space beetween lite chart links


// ASSET PRICE CHARTS
if ( $_GET['mode'] == 'asset_price' ) {
require_once($base_dir . '/app-lib/php/other/ajax/charts/asset_price.php');
}
// ASSET BALANCE CHART
elseif ( $_GET['mode'] == 'asset_balance' ) {
require_once($base_dir . '/app-lib/php/other/ajax/charts/asset_balance.php');
}
// ASSET PERFORMANCE CHART
elseif ( $_GET['mode'] == 'asset_performance' ) {
require_once($base_dir . '/app-lib/php/other/ajax/charts/asset_performance.php');
}
// MARKETCAP DATA
elseif ( $_GET['mode'] == 'marketcap_data' ) {
require_once($base_dir . '/app-lib/php/other/ajax/charts/marketcap_data.php');
}
// SYSTEM CHARTS
elseif ( $_GET['mode'] == 'system' ) {
require_once($base_dir . '/app-lib/php/other/ajax/charts/system.php');
}


flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

?>