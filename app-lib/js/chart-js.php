<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 
 
// Split sleeps between chart / ajax external calls, AND UI runtime to randomly spread calls apart better
usleep(500000); // Wait 0.50 seconds, so low power devices (like a raspberry pi) don't get ddos attacked by accident

$runtime_mode = 'chart_output';

// Change directory
chdir("../../");
require("config.php");


if ( $_GET['type'] == 'asset' ) {
require($base_dir . '/templates/interface/php/user/user-charts/asset-charts.php');
}
elseif ( $_GET['type'] == 'system' ) {
require($base_dir . '/templates/interface/php/admin/admin-charts/system-charts.php');
}
elseif ( $_GET['type'] == 'balance_stats' ) {
require($base_dir . '/templates/interface/php/user/user-charts/balance-stats-charts.php');
}

?>
