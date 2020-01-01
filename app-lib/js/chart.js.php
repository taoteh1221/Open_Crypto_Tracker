<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 
 
$runtime_mode = 'chart_output';

// Change directory
chdir("../../");
require("config.php");


if ( $_GET['type'] == 'asset' ) {
require($base_dir . '/app-lib/php/other/asset-charts.php');
}
elseif ( $_GET['type'] == 'system' ) {
require($base_dir . '/app-lib/php/other/system-charts.php');
}

?>
