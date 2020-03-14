<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 
 
$runtime_mode = 'chart_output';

// Change directory
chdir("../../");
require("config.php");


if ( $_GET['type'] == 'asset' ) {
require($base_dir . '/templates/interface/php/user/charts/asset-charts.php');
}
elseif ( $_GET['type'] == 'system' ) {
require($base_dir . '/templates/interface/php/user/charts/system-charts.php');
}
elseif ( $_GET['type'] == 'balance_stats' ) {
require($base_dir . '/templates/interface/php/user/charts/balance-stats-charts.php');
}

?>
